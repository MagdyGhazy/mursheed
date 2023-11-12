<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'pages'], function () {
        Route::get('/', [\App\Http\Controllers\Api\PageController::class, 'index'])->name('page.index');
        Route::post('/create', [\App\Http\Controllers\Api\PageController::class, 'store'])->name('page.create');

        Route::get('/{page}', [\App\Http\Controllers\Api\PageController::class, 'show'])->name('page.show');
        Route::post('/{page}/update', [\App\Http\Controllers\Api\PageController::class, 'update'])->name('page.update');
        Route::delete('/{page}/delete', [\App\Http\Controllers\Api\PageController::class, 'destroy'])->name('page.delete');
    });



});
