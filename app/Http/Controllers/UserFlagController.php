<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserFlag;
use App\UserFlagPatrol;
use Auth;

class UserFlagController extends Controller
{
    public function index()
    {
        $patrol = UserFlagPatrol::where('flags', '!=', '1')->orderBy('id', 'desc')->with('patroller')->paginate(200);
        $contentFlags = UserFlag::orderBy('id', 'desc')->paginate(200);
        //eturn $patrol;
        return view('moderator.userflag')->with(['content' => $contentFlags, 'patrol' => $patrol]);
    }

    public function getByCid($type, $cid)
    {
        $x = UserFlag::where('type', $type)->where('cid', $cid)->get();
        return $x;
    }

    public function getByUserId($id)
    {
        $x = UserFlag::where('user_id', $id)->get();
        return $x;
    }

    public function markAsPatrolled($id)
    {
        $x = UserFlagPatrol::findOrFail($id);
        $x->markAsPatrolled(Auth::user()->fjuser->fj_id, false);
        return $x;
    }
}
