<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\PermissionUser;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{

  
         function __construct()
         {
              $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
              $this->middleware('permission:role-create', ['only' => ['create','store']]);
              $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
              $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $data = Role::with('permission')->get();

        return response()->json([
            "data" => $data,
        ], 200);
    }


    public function store(Request $request)
    {
        $roles = Role::create([
            "name" => $request->name,
            'guard_name' => "api"
        ]);
        foreach ($request->permission_id as $value) {
            RolePermission::create(
                [
                    'role_id' => $roles->id,
                    'permission_id' => $value
                ]
            );
        }


        return response()->json([
            "data" => $roles,

        ], 200);
    }

    public function show($id)
    {
        $role = Role::where('id', $id)->with('permission')->get();
        return response()->json($role);
    }
    public function permissionsIndex()
    {
        $data = Permission::get();

        return response()->json([
            "data" => $data,
        ], 200);
    }
    public function updaterole(Request $request,$id)
    {
      
        $role = Role::find($id);
        $role->update([
            "name" => $request->name,
            'guard_name' => "api"
        ]);
     
        $permaion = $role->permission;
        foreach ($permaion as $value) {
           $data=RolePermission::find($value->id);
           $data->delete();
        }
        foreach ($request->permission_id as $value) {
            RolePermission::create(
                [
                    'role_id' => $role->id,
                    'permission_id' => $value
                ]
            );
        }
    }
   

    public function destroy($id)
    {
     
        $role = Role::where('id', $id)->with('permission')->delete();
        
        return response()->json("sucsses");
    }
}
