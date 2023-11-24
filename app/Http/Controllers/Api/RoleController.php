<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\PermissionRole;
use App\Models\PermissionUser;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;

class RoleController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
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
    // get one role 
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
    public function updaterole(Request $request, $id)
    {
        //get Role 
        $role = Role::find($id);
        $role->update([
            "name" => $request->name,
            'guard_name' => "api"
        ]);
        // remove permissions
        $permissions = DB::table('role_permissions')->where('role_id', $id)->delete();
        //updata new permissions 
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
        // delete role permissions
        $role = Role::where('id', $id)->with('permission')->delete();

        return response()->json("sucsses");
    }
}
