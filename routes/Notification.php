<?php

use Illuminate\Support\Facades\Route;


Route::controller(\App\Http\Controllers\NotificationController::class)->group(function () {
    //get all Unread notifications
    Route::get('/GetUnreadNotifications', 'GetUnreadNotifications');

    //get all notifications
    Route::get('/GetAllNotifications', 'GetAllNotifications');

    //Mark All Notifications
    Route::get('/Notifications/markAsRead', 'MarkAllNotifications');


});