<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'guides'], function () {
        Route::get('/', [\App\Http\Controllers\Api\GuidesCotroller::class, 'index'])->name('guide.index');
        Route::get('/all', [\App\Http\Controllers\Api\GuidesCotroller::class, 'index_mobile'])->name('guide.index_mobile');
        Route::get('/latest', [\App\Http\Controllers\Api\GuidesCotroller::class, 'getLatestWithCity'])->name('guides.latest');
        Route::get('/{guide}', [\App\Http\Controllers\Api\GuidesCotroller::class, 'show'])->name('guide.show');
        Route::post('/{guide}/update', [\App\Http\Controllers\Api\GuidesCotroller::class, 'update'])->name('guide.update');
        Route::post('update', [\App\Http\Controllers\Api\GuidesCotroller::class, 'update_mobile'])->name('guide.update_mobile');
        Route::post('/get-guide-by-city', [\App\Http\Controllers\Api\GuidesCotroller::class, 'getGuideByCityWithPriceList'])->name('guide.getGuideByCityWithPriceList');
        Route::post('/get-guide-by-country', [\App\Http\Controllers\Api\GuidesCotroller::class, 'getGuideByCountryWithPriceList'])->name('guide.getGuideByCountryWithPriceList');
        Route::post('/{guide}/active', [\App\Http\Controllers\Api\GuidesCotroller::class, 'active'])->name('guide.active');
        Route::post('/{guide}/inActive', [\App\Http\Controllers\Api\GuidesCotroller::class, 'inActive'])->name('guide.inActive');
        Route::post('/{guide}/changeStatus', [\App\Http\Controllers\Api\GuidesCotroller::class, 'change'])->name('guide.changeStatus');
        Route::delete('/{guide}/delete', [\App\Http\Controllers\Api\GuidesCotroller::class, 'destroy'])->name('guide.delete');
    });
});



Route::post('guides/create', [\App\Http\Controllers\Api\GuidesCotroller::class, 'store'])->name('guide.create');
