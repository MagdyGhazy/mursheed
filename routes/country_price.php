<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix("country-price")->as('country_price.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\CountryPriceController::class, 'index'])->name('index');
        Route::post('/create', [\App\Http\Controllers\Api\CountryPriceController::class, 'store'])->name('store');
        Route::get('/{country_price}', [\App\Http\Controllers\Api\CountryPriceController::class, 'show'])->name('show');
        Route::post('/{country_price}/update', [\App\Http\Controllers\Api\CountryPriceController::class, 'update'])->name('update');
        Route::post('/{country_price}/active', [\App\Http\Controllers\Api\CountryPriceController::class, 'active'])->name('active');
        Route::post('/{country_price}/de-active', [\App\Http\Controllers\Api\CountryPriceController::class, 'deActive'])->name('de_active');
    });
});
