<?php

use App\Http\Controllers\Api\ReportController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'reports'], function () {
        Route::post("fillter", [ReportController::class, "index"]);
    });
});