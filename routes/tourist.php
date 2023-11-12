<?php





Route::post('tourists/create', [\App\Http\Controllers\Api\TouristController::class, 'store'])->name('tourist.create');

Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'tourists'], function () {
        Route::get('/', [\App\Http\Controllers\Api\TouristController::class, 'index'])->name('tourist.index');
        Route::get('/{tourist}', [\App\Http\Controllers\Api\TouristController::class, 'show'])->name('tourist.show');
        Route::post('/{tourist}/update', [\App\Http\Controllers\Api\TouristController::class, 'update'])->name('tourist.update');
        Route::post('update', [\App\Http\Controllers\Api\TouristController::class, 'update_mobile'])->name('tourist.update_mobile');
        //        Route::post('/{tourist}/active', [\App\Http\Controllers\Api\TouristController::class, 'active'])->name('tourist.active');
        //        Route::post('/{tourist}/inActive', [\App\Http\Controllers\Api\TouristController::class, 'inActive'])->name('tourist.inActive');
        //        Route::post('/{tourist}/changeStatus', [\App\Http\Controllers\Api\TouristController::class, 'change'])->name('tourist.changeStatus');
        Route::delete('/{tourist}/delete', [\App\Http\Controllers\Api\TouristController::class, 'destroy'])->name('tourist.delete');
    });
});
