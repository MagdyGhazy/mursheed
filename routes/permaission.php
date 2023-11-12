<?php

use App\Models\Role;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\MobileApi\SocialiteController;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'permaissions'], function () {
       Route::get('all/permission',[RoleController::class,'permissionsIndex']);
       
       Route::apiResource('roles', RoleController::class);
     

    });
});

Route::get('auth/redirect/{provider}',[SocialiteController::class,'redirect']);
Route::get('auth/clalback/{provider}',[SocialiteController::class,'callback']);
