<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Shipment;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Throw_;
use Throwable;

class SupplierController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Shipment $shipment) {
        $filters = ['search' => request()->only('search')['search'] ?? false, 'shipment' => $shipment->slug];

        // dd($filters);

        $supplier = Supplier::latest()->filters($filters);

        return view('supplier.index', [
            "suppliers" => $supplier->get(),
            'shipment' => $shipment
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Shipment $shipment) {
        return view('supplier.create', [
            'supplier' => null,
            'shipment' => $shipment,
            'users' => User::where("is_admin", 0)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request, Shipment $shipment) {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $validated['shipment_id'] = $shipment->id;

            Supplier::create($validated);

            DB::commit();
            return redirect(route('suppliers.index', ['shipment' => $shipment->slug]))->with("success", "Supplier for Shipment $shipment->name Created");
        } catch (Throwable $th) {
            DB::rollback();
            return back()->with("error", "Data cannot be created.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment, Supplier $supplier) {
        return redirect(route('suppliers.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment, Supplier $supplier) {
        if ($supplier->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        return view('supplier.create', [
            "supplier" => $supplier,
            "shipment" => $shipment,
            "users" => User::where("is_admin", 0)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Shipment $shipment, Supplier $supplier) {
        if ($supplier->shipment->isNot($shipment)) {
            return redirect(route('shipments.index'))->with('error', "Data doesn't Match");
        }
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $supplier->update($validated);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with("error", 'Data cannot be changed.');
        }

        return redirect(route('suppliers.index', ['shipment' => $shipment->slug]))->with('success', 'Data Updated ');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Supplier $supplier) {
    //     try {
    //         DB::beginTransaction();

    //         Storage::delete($supplier->image);
    //         $supplier->delete();

    //         DB::commit();
    //     } catch (Throwable $th) {
    //         DB::rollback();
    //         return back()->with('error', $th->getMessage());
    //     }

    //     return redirect(route('suppliers.index'))->with('success', 'Data deleted');
    // }
}