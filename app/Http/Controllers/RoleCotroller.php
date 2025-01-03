<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleCotroller extends Controller
{
    public function insertRole(Request $request)
    {

        $role = new Role();
        $role->name = $request->name;
        $role->permissions = $request->permissions;
        $role->save();
        return new RoleResource($role);
    }
    public function getRoles(Request $request)
    {
        $roles = Role::all();
        return RoleResource::collection($roles);
    }
}
