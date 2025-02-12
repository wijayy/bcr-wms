<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Http\Requests\StoreBoxRequest;
use App\Http\Requests\UpdateBoxRequest;
use App\Models\BoxDetail;
use App\Models\Goods;
use App\Models\Job;
use App\Models\Shipment;
use App\Models\Stock;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;

class BoxController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Shipment $shipment, Job $job) {
        if ($job->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        return view('box.index', [
            'shipment' => $shipment,
            'job' => $job,
            'boxes' => Box::orderBy('no_box')->where('job_id', $job->id)->orderBy('no_box', 'desc')->get(),
        ]);
    }

    //
    //
    // 

    /**
     * Show the form for creating a new resource.
     */
    public function create(Shipment $shipment, Job $job) {
        if ($job->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }

        return view('box.create', [
            'shipment' => $shipment,
            'job' => $job,
            'boxes' => null,
            'goods' => $shipment->goods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxRequest $request, Shipment $shipment, Job $job) {
        if ($job->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }
        $validated = $request->validated();

        // dd($validated);
        try {
            DB::beginTransaction();
            $validated['job_id'] = $job->id;

            $box = Box::create($validated);

            if ($validated['box'] ?? false) {
                foreach ($validated['box'] as $item) {
                    $item['box_id'] = $box->id;
                    $goods = Goods::where('id', $item['goods_id'])->first();

                    $item['amount'] = $item['amount'] > 0 ? min($item['amount'], $goods->stock) : $item['amount'];

                    $stockData = ['goods_id' => $item['goods_id'], 'amount' => $item['amount'], 'type' => 'depart', 'name' => $item['name'], 'weight' => $item['weight'], 'stock' => $goods->stock, 'desc' => $item['amount'] > 0 ?  "Goods depart into Job $job->no_job at Box $box->no_box" : "Part of Goods depart into Job $job->no_job at Box $box->no_box"];
                    $stock = Stock::create($stockData);


                    if ($item['amount'] > 0) {
                        $goods->decrement('stock', $item['amount']);
                        $detail = BoxDetail::where('amount', '>', 0)->filters(['goods' => $goods->id])->firstOrCreate(['box_id' => $box->id], ['amount' => 0, 'stock_id' => $stock->id]);
                        $detail->increment('amount', $item['amount']);
                    } else {
                        $item['stock_id'] = $stock->id;
                        $detail = BoxDetail::create($item);
                    }
                }
            }
            DB::commit();
            return redirect(route('boxes.index', [
                'shipment' => $shipment,
                'job' => $job,
            ]))->with('success', "Box $box->no_box" . "$box->prefix Created");
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment, Job $job, Box $box) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment, Job $job, Box $box) {
        if ($job->shipment->isNot($shipment) || $box->job->isNot($job)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }
        return view('box.create', [
            'boxes' => $box,
            'shipment' => $shipment,
            'job' => $job,
            'boxdetail' => BoxDetail::where('box_id', $box->id)->get(['stock_id', 'amount']),
            'goods' => Goods::filters(['shipment_id' => $shipment->id])->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoxRequest $request, Shipment $shipment, Job $job, Box $box) {
        if ($job->shipment->isNot($shipment) || $box->job->isNot($job)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $box->update($validated);
            DB::commit();
            return redirect(route('boxes.index', ['shipment' => $shipment->slug, 'job' => $job->slug]))->with("success", "Box $box->no_box" . "$box->prefix Updated");
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment, Job $job, Box $box) {
        if ($job->shipment->isNot($shipment) || $box->job->isNot($job)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }

        try {
            DB::beginTransaction();
            foreach ($box->box_detail as $item) {
                $goods = Goods::where("id", $item->stocks->goods_id)->firstOrFail();
                $goods->increment('stock', $item->amount);
                Stock::create(['goods_id' => $goods->id, 'amount' => $item->amount, 'type' => 'stock', 'name' => $item->stocks->name, 'stock' => $goods->stock, "desc" => "Box $box->no_box at Job $job->no_job deleted, All goods removed"]);
            }
            $box->delete();
            DB::commit();
            return redirect(route('boxes.index', ['shipment' => $shipment->slug, 'job' => $job->slug]))->with("success", "Box $box->no_box" . "$box->prefix Deleted");
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', $th->getMessage());
        }
    }
}