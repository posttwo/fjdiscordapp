<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModCase;

class ModCaseController extends Controller
{
    public function test()
    {
        $x = new ModCase;
        return $x->bulkImport();
    }
}
