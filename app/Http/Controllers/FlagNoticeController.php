<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FlagNotice;

class FlagNoticeController extends Controller
{
    public function index()
    {
        $notices = FlagNotice::paginate(100);
        return view('moderator.flagnotice')->with('notices', $notices);
    }
}
/*
"id" => 3
    "user_id" => "0bac7680-86b6-11e8-82b2-0ba149f1d254"
    "context" => "imageId"
    "value" => "d25e45dcf26cd29eb07a06c77b7fe59a.jpg"
    "text" => "goodImage"
    "revoked" => 0
    "deleted_at" => null
    "created_at" => "2018-07-19 20:29:32"
    "updated_at" => "2018-07-19 20:29:32"
    */