<?php

namespace App\Models;

use Laratrust\Models\Role as RoleModel;

class Role extends RoleModel
{
    public $guarded = [];
   
    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
    
}
