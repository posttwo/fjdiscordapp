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
        return view('home')->with('roles', $roles);
    }
}
