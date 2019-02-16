<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModCase;
use App\ModAction;
use App\FJContent;

class ModCaseController extends Controller
{
    public function getCase($sourceType, $sourceId)
    {
        $case = ModCase::with('messages')->where('source_type', $sourceType)->where('source_id', $sourceId)->first();
        $modActions = ModAction::where('reference_type', $case->reference_type)->where('reference_id', $case->reference_id)->get();
        
        $contentLive = null;
        if($case->reference_type == 'content')
            $contentLive = FJContent::where('id', $case->reference_id)->first();
        

        $previousFlags = ModAction::where('owner', $case->user_metadata['username'])->where('category', 'flag')->get();
        return view('case', ['case' => $case, 'modactions' => $modActions, 'contentLive' => $contentLive, 'previousFlags' => $previousFlags]);
        return $case;
        //$x = new ModCase;
        //return $x->bulkImport();
    }
}
