<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\MobileApi\OrderController;
use App\Http\Controllers\Api\AccommmoditionOrderController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'reports'], function () {

        Route::get("fillter", [ReportController::class, "index"]);
        Route::get('profite', [OrderController::class, 'profiteCost']);

        Route::post('allProfits/{country_id}', [ReportController::class, 'profits']);

        Route::apiResource('roles', RoleController::class);
        Route::apiResource('accommmodition/order', AccommmoditionOrderController::class);
    });
});
