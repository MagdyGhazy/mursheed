<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\MobileApi\OrderController;
use App\Http\Controllers\Api\AccommmoditionOrderController;

Route::middleware('auth:sanctum')->group(function () {
  Route::group(['prefix' => 'reports'], function () {

    Route::post("fillter", [ReportController::class, "index"]);
    Route::get('profite', [OrderController::class, 'profiteCost']);

    Route::post('allProfits', [ReportController::class, 'profits']);
    Route::post('allProfitsSixMonths', [ReportController::class, 'profitsFromSixMonths']);

    Route::apiResource('roles', RoleController::class);
    Route::post("filter/accommmodition/order", [AccommmoditionOrderController::class, 'filter']);
    Route::apiResource('accommmodition/order', AccommmoditionOrderController::class);
    Route::post('accommmodition/order/filter', [AccommmoditionOrderController::class, 'filter']);
  });
});
