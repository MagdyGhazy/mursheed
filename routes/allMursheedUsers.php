<?php


use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    //All Mursheed Users
    Route::get('AllUsers', '\App\Http\Controllers\Api\AllMursheedUsersController@allMursheedUsers');

});