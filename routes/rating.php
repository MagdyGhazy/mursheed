<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix("rating")->as('rating.')->middleware('auth')->group(function () {
        Route::get('', [\App\Http\Controllers\MobileApi\RatingController::class, 'index'])->name('index');
        Route::post('/create', [\App\Http\Controllers\MobileApi\RatingController::class, 'store'])->name('store');
        Route::post('/create-admin', [\App\Http\Controllers\MobileApi\RatingController::class, 'createAdminRating'])->name('store.admin');
        // Route::get('/{country_price}', [\App\Http\Controllers\MobileApi\RatingController::class, 'show'])->name('show');
        // Route::post('/{country_price}/update', [\App\Http\Controllers\MobileApi\RatingController::class, 'update'])->name('update');
        // Route::post('/{country_price}/active', [\App\Http\Controllers\MobileApi\RatingController::class, 'active'])->name('active');
        // Route::post('/{country_price}/de-active', [\App\Http\Controllers\MobileApi\RatingController::class, 'deActive'])->name('de_active');
    });
});
