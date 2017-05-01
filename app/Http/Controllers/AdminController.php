<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Spatie\Permission\Models\Permission;
use App\RoleRestriction;
use Cache;

class AdminController extends Controller
{
    public function viewRoles()
    {
        $roles = Role::get();
        return view('viewroles')->with('roles', $roles);
    }

    public function addRole(Request $request)
    {
        $x = new Role();
        $x->name = $request->input('name');
        $x->description = $request->input('description');
        $x->discord_id = $request->input('discord_id');
        $x->icon = $request->input('icon');
        $x->slug = $request->input('slug');
        $x->save();
        Cache::tags('roles')->flush();
        return $x;
    }

    public function addRestriction(Request $request)
    {
        $role = Role::find($request->role);
        foreach($request->permissions as $permission)
        {
            $perm = Permission::find($permission);
            $x = new RoleRestriction();
            $x->role_id = $role->id;
            $x->permission = $perm->name;
            $x->save();
        }
    }

    public function getListOfPermissions()
    {
        return Permission::get();
    }
}
