<?php

namespace App\Http\Controllers;

use App\Models\Job_Detail;
use App\Http\Requests\StoreJob_DetailRequest;
use App\Http\Requests\UpdateJob_DetailRequest;
use App\Models\Goods;
use App\Models\Job;
use App\Models\Shipment;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class JobDetailController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Job $job) {
        return view('details.create', [
            "job" => $job,
            "goods" => null,
            "detail" => null,
            'shipment' => $job->shipment
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJob_DetailRequest $request, Job $job) {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
            // dd($validated);
            foreach ($validated['goods'] as $item) {
                $good = Goods::where('id', $item['goods_id'])->first();
                $detail = Job_Detail::where('goods_id', $good->id)->where('job_id', $job->id)->first() ??  Job_Detail::create(['job_id' => $job->id, 'goods_id' => $item->id, 'amount' => 0]);;
                if ($item['type'] == 'depart') {
                    $item['amount'] = ($item['amount'] >= $good->stock) ? $good->stock : $item['amount'];
                    $detail->increment('amount', $item['amount']);
                    Stock::create(['goods_id' => $item['goods_id'], 'type' => 'depart', 'stock' => $good->stock - $item['amount'], 'amount' => $item['amount'], 'desc' => "Goods depart into job $job->no_job"]);
                    $good->decrement('stock', $item['amount']);
                } elseif ($item['type'] == 'stock') {
                    $detail->decrement('amount', $item['amount']);
                    Stock::create(['goods_id' => $item['goods_id'], 'type' => 'stock', 'stock' => $good->stock + $item['amount'], 'amount' => $item['amount'], 'desc' => "Goods moved to stock from job $job->no_job"]);
                    $good->increment('stock', $item['amount']);
                } elseif ($item['type'] == 'returned') {
                    if ($item['amount'] > $detail->amount) {
                        $item['amount'] = $detail->amount;
                    }
                    Stock::create(['goods_id' => $item['goods_id'], 'type' => 'returned', 'stock' => $good->stock, 'amount' => $item['amount'], 'desc' => "Goods on job $job->no_job leave the warehouse"]);
                    $detail->decrement('amount', $item['amount']);
                }
            }

            DB::commit();

            return redirect(route('jobs.show', ["job" => $job->slug]))->with('success', 'Success');
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Job_Detail $detail) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job, Job_Detail $detail) {
        return view('details.create', [
            "job" => $job,
            "detail" => $detail,
            'shipment' => $job->shipment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJob_DetailRequest $request,  Job $job,  Job_Detail $detail) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job, Job_Detail $detail) {
        try {
            DB::beginTransaction();
            $detail->goods->increment("stock", $detail->amount);
            Stock::create(['goods_id' => $detail->goods->id, 'type' => "returned", 'desc' => "Goods returned from $job->no_job", 'amount' => $detail->amount, "stock" => $detail->goods->stock]);
            $detail->delete();
            DB::commit();
            return back()->with('success', 'Goods stocks returned');
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', '');
        }
    }
}