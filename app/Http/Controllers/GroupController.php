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
use Cache;

class GroupController extends Controller
{
    public function join(Role $role)
    {
        $this->checkIfUserCanJoinRole($role, true);
        dispatch(new AddUserDiscordGroup(Auth::user(), $role));
        event(new UserJoinedGroup(Auth::user(), $role));
        $this->dLog("joined group", $role);
        return ["message" => "Joined the group, it may take a minute before Discord updates"];
    }

    public function leave(Role $role)
    {
        dispatch(new RemoveUserDiscordGroup(Auth::user(), $role));
        event(new UserLeftGroup(Auth::user(), $role));
        Cache::tags('role_membership.'. Auth::user()->id . '.' . $role->slug)->flush();
        $this->dLog("left group", $role);
        return ["message" => "Left group, it may take a minute before Discord updates"];
    }

    public function slugJoin(Role $role)
    {
        $check = $this->checkIfUserCanJoinRole($role, false);
        if($check)
        {
            $this->dLog("joined group via slug", $role);
            dispatch(new AddUserDiscordGroup(Auth::user(), $role));
            event(new UserJoinedGroup(Auth::user(), $role));
        }else{
            $this->dLog("failed to join group via slug", $role);
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
                return 0;
        
        //check if user has permissions
        foreach($role->restrictions as $restriction)
        {
            if(Auth::user()->cannot($restriction->permission))
            {
                if($abort){
                    logger()->error("User tried to join group but didnt have permission.", ["id" => Auth::user()->id, "discord_id" => $role->discord_id, "role_name" => $role->name, "username" => Auth::user()->nickname]);
                    abort(403, 'Group Access Restricted: ' . $restriction->restriction->description . " | " . $restriction->permission);
                }
                else
                    return false;
            }
        }
        Cache::tags('role_membership.'. Auth::user()->id . '.' . $role->slug)->flush();
        return true;
    }

    protected function dLog($message, Role $role)
    {
        logger("User " . $message, ["id" => Auth::user()->id, "role_discord_id" => $role->discord_id, "role_name" => $role->name, "username" => Auth::user()->nickname]);
    }
}
