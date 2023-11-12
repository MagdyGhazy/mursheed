<?php


Route::middleware('auth:sanctum')->group(function () {

    Route::group(['prefix' => 'flights'], function () {
        Route::get('/', [\App\Http\Controllers\Api\FlightReservationController::class, 'index'])->name('flight.index');
        Route::get('/all', [\App\Http\Controllers\Api\FlightReservationController::class, 'paginateIndex'])->name('flight.paginateIndex');
        Route::post('/create', [\App\Http\Controllers\Api\FlightReservationController::class, 'store'])->name('flight.create');
        Route::get('/{flight}', [\App\Http\Controllers\Api\FlightReservationController::class, 'show'])->name('flight.show');
        Route::post('/{flight}/update', [\App\Http\Controllers\Api\FlightReservationController::class, 'update'])->name('flight.update');
        Route::delete('/{flight}/delete', [\App\Http\Controllers\Api\FlightReservationController::class, 'destroy'])->name('flight.delete');
    });



});
