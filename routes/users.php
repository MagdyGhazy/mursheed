<?php

use App\Http\Controllers\MobileApi\SocialiteController;

Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [\App\Http\Controllers\Api\UserController::class, 'index'])->name('user.index');
        Route::post('/create', [\App\Http\Controllers\Api\UserController::class, 'store'])->name('user.create');

        Route::get('/{user}', [\App\Http\Controllers\Api\UserController::class, 'show'])->name('user.show');
        Route::post('/{user}/update', [\App\Http\Controllers\Api\UserController::class, 'update'])->name('user.update');
        Route::delete('/{user}/delete', [\App\Http\Controllers\Api\UserController::class, 'destroy'])->name('user.delete');
    });

});

