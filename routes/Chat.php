<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(\App\Http\Controllers\Api\Chat\ConversationsController::class)->group(function () {
        // Get all conversations
        Route::get('conversations', 'index');

        // Route to get a single conversation
        Route::get('conversations/{conversation}', 'show');//------------
    });

    Route::controller(\App\Http\Controllers\Api\Chat\MessagesController::class)->group(function () {
        //get User chat from id
        Route::get('conversations/{id}/messages', 'index');

        // Route for send new message
        Route::post('messages', 'store'); //--------
        Route::post('storeMusheeduser', 'storeMusheeduser'); //--------

    });

    Route::controller(\App\Http\Controllers\Api\Chat\ChatController::class)->group(function () {
        // Get All Friends
        Route::get('friends', 'index');//----------

        //Get Al Chats
        Route::get('chats', 'chats');//----------
    });

});
