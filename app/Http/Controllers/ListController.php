<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cah;

class ListController extends Controller
{
    public function listCahCards()
    {
        return view('cahcards')->with('cards', Cah::all());
    }
}
