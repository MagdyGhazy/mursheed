<?php


Route::controller(\App\Http\Controllers\Api\TermsAndConditionController::class)->group(function () {
    Route::get('conditions', 'index');
    Route::get('condition/{id}', 'show');
    Route::post('conditions', 'store');
    Route::post('condition/{id}', 'update');
    Route::post('conditions/{id}', 'destroy');
});
Route::get('ConditionsMobile', [\App\Http\Controllers\MobileApi\TermsAndConditionController::class, 'index'])->name('index');