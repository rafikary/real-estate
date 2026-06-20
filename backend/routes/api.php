<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandBankController;
use App\Http\Controllers\MasterProjectController;
use App\Http\Controllers\UpdateSertifikatController;
use App\Http\Controllers\SiteplanController;
use App\Http\Controllers\SplitGroupingLandController;
use App\Http\Controllers\MasterInterfaceController;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\MasterSupplierController;
use App\Http\Controllers\UpdateLandbankController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    // ── Land Bank ────────────────────────────────────────────────────────
    Route::apiResource('land-banks', LandBankController::class);
    Route::get('land-banks/{id}/regions', [LandBankController::class, 'getRegions'])->missing(fn() => response()->json(['message'=>'Not found'],404));

    // ── Master Proyek ────────────────────────────────────────────────────
    Route::get('projects/land-bank-options', [MasterProjectController::class, 'landBankOptions']);
    Route::apiResource('projects', MasterProjectController::class);

    // ── Update Sertifikat ────────────────────────────────────────────────
    Route::get('update-sertifikats/land-bank-options', [UpdateSertifikatController::class, 'landBankOptions']);
    Route::apiResource('update-sertifikats', UpdateSertifikatController::class);

    // ── Siteplan ─────────────────────────────────────────────────────────
    Route::get('siteplans/project-options', [SiteplanController::class, 'projectOptions']);
    Route::apiResource('siteplans', SiteplanController::class);

    // ── Split / Grouping Land ────────────────────────────────────────────
    Route::get('split-grouping-lands/land-bank-options', [SplitGroupingLandController::class, 'landBankOptions']);
    Route::apiResource('split-grouping-lands', SplitGroupingLandController::class);

    // ── Master Interface ─────────────────────────────────────────────────
    Route::apiResource('interfaces', MasterInterfaceController::class);

    // ── Master Item ──────────────────────────────────────────────────────
    Route::apiResource('items', MasterItemController::class);

    // ── Master Supplier ──────────────────────────────────────────────────
    Route::apiResource('suppliers', MasterSupplierController::class);

    // ── Update Landbank (Transaction) ────────────────────────────────────
    Route::apiResource('update-landbanks', UpdateLandbankController::class);

});
