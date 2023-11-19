<?php


Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'tickets'], function () {

        Route::get('/', [\App\Http\Controllers\Api\Tickets\TicketController::class, 'index'])->name('tickets');
        Route::post('/store', [\App\Http\Controllers\Api\Tickets\TicketController::class, 'store'])->name('ticket.store');
        Route::get('/show/{ticket_id}', [\App\Http\Controllers\Api\Tickets\TicketController::class, 'show'])->name('ticket.show');
        Route::get('/userTickets/{user_id}', [\App\Http\Controllers\Api\Tickets\TicketController::class, 'userTickets'])->name('ticket.userTickets');
        Route::post('/addReplay/{ticket_id}', [\App\Http\Controllers\Api\Tickets\TicketController::class, 'addReplay'])->name('ticket.storeReplay');
        Route::post('/addMessage/{ticket_id}', [\App\Http\Controllers\Api\Tickets\TicketController::class, 'addMessage'])->name('ticket.storeMessage');
        Route::get('/inActiveTicket/{ticket_id}', [\App\Http\Controllers\Api\Tickets\TicketController::class, 'inActiveTicket'])->name('ticket.inActiveTicket');
    });
});
