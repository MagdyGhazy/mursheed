<?php

namespace App\Models;

use Laratrust\Models\Permission as PermissionModel;

class PermissionRole extends PermissionModel
{ 
    protected $table='permission_role';
    public $guarded = [];
}
