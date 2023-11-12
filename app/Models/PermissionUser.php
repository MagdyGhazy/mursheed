<?php

namespace App\Models;

use Laratrust\Models\Permission as PermissionModel;

class PermissionUser extends PermissionModel
{ 
    protected $table='permission_user';
    public $guarded = [];
}
