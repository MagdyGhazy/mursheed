<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(\App\Http\Controllers\MessageController::class)->group(function () {
        Route::post('messages', 'createConversation');
        Route::get('AllMessages', 'index');
    });
    Route::controller(\App\Http\Controllers\RepliesController::class)->group(function () {
        Route::post('Replies', 'createReplay');
        Route::get('AllReplies', 'index');
    });
    Route::controller(\App\Http\Controllers\ConversationController::class)->group(function () {
        Route::get('AllConversation', 'index');
    });

});
