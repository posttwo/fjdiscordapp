<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;

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
        return $x;
    }
}
