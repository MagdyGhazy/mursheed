<?php


use App\Http\Controllers\MobileApi\OrderController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'orders'], function () {
        Route::post('/status/{order}', [\App\Http\Controllers\MobileApi\OrderController::class, 'statusOrder'])->name('order.approvedOrder');

        Route::get('/', [\App\Http\Controllers\MobileApi\OrderController::class, 'index'])->name('order.index');
        Route::post('/myOrders', [\App\Http\Controllers\MobileApi\OrderController::class, 'getMyOrders'])->name('orders.myOrders');

        Route::get('{order_id}', [\App\Http\Controllers\MobileApi\OrderController::class, 'show'])->name('order.show');

        Route::post('/submit/{order}', [\App\Http\Controllers\MobileApi\OrderController::class, 'submitOrder'])->name('order.submitOrder');

        Route::post('/create', [\App\Http\Controllers\MobileApi\OrderController::class, 'store'])->name('orders.create');
        Route::post('/getPrice', [\App\Http\Controllers\MobileApi\OrderController::class, 'getPrice'])->name('orders.getPrice');

    });
});
