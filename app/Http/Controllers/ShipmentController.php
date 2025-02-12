<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateShipmentRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $marketing = null;

        if (request('marketing')) {
            $marketing = User::where('slug', request('marketing'))->first();
        }

        return view('shipment.index', [
            'shipments' => Shipment::latest()->filters(request(['search', 'marketing']))->get(),
            "marketing" => $marketing
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('shipment.create', [
            "shipments" => null,
            "users" => User::where('is_admin', 0)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShipmentRequest $request) {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            Shipment::create($validated);
            DB::commit();
            return redirect(route('shipments.index'))->with('success', "New Shipments Added");
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', 'New Shipments cannot be created');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment) {
        return view('shipment.create', [
            'shipments' => $shipment,
            "users" => User::where('is_admin', 0)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipmentRequest $request, Shipment $shipment) {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $shipment->update($validated);
            DB::commit();
            return redirect(route('shipments.index'))->with('success', "Data Shipments $shipment->name Edited");
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', "Data Shipments $shipment->name cannot be Edited");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment) {
        try {
            DB::beginTransaction();
            $shipment->delete();
            DB::commit();
            return redirect(route('shipments.index'))->with('success', "Data Shipments $shipment->name Deleted");
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return back()->with('error', "Data Shipments $shipment->name cannot be Deleted");
        }
    }
}
