<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Goods;
use App\Models\Job_Detail;
use App\Models\Shipment;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JobController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Shipment $shipment) {

        $filters = [
            'shipment' => $shipment->slug,
            "search" => request()->only('search')['search'] ?? null
        ];

        // dd($filters);

        return view('jobs.index', [
            "jobs" => Job::latest()->filters($filters)->get(),
            "shipment" => $shipment
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Shipment $shipment) {
        return view('jobs.create', [
            "job" => null,
            'shipment' => $shipment
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request, Shipment $shipment) {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            // $validated['supplier_id'] = $supplier->id;

            if (Job::where('status', 0)->where('shipment_id', $shipment->id)->first()) {
                throw new \Exception("Unable to create a new job when there is a job still in progress.");

                // return redirect(route('jobs.index'))->with('error', "");
            }

            $validated['shipment_id'] = $shipment->id;

            $job = Job::create($validated);

            DB::commit();

            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('success', "Job $job->no_job Successfuly Created");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment, Job $job) {
        return view('jobs.show', [
            "shipment" => $shipment,
            "job" => $job,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment, Job $job): RedirectResponse|View {
        if ($job->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }


        if ($job->status) {
            return redirect(route('jobs.index'))->with('error', 'A done job cannot be modified.');
        }
        return view('jobs.create', [
            "shipment" => $shipment,
            "job" => $job,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request,  Shipment $shipment, Job $job) {
        if ($job->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index'))->with('error', 'A done job cannot be modified.');
        }
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $job->update($validated);
            DB::commit();
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('success', 'Job Successfuly Edited');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Job cannot be updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment, Job $job) {
        if ($job->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index'))->with('error', 'A done job cannot be deleted.');
        }

        try {
            DB::beginTransaction();
            $job->job_detail->each(function ($detail) use ($job) {
                $goods = Goods::where('id', $detail->goods_id)->first();
                $goods->increment('stock', $detail->amount);
                Stock::create(['goods_id' => $detail->goods_id, 'amount' => $detail->amount, 'type' => 'stock', 'stock' => $goods->stock, 'desc' => "Job $job->no_job was Deleted, Goods in Stock"]);
                $detail->delete();
            });
            $job->delete();
            DB::commit();
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('success', 'Job Successfuly Edited');
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return back()->with('error', $th->getMessage());
        }
    }
}