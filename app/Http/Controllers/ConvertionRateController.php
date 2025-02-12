<?php

namespace App\Http\Controllers;

use App\Models\ConvertionRate;
use App\Http\Requests\StoreConvertionRateRequest;
use App\Http\Requests\UpdateConvertionRateRequest;
use Illuminate\Support\Facades\DB;

class ConvertionRateController extends Controller {
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConvertionRateRequest $request, ConvertionRate $convertionrate) {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $convertionrate->update($validated);
            DB::commit();
            return redirect(route('dashboard'))->with('success', 'Convertion Rate Updated');
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th
            return back()->with('error', $th->getMessage());
        }
    }
}
