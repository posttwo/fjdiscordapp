<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\ModHelp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use Posttwo\FunnyJunk\FunnyJunk;

class DiscordHelpController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }

    public function sendHelpRequest(Request $request)
    {
	if(Auth::user()->cannot('mod.isAMod'))
                abort(403);
        //find if already exists
        try{
            $content = ModHelp::where('image_id', $request->input('imageId'))->where('comment_id', $request->input('commentId'))->firstOrFail();
            return response()->json('Already Asked', 406);
        } 
        catch (ModelNotFoundException $e)
        {
            $content = new ModHelp;
            $content->content_id = $request->input('contentId');
            $content->content_url = $request->input('contentUrl');
            $content->image_id = $request->input('imageId');
            $content->image_url = $request->input('imageUrl');
            $content->comment_id = $request->input('commentId', null);
            $content->save();
            //post it
            $fj = $this->fj->getByUrl($content->content_url);
            //return response()->json($fj);
            $slack = new \App\Slack;
            $slack->target = 'mod-help';
            $slack->username =   Auth::user()->nickname;
            $slack->avatar   =   Auth::user()->avatar;
            $slack->title    = $fj->title;
            $slack->text     = 'Content: https://funnyjunk.com' . $fj->base_url . '#' . $content->image_id;
            $slack->text    .= "\nImage: " . $content->image_url;
            $slack->embedFields = [ 'Posted By' => $fj->username, 
                                    'Date: ' => $fj->date];
            $slack->footer = Auth::user()->fjuser->username;
            $slack->image_url = $content->image_url;
            if($fj->is_mature == 1){
                $slack->color = 'error';
                $slack->text .= "\n :warning: **NSFW** :warning:";
            }

            \Notification::send($slack, new \App\Notifications\ModNotify(null));
            return response()->json('OK', 200);
        }
        //else add

    }
}
