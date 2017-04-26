<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Role;

class HomeController extends Controller
{
    public function view()
    {
        $roles = Role::get();
        $userRoles = Auth::user()->roles()->get();
        $rolesUserDoesntHave = $roles->diff($userRoles);
        return view('home')->with('rolesUserDoesntHave', $rolesUserDoesntHave)->with('rolesUserHas', $userRoles);
    }
}
