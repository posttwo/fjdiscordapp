<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\AddUserDiscordGroup;
use App\Role;
use Auth;
use App\Events\UserJoinedGroup;

class GroupController extends Controller
{
    public function join($name)
    {
        $role = Role::where('name', $name)->firstOrFail();
        dispatch(new AddUserDiscordGroup(Auth::user(), $role->discord_id));
        event(new UserJoinedGroup(Auth::user(), $role));
    }
}
