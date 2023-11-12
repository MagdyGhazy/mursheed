<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix("favourite")->as('favourite.')->middleware('auth')->group(function () {
        Route::get('/', [\App\Http\Controllers\MobileApi\FavouriteController::class, 'index'])->name('index');
        Route::post('/create', [\App\Http\Controllers\MobileApi\FavouriteController::class, 'store'])->name('store');

    });
});