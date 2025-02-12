<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\Job;
use App\Models\Shipment;
use App\Models\BoxDetail;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreBoxDetailRequest;
use App\Http\Requests\UpdateBoxDetailRequest;
use App\Models\Goods;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class BoxDetailController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Shipment $shipment, Job $job, Box $box) {
        if ($job->shipment->isNot($shipment) || $box->job->isNot($job)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }
        return view('details.create', [
            'shipment' => $shipment,
            'job' => $job,
            'boxes' => $box,
            'detail' => null,
            'goods' => $shipment->goods
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxDetailRequest $request, Shipment $shipment, Job $job, Box $box) {
        if ($job->shipment->isNot($shipment) || $box->job->isNot($job)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            foreach ($validated['box'] as $item) {
                $item['box_id'] = $box->id;
                $goods = Goods::where('id', $item['goods_id'])->first();

                $item['amount'] = $item['amount'] > 0 ? min($item['amount'], $goods->stock) : $item['amount'];

                $stockData = ['goods_id' => $item['goods_id'], 'amount' => $item['amount'], 'type' => 'depart', 'name' => $item['name'], 'stock' => $goods->stock, 'desc' => $item['amount'] > 0 ?  "Goods depart into Job $job->no_job at Box $box->no_box" : "Part of Goods depart into Job $job->no_job at Box $box->no_box"];
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
            DB::commit();
            return redirect(route('boxes.index', [
                'shipment' => $shipment,
                'job' => $job,
            ]))->with('success', "Goods added into Box $box->no_box" . "$box->prefix");
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment, Job $job, Box $box, BoxDetail $detail) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment, Job $job, Box $box, BoxDetail $detail) {
        if ($job->shipment->isNot($shipment) || $box->job->isNot($job) || $detail->box->isNot($box)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }

        if ($detail->amount < 0) {
            return redirect(route('boxes.index', [
                'shipment' => $shipment,
                'job' => $job,
            ]))->with('success', "Goods cannot be modified, please remove it and add new goods");
        }

        // dd(Goods::where('id', $detail->stocks->goods_id)->get());
        return view('details.edit', [
            'shipment' => $shipment,
            'job' => $job,
            'boxes' => $box,
            'detail' => $detail,
            'goods' => Goods::where('id', $detail->stocks->goods_id)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoxDetailRequest $request, Shipment $shipment, Job $job, Box $box, BoxDetail $detail) {
        if ($job->shipment->isNot($shipment) || $box->job->isNot($job) || $detail->box->isNot($box)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        if ($job->status) {
            return redirect(route('jobs.index', ['shipment' => $shipment->slug]))->with('error', "A done jobs cannot modified");
        }
        $validated = $request->validated();

        // dd($detail);
        // jika amount > 0 dan type stock -> berarti tambah goods pada box dan kurangi pada amount

        try {
            DB::beginTransaction();
            foreach ($validated['box'] as $key => $item) {
                $goods = Goods::where('id', $detail->stocks->goods_id)->firstOrFail();
                if ($item['type'] == 'depart') {
                    // jika amount > 0 dan type depart -> berarti tambah amount pada box dan kurangi pada goods
                    $item['amount'] = min($item['amount'], $goods->stock);

                    $goods->decrement('stock', $item['amount']);
                    $detail->increment('amount', $item['amount']);
                    Stock::create(['goods_id' => $detail->stocks->goods_id, 'name' => $detail->stocks->name, 'type' => 'depart', 'amount' => $item['amount'], 'stock' => $goods->stock, 'desc' => "Goods depart into Box {$box->no_box}{$box->prefix} at Job $job->no_job"]);
                }
                if ($item['type'] == 'stock') {
                    // jika amount > 0 dan type depart -> berarti tambah amount pada box dan kurangi pada goods
                    $item['amount'] = min($item['amount'], $detail->amount);

                    $goods->increment('stock', $item['amount']);
                    $detail->decrement('amount', $item['amount']);
                    Stock::create(['goods_id' => $detail->stocks->goods_id, 'name' => $detail->stocks->name, 'type' => 'stock', 'amount' => $item['amount'], 'stock' => $goods->stock, 'desc' => "Goods are moved to stock from Box {$box->no_box}{$box->prefix} at Job $job->no_job"]);
                }
            }
            DB::commit();
            return redirect(route('boxes.index', [
                'shipment' => $shipment,
                'job' => $job,
            ]))->with('success', "Goods modified at Box {$box->no_box}{$box->prefix}");
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment, Job $job, Box $box, BoxDetail $detail) {
        try {
            DB::beginTransaction();
            $goods = Goods::where("id", $detail->stocks->goods_id)->firstOrFail();
            $goods->increment('stock', $detail->amount);
            Stock::create(['goods_id' => $goods->id, 'amount' => $detail->amount, 'type' => 'stock', 'name' => $detail->stocks->name, 'stock' => $goods->stock, "desc" => "Goods are removed from Box $box->no_box at Job $job->no_job"]);
            $detail->delete();

            DB::commit();
            return redirect(route('boxes.index', [
                'shipment' => $shipment,
                'job' => $job,
            ]))->with('success', "Goods removed from Box {$box->no_box}{$box->prefix}");
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', $th->getMessage());
        }
    }
}
