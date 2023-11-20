<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\MobileApi\OrderController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'reports'], function () {
        Route::post("fillter", [ReportController::class, "index"]);
        Route::get('profite',[OrderController::class,'profiteCost']);
    });
});