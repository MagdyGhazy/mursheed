<?php

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(\App\Http\Controllers\Api\Chat\ConversationsController::class)->group(function () {
        // Get all conversations
        Route::get('conversations', 'index');

        // Route to get a single conversation
        Route::get('conversations/{conversation}', 'show');

        Route::post('conversations/{conversation}/participants', 'addParticipant');

        // Route to delete a message
        Route::delete('conversations/{conversation}/participants', 'removeParticipant');
    });

    Route::controller(\App\Http\Controllers\Api\Chat\MessagesController::class)->group(function () {
        // Route for listing messages in a conversation
        Route::get('conversations/{id}/messages', 'index');

        // Route for storing new message
        Route::post('messages', 'store');

        // Route for deleting a message
        Route::delete('messages/{id}', 'destroy');
    });
});
