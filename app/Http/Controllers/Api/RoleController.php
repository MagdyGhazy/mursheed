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
       public function index() {
        $data = Role::with('permission')->get();

        return response()->json([
            "data" => $data,
        ], 200);
    }


    public function store(Request $request) {
     
        $roles = Role::create([
           "name"=> $request->name,
           'guard_name'=>"api"
        ]);
    foreach ($request->permission_id as $value) 
        {
        RolePermission::create(
               [
                'role_id'=>$roles->id,
                'permission_id'=> $value
               ]);
        }
        
   
        return response()->json([
            "data" => $roles,
           
        ], 200);
    }

    public function show($id) 
    {
        $role = Role::where('id',$id)->with('permission')->get();
        return response()->json($role);
    }
    public function permissionsIndex() {
        $data = Permission::get();

        return response()->json([
            "data" => $data,
        ], 200);       }
    public function getallpermaission( ) {
       
   

    }

    public function permissions_create(Request $request) {
        // $permission = Permission::create([
        //     'name' => $request->name,
        //     'display_name' => $request->display_name,
        //     'description' => $request->description
        // ]);

        // PermissionRole::create([
        //     'role_id'=> $request->role_id,
        //     'permission_id' => $permission->id
        // ]);

        // return response()->json([
        //     "data"=>$permission
        // ], 200); 
            

        }

}
