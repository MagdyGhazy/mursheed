<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class EventController extends Controller
{
    public function index(Request $request){

        $NotificationService = new NotificationService();
        return $NotificationService->notification($request);
    }
}