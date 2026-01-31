<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AI\Kardex\ProductAnalysisController;

Route::middleware('throttle:ai')->prefix('ai/kardex')->group(function () {
    Route::post('/product/rotation', [ProductAnalysisController::class, 'rotation']);
    Route::post('/product/stock-risk', [ProductAnalysisController::class, 'stockRisk']);
});

