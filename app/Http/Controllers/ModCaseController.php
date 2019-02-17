<?php

namespace App\Http\Controllers;

use Request;
use App\ModCase;
use App\ModAction;
use App\FJContent;
use App\ModCaseMessage;
use Auth;
use App\Jobs\SendMessageToUser;
use App\Jobs\UpdateFJComplaintStatus;
use Posttwo\FunnyJunk\User as FJUser;
use Illuminate\Support\Str;

class ModCaseController extends Controller
{
    public function index()
    {
        $x = ModCase::orderBy('id', 'desc')->paginate(100);
        return view('caseindex', ['list' => $x]);
    }

    public function getCase(ModCase $modCase)//($sourceType, $sourceId)
    {
        //$case = ModCase::with('messages')->where('source_type', $sourceType)->where('source_id', $sourceId)->first();
        $case = $modCase;
        $referenceType = $case->reference_type;
        if($referenceType == 'userprofile')
            $referenceType = 'user';
        $modActions = ModAction::where('reference_type', $referenceType)->where('reference_id', $case->reference_id)->get();
        //User profile actions not visible?
        
        $contentLive = null;
        if($case->reference_type == 'content')
            $contentLive = FJContent::where('id', $case->reference_id)->first();
        
        $previousFlags = ModAction::where('owner', $case->user_metadata['username'])
                        ->whereIn('category', array('flag', 'unflag', 'comment_flag', 'comment_unflag', 'cover_flag', 'cover_unflag', 'ban', 'avatar_flag', 'spam_comment_flag', 'voteban'))
                        ->get();
        
        $previousGays = ModAction::where('reference_type', 'user')->where('reference_id', $case->fj_user_id)->get();
        $previousFlags = $previousFlags->merge($previousGays);

        if(Request::get('json'))
        {
            return $case;
        }
        return view('case', ['case' => $case, 'modactions' => $modActions, 'contentLive' => $contentLive, 'previousFlags' => $previousFlags]);
        //$x = new ModCase;
        //return $x->bulkImport();
    }

    public function addCaseMessage(ModCase $modCase)
    {
        $message = new ModCaseMessage;
        $message->title = '';
        $message->description = Request::input('new_message');
        $message->internal = Request::input('internal', 0);
        $message->fj_user_id = Auth::user()->fjuser->fj_id;
        $modCase->messages()->save($message);
        
        if($message->internal == 0 && Request::input('fjstatus') == null)
        {
            $user = new FJUser;
            $user->username = $modCase->user_metadata['username'];
            $user->id = $modCase->fj_user_id;
            $topic = 'New Reply to Case#' . $modCase->id;
            $reply  = "\n [big][bold]====================[bold][big]\n";
            $reply .= $message->description;
            $reply .= "\n [big][bold]====================[bold][big]\n";
            $reply .= "[small]To reply to this please go to: " . route('moderator.case.viewbyuser', ['modCase' => $modCase, 'hash' => $modCase->access_key]) . '[small]';
            dispatch(new SendMessageToUser($user, $topic, $reply));

            $modCase->status = 2;
            $modCase->save();
        }

        if(Request::input('fjstatus') != null && $modCase->source_type == 'fj-user-complaint')
        {
            $modCase->status = 4;
            $modCase->save();
            $modCase->addInternalAnnotation('changeComplaintFJStatus', 'Changed FJ status to ' . Request::input('fjstatus'), Auth::user()->fjuser->fj_id);
            $reply = $message->description;
            $reply .= "\n [big][bold]====================[bold][big]\n";
            $reply .= "[small]To reply to this please go to: " . route('moderator.case.viewbyuser', ['modCase' => $modCase, 'hash' => $modCase->access_key]) . '[small]';
            dispatch(new UpdateFJComplaintStatus($modCase->source_id, Request::input('fjstatus'), $reply));
        }
        //setComplaintStatus
        return back();
    }

    public function resetAccessKey(ModCase $modCase)
    {
        $modCase->access_key = Str::random(40);
        $modCase->save();

        $modCase->addInternalAnnotation('resetAccessKey', "Resetting access key " . Auth::user()->id . ' ' . Auth::user()->fjuser->username, Auth::user()->fjuser->fj_id);
        return back();
    }

    public function toggleCaseLock(ModCase $modCase)
    {
        $modCase->locked = !$modCase->locked;
        $modCase->save();

        $modCase->addInternalAnnotation('toggleCaseLock', "Toggling case lock to " . $modCase->locked, Auth::user()->fjuser->fj_id);
        return back();
    }


    //######### USER FUNCTIONS ###################\\
    public function getCaseForUser(ModCase $modCase, $hash)
    {
        if($modCase->access_key != $hash)
            abort(403);

        return view('caseforuser', ['case' =>$modCase]);
    }

    public function addCaseMessageByUser(ModCase $modCase, $hash)
    {
        if($modCase->access_key != $hash)
            abort(403);
        
        if($modCase->locked == true)
            abort(403);
        
        $modCase->status = 3;
        $modCase->save();
        $message = new ModCaseMessage;
        $message->title = 'webReply';
        $message->description = Request::input('new_message');
        $message->internal = 0;
        $message->fj_user_id = $modCase->fj_user_id;
        $modCase->messages()->save($message);

        return back();
    }
}
