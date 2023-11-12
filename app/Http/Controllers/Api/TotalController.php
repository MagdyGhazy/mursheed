<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\accommmodition;
use App\Models\Driver;
use App\Models\Guides;
use App\Models\Order;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Http\Request;

class TotalController extends Controller
{
    public function index(){
        $driver_pending = Driver::where('status',1)->count();
        $driver_approved = Driver::where('status',2)->count();
        $guides_pending = Guides::where('status',1)->count();
        $guides_approved = Guides::where('status',2)->count();
        $tourists = Tourist::count();
        $users = User::count();
        $accommmoditions = accommmodition::count();
        $order_pending = Order::where('status',1)->count();
        $order_approved = Order::where('status',1)->count();
        
        return response()->json([
            "message" => "Total successfully",
            "driver_pending" => $driver_pending,
            "driver_approved" => $driver_approved,
            "guides_pending" => $guides_pending,
            "guides_approved" => $guides_approved,
            "tourists" => $tourists,
            "users" =>  $users,
            "accommmoditions" => $accommmoditions,
            "order_pending" => $order_pending,
            "order_approved" => $order_approved,
        ]);
    }
}