<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\MobileApi\OrderController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'order'], function () {
        Route::get('profite',[OrderController::class,'profiteCost']);
    });
});
