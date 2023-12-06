<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'drivers'], function () {
        Route::get('/', [\App\Http\Controllers\Api\Drivercontroller::class, 'index'])->name('driver.index');

        Route::get('/all', [\App\Http\Controllers\Api\Drivercontroller::class, 'index_mobile'])->name('driver.index_mobile');
        Route::get('/latest', [\App\Http\Controllers\Api\Drivercontroller::class, 'getLatestWithCity'])->name('driver.latest');

        Route::get('/{driver}', [\App\Http\Controllers\Api\Drivercontroller::class, 'show'])->name('driver.show');
        Route::get('/web/{driver}', [\App\Http\Controllers\Api\Drivercontroller::class, 'show_web'])->name('driver.web.show');
        Route::post('/{driver}/update', [\App\Http\Controllers\Api\Drivercontroller::class, 'update'])->name('driver.update');
        Route::post('update', [\App\Http\Controllers\Api\Drivercontroller::class, 'update_mobile'])->name('driver.update_mobile');
        Route::post('/get-driver-by-city', [\App\Http\Controllers\Api\Drivercontroller::class, 'getDriverByCityWithPriceList'])->name('driver.getDriverByCityWithPriceList');
        Route::post('/get-driver-by-country', [\App\Http\Controllers\Api\Drivercontroller::class, 'getDriverByCountryWithPriceList'])->name('driver.getDriverByCountryWithPriceList');

        Route::post('/{driver}/active', [\App\Http\Controllers\Api\Drivercontroller::class, 'active'])->name('driver.active');
        Route::post('/{driver}/inActive', [\App\Http\Controllers\Api\Drivercontroller::class, 'inActive'])->name('driver.inActive');
        Route::post('/{driver}/changeStatus', [\App\Http\Controllers\Api\Drivercontroller::class, 'change'])->name('driver.changeStatus');
        Route::delete('/{driver}/delete', [\App\Http\Controllers\Api\Drivercontroller::class, 'destroy'])->name('driver.delete');

    });
});

Route::post('drivers/create', [\App\Http\Controllers\Api\Drivercontroller::class, 'store'])->name('driver.create');

