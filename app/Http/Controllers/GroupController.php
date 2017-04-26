<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\AddUserDiscordGroup;
use App\Jobs\RemoveUserDiscordGroup;
use App\Role;
use Auth;
use App\Events\UserJoinedGroup;
use App\Events\UserLeftGroup;

class GroupController extends Controller
{
    public function join($name)
    {
        $role = Role::where('name', $name)->firstOrFail();
        $rolesUserHas = Auth::user()->roles()->where('id', $role->id)->first();
        if($rolesUserHas !== null)
            abort(400);
        dispatch(new AddUserDiscordGroup(Auth::user(), $role->discord_id));
        event(new UserJoinedGroup(Auth::user(), $role));
        return ["message" => "Joined the group, it may take a minute before Discord updates"];
    }

    public function leave($name)
    {
        $role = Role::where('name', $name)->firstOrFail();
        dispatch(new AddUserDiscordGroup(Auth::user(), $role));
        event(new UserLeftGroup(Auth::user(), $role));
        return ["message" => "Left group, it may take a minute before Discord updates"];
    }

    public function slugJoin($slug)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        dispatch(new RemoveUserDiscordGroup(Auth::user(), $role));
        event(new UserJoinedGroup(Auth::user(), $role));
        return view('joined')->with('role', $role);
    }
}
