<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'chat'], function () {

        Route::controller(\App\Http\Controllers\Api\Chat\MessageController::class)->group(function () {
            Route::post('Conversation/messages', 'createConversation');
            Route::post('messages', 'createMessage');
            Route::get('AllMessages', 'index');
            Route::get('oneMessage/{id}', 'getOneMessage');
        });
        Route::controller(\App\Http\Controllers\Api\Chat\ReplayController::class)->group(function () {
            Route::post('Replies', 'createReplay');
            Route::get('AllReplies', 'index');
            Route::get('OneReplay/{id}', 'getOneReplay');
        });
        Route::controller(\App\Http\Controllers\Api\Chat\ConversationController::class)->group(function () {
            Route::get('AllConversation', 'index');
            Route::get('oneConversation/{id}', 'getOneConversation');
        });

    });
});
