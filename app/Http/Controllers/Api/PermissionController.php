<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index() 
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }
}
