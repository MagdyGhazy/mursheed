<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Http\Requests\StoreRoleRequest;
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


    public function store(StoreRoleRequest $request) {
     
        
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

        public function updaterole(Request $request,$id)
    {
      
        $role = Role::find($id);
        $role->update([
            "name" => $request->name,
            'guard_name' => "api"
        ]);
      //  $role = Role::where('id', $id)->with('permission')->get();
        $permaion = $role->permission;
    //  return $permaion;

      
        foreach ($permaion as $value) {
            foreach ($request->permission_id as $data) {
            
            
                $value->update(
                [
                    'role_id' => $role->id,
                    'permission_id' => $data,
                ]
            );
        }
        }

    }
   

    public function destroy($id)
    {
     
        $role = Role::where('id', $id)->with('permission')->delete();
        
        return response()->json("sucsses");
    }

}
