<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Http\Requests\StoreGoodsRequest;
use App\Http\Requests\UpdateGoodsRequest;
use App\Models\ConvertionRate;
use App\Models\Job;
use App\Models\Job_Detail;
use App\Models\Shipment;
use App\Models\Stock;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\View;

class GoodsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Shipment $shipment, Supplier $supplier): View|RedirectResponse {
        if ($supplier->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        // dd(request(['supplier']));
        return view('goods.index', [
            "goods" => Goods::filters(['search' => request()->only('search')['search'] ?? false, 'supplier' => $supplier->slug])->get(),
            "title" => "All Goods for Shipment $shipment->name at Supplier $supplier->name",
            "supplier" => $supplier,
            "shipment" => $shipment,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
    //  * @return View
     */
    public function create(Shipment $shipment, Supplier $supplier) {
        if ($supplier->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        return view('goods.create', [
            'goods' => null,
            "supplier" => $supplier,
            "shipment" => $shipment,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(StoreGoodsRequest $request, Shipment $shipment, Supplier $supplier): RedirectResponse {
        if ($supplier->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $stock = $validated['stock'];
            if ($validated['type'] == 'stock') {
                $convertionRate = ConvertionRate::first();
                $validated['image'] = $request->file('image')->store('goods');

                if ($validated['id_price'] != null && $validated['us_price'] == null) {
                    $validated['us_price'] = $validated['id_price'] / $convertionRate->rate;
                } elseif ($validated['id_price'] == null && $validated['us_price'] != null) {
                    $validated['id_price'] = $validated['us_price'] * $convertionRate->rate;
                }


                $stocks = ["type" => "stock", "desc" => "goods received in the warehouse", 'stock' => $stock];
            } elseif ($validated['type'] == 'supplier') {
                $stocks = ["type" => "supplier", "desc" => "The goods are still with the supplier.", 'stock' => 0];
                $validated['at_supplier'] = $validated['stock'];
                unset($validated['stock']);
            }
            $validated['shipment_id'] = $shipment->id;
            $validated['supplier_id'] = $supplier->id;

            $goods = Goods::create($validated);

            $stocks['goods_id'] = $goods->id;
            $stocks['amount'] = $stock;
            $stocks['note'] = $request->file('note')->store('note');
            Stock::create($stocks);

            DB::commit();
            return redirect(route('goods.index', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug]))->with('success', "Goods successfully created");
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @return View
     */
    public function show(Shipment $shipment, Supplier $supplier, Goods $good): View|RedirectResponse {
        if ($supplier->shipment->isNot($shipment) || $good->supplier->isNot($supplier)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        return view('goods.show', [
            "goods" => $good,
            "supplier" => $supplier,
            "shipment" => $shipment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment, Supplier $supplier, Goods $good): View|RedirectResponse {
        if ($supplier->shipment->isNot($shipment) || $good->supplier->isNot($supplier)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        return view('goods.create', [
            'goods' => $good,
            "supplier" => $supplier,
            "shipment" => $shipment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoodsRequest $request,  Shipment $shipment, Supplier $supplier, Goods $good): RedirectResponse {
        if ($supplier->shipment->isNot($shipment) || $good->supplier->isNot($supplier)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            if (isset($validated['image'])) {
                if ($good->image ?? false) {
                    Storage::delete($good->image);
                }
                $validated['image'] = $request->file('image')->store('goods');
            }
            $good->update($validated);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', $th->getMessage());
        }

        return redirect(route('goods.index', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug]))->with('success', "Goods successfully created");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment, Supplier $supplier, Goods $good): RedirectResponse {
        if ($supplier->shipment->isNot($shipment) || $good->supplier->isNot($supplier)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        try {
            if ($good->stock <= 0) {
                throw new \Exception("The goods are still available; please empty the stock first.");
            }

            DB::beginTransaction();
            $image = $good->image;
            $good->stocks->delete();
            $good->delete();
            DB::commit();
            Storage::delete($image);
            return back()->with('success', 'Goods Successfuly deleted');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Goods cannot be deleted');
        }
    }
}
