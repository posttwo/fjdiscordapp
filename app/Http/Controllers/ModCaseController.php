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

        $referenceType = $case->reference_type;
        if($referenceType == 'userprofile')
            $referenceType = 'user';
        $modActions = ModAction::where('reference_type', $referenceType)->where('reference_id', $case->reference_id)->get();
        //User profile actions not visible?
        
        $contentLive = null;
        if($case->reference_type == 'content')
            $contentLive = FJContent::where('id', $case->reference_id)->first();
        
        $previousFlags = ModAction::where('owner', $case->user_metadata['username'])->whereIn('category', array('flag', 'unflag', 'comment_flag', 'comment_unflag', 'cover_flag', 'cover_unflag', 'ban', 'avatar_flag', 'spam_comment_flag', 'voteban'))->get();
        return view('case', ['case' => $case, 'modactions' => $modActions, 'contentLive' => $contentLive, 'previousFlags' => $previousFlags]);
        return $case;
        //$x = new ModCase;
        //return $x->bulkImport();
    }
}
