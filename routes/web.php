<?php

use App\Exports\BoxExport;
use App\Exports\ExportGoods;
use App\Exports\ExportStocks;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\BoxDetailController;
use App\Http\Controllers\ConvertionRateController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobDetailController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Models\Box;
use App\Models\BoxDetail;
use App\Models\ConvertionRate;
use App\Models\Goods;
use App\Models\Job;
use App\Models\Job_Detail;
use App\Models\Shipment;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return redirect(route('dashboard'));
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        "marketing" => User::where('is_admin', 0)->get(),
        "suppliers" => Supplier::all(),
        "goods" => Goods::all(),
        "jobs" => Job::all(),
        "convertionrate" => ConvertionRate::first(),
        'shipment' => Shipment::filters([])->get(),
        "stocks" => Stock::latest()->whereMonth('created_at', now())->whereYear('created_at', now())->get(),
        'details' => BoxDetail::all()
    ]);
})->middleware(['auth'])->name('dashboard');

Route::resource('marketing', MarketingController::class)->only(['index', 'create', 'store'])->middleware(['auth', 'admin']);
Route::resource('convertionrate', ConvertionRateController::class)->only('update')->middleware(['auth', 'admin']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/jobs/{job}', function (Job $job) {
        $job->update(['status' => 1]);

        return back()->with('success', "Job $job->no_job Done");
    })->name("job.done");

    Route::resource('shipments', ShipmentController::class)->only(['index']);

    Route::post('download/{stock}', function (Stock $stock) {
        $filePath = public_path('storage/' . $stock->note); // Path file
        $fileName = "note-" . $stock->goods->name . "-$stock->id.jpg"; // Nama file yang akan diunduh
        return response()->download($filePath, $fileName);
    })->name('download');

    // Route::resource("jobs.details", JobDetailController::class)->except(['index']);

    Route::get('/export', function () {
        return view(view: 'export');
    })->name('export.index');

    Route::post("/export-goods", function () {
        return Excel::download(new ExportGoods, 'multi-sheet.xlsx');
    })->name('export.goods');

    Route::post("/export-stocks", function (HttpRequest $request) {
        $validated = $request->validate([
            "year" => 'required',
            "month" => 'required',
        ]);
        return Excel::download(new ExportStocks($validated['month'], $validated['year']), "stocks_" . $validated['month'] . '-' . $validated['year'] . ".xlsx");
    })->name('export.stock');

    Route::get('export/{job}', function (Job $job) {
        // return view('export.box', ['job' => $job]);
        return Excel::download(new BoxExport($job), "Job $job->no_job.xlsx");
    })->name('export.box');
});

Route::middleware(['auth', 'restrictAccess'])->group(function () {
    Route::resource('shipments', ShipmentController::class)->except(['index', 'show']);
    Route::resource('shipments.suppliers', SupplierController::class)->except(['destroy'])->names('suppliers');
    Route::resource("shipments.jobs", JobController::class)->except(['show'])->names('jobs');
    Route::resource("shipments.jobs.boxes", BoxController::class)->names('boxes');
    Route::resource("shipments.jobs.boxes.detail", BoxDetailController::class)->names('detail');
    Route::resource("shipments.supplier.goods", GoodsController::class)->names('goods');
    Route::resource("shipments.supplier.goods.stocks", StockController::class)->only(['index', 'store'])->names('stocks');

});

require __DIR__ . '/auth.php';
