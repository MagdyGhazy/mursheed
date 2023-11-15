<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'accommodition'], function () {
        Route::get('/', [\App\Http\Controllers\Api\AccommmoditionController::class, 'index'])->name('accommodition.index');
        Route::get('/all', [\App\Http\Controllers\Api\AccommmoditionController::class, 'paginatedIndex'])->name('accommodition_category.paginatedIndex');

        Route::post('/create', [\App\Http\Controllers\Api\AccommmoditionController::class, 'store'])->name('accommodition.create');

        Route::get('/{accommmodition}', [\App\Http\Controllers\Api\AccommmoditionController::class, 'show'])->name('accommodition.show');
        Route::post('/{accommmodition}/update', [\App\Http\Controllers\Api\AccommmoditionController::class, 'update'])->name('accommodition.update');
        Route::delete('/{accommmodition}/delete', [\App\Http\Controllers\Api\AccommmoditionController::class, 'destroy'])->name('accommodition.delete');
    });
});


Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'accommodition_category'], function () {
        Route::get('/', [\App\Http\Controllers\Api\CategoryAcccommodation::class, 'index'])->name('accommodition_category.index');
        Route::post('/create', [\App\Http\Controllers\Api\CategoryAcccommodation::class, 'store'])->name('accommodition_category.create');

        Route::get('/{accommmodition}', [\App\Http\Controllers\Api\CategoryAcccommodation::class, 'show'])->name('accommodition_category.show');
        Route::post('/{accommmodition}/update', [\App\Http\Controllers\Api\CategoryAcccommodation::class, 'update'])->name('accommodition_category.update');
        Route::delete('/{accommmodition}/delete', [\App\Http\Controllers\Api\CategoryAcccommodation::class, 'destroy'])->name('accommodition_category.delete');
    });
});
Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'attracives'], function () {
        Route::get('/', [\App\Http\Controllers\Api\AttactiveController::class, 'index'])->name('attractive.index');
        Route::get('/all', [\App\Http\Controllers\Api\AttactiveController::class, 'index_mobile'])->name('attractive.index_mobile');
        Route::post('/create', [\App\Http\Controllers\Api\AttactiveController::class, 'store'])->name('attractive.create');
        Route::get('home', [\App\Http\Controllers\Api\AttactiveController::class, 'home'])->name('attractive.home');
//        Route::get('/{attractiveLocation}', [\App\Http\Controllers\Api\AttactiveController::class, 'show'])->name('attractive.show');
        Route::get('/{attractiveLocation}', function (){
            return "ds";
        })->name('attractive.show');
        Route::post('/{attractiveLocation}/update', [\App\Http\Controllers\Api\AttactiveController::class, 'update'])->name('attractive.update');
        Route::delete('/{attractiveLocation}/delete', [\App\Http\Controllers\Api\AttactiveController::class, 'destroy'])->name('attractive.delete');
    });
});
