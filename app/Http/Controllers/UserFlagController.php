<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserFlag;

class UserFlagController extends Controller
{
    public function index()
    {
        //$x = new UserFlag;
        //$x->bulkImport();
        $contentFlags = UserFlag::paginate(200);
        //return $contentFlags;
        return view('moderator.userflag')->with(['content' => $contentFlags]);
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
}
