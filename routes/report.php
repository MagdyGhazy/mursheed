<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\MobileApi\OrderController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'reports'], function () {

        Route::get("fillter", [ReportController::class, "index"]);
        Route::get('profite',[OrderController::class,'profiteCost']);


        Route::post('allProfits/{country_id}',[ReportController::class,'allProfits']);
    });
});
