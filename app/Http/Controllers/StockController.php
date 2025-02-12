<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\Goods;
use App\Models\Job;
use App\Models\Job_Detail;
use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class StockController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Shipment $shipment, Supplier $supplier, Goods $good) {
        if (!$good->weight ?? false) {
            return redirect(route('goods.edit', ['shipment' => $shipment->slug, 'supplier' => $supplier->slug, 'good' => $good]))->with('success', '');
        }

        return view('stocks.index', [
            "goods" => $good,
            'shipment' => $shipment,
            'supplier' => $supplier,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStockRequest $request, Shipment $shipment, Supplier $supplier, Goods $good) {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            if ($validated['note'] ?? false) {
                $validated['note'] = $request->file('note')->store('note');
            }

            if ($validated['type'] == 'supplier') {
                $good->increment('at_supplier', $validated['amount']);
                Stock::create(['goods_id' => $good->id, 'note' => $validated['note'], 'type' => 'supplier', 'stock' => $good->stock, 'amount' => $validated['amount'], 'desc' => "The goods are still at the supplier."]);
                $message = "Goods $good->code stock updated, but the goods still at supplier";
            } elseif ($validated['type'] == 'stock') {
                // $desc = "Goods arrived at warehouse";
                if ($good->at_supplier > 0) {
                    $validated['amount'] = min($validated['amount'], $good->at_supplier);
                    $good->decrement('at_supplier', $validated['amount']);
                    $desc = $validated['amount'] . " Stock from Supplier arrived at warehouse";
                }
                $good->increment('stock', $validated['amount']);
                Stock::create(['goods_id' => $good->id, 'note' => $validated['note'], 'type' => 'stock', 'stock' => $good->stock, 'amount' => $validated['amount'], 'desc' => $desc ?? "Goods enter the warehouse"]);
                $message = "Goods $good->code Successfuly Added in warehouse";
            } elseif ($validated['type'] == 'returned') {
                $validated['amount'] = min($validated['amount'], $good->stock);
                Stock::create(['goods_id' => $good->id, 'type' => 'returned', "note" => $validated['note'] , 'stock' => $good->stock - $validated['amount'], 'amount' => $validated['amount'], 'desc' => "Goods leave the warehouse"]);
                $good->decrement('stock', $validated['amount']);
                $message = "Goods $good->code Successfuly leave the warehouse";
            }

            DB::commit();
            return redirect(route('goods.show', ['good' => $good->slug, 'shipment' => $shipment->slug, 'supplier' => $supplier->slug,]))->with('success', $message);
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', $th->getMessage());
        }
    }
}