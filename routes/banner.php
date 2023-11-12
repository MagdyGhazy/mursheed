<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', [\App\Http\Controllers\Api\BannerController::class, 'index'])->name('banner.index');
        Route::post('/create', [\App\Http\Controllers\Api\BannerController::class, 'store'])->name('banner.create');
        Route::get('/{banner}', [\App\Http\Controllers\Api\BannerController::class, 'show'])->name('banner.show');
        Route::post('/{banner}/update', [\App\Http\Controllers\Api\BannerController::class, 'update'])->name('banner.update');
        Route::delete('/{banner}/delete', [\App\Http\Controllers\Api\BannerController::class, 'destroy'])->name('banner.delete');
    });
});
