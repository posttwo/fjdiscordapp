<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\AddUserDiscordGroup;
use App\Jobs\RemoveUserDiscordGroup;
use App\Jobs\JoinUserDiscord;
use App\Role;
use Auth;
use App\Events\UserJoinedGroup;
use App\Events\UserLeftGroup;
use Spatie\Permission\Models\Permission;

class GroupController extends Controller
{
    public function join($name)
    {
        $role = Role::where('name', $name)->firstOrFail();
        $this->checkIfUserCanJoinRole($role, true);
        dispatch(new AddUserDiscordGroup(Auth::user(), $role->discord_id));
        event(new UserJoinedGroup(Auth::user(), $role));
        logger("User joined group", ["id" => Auth::user()->id, "role_discord_id" => $role->discord_id]);
        return ["message" => "Joined the group, it may take a minute before Discord updates"];
    }

    public function leave($name)
    {
        $role = Role::where('name', $name)->firstOrFail();
        dispatch(new RemoveUserDiscordGroup(Auth::user(), $role));
        event(new UserLeftGroup(Auth::user(), $role));
        logger("User left up", ["id" => Auth::user()->id, "role_discord_id" => $role->discord_id]);
        return ["message" => "Left group, it may take a minute before Discord updates"];
    }

    public function slugJoin($slug)
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        $check = $this->checkIfUserCanJoinRole($role, false);
        if($check)
        {
            logger("User joined group via slug", ["id" => Auth::user()->id, "role_discord_id" => $role->discord_id]);
            dispatch(new AddUserDiscordGroup(Auth::user(), $role->discord_id));
            event(new UserJoinedGroup(Auth::user(), $role));
        }else{
            logger("User failed to join group via slug", ["id" => Auth::user()->id, "role_discord_id" => $role->discord_id]);
        }
        return view('joined')->with('role', $role)->with('check', $check);
    }

    protected function checkIfUserCanJoinRole(Role $role, $abort)
    {
        $rolesUserHas = Auth::user()->roles()->where('id', $role->id)->first();
        if($rolesUserHas !== null)
            if($abort)
                abort(400);
            else
                return false;
        
        //check if user has permissions
        foreach($role->restrictions as $restriction)
        {
            if(Auth::user()->cannot($restriction->permission))
            {
                if($abort){
                    logger()->error("User tried to join group but didnt have permission.", ["id" => Auth::user()->id, "role_discord_id" => $role->discord_id]);
                    abort(403, 'Group Access Restricted: ' . $restriction->restriction->description . " | " . $restriction->permission);
                }
                else
                    return false;
            }
        }
        return true;
    }
}
