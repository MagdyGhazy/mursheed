<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('settings')->as('setting')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\SettingsController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Api\SettingsController::class, 'store'])->name('store');
        Route::post('/{setting}', [\App\Http\Controllers\Api\SettingsController::class, 'update'])->name('update');
    });
});
