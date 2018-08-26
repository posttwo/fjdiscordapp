<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModAction;
use App\ModActionNote;
use App\FJContent;
use App\FunnyjunkUser;

use App\Exceptions\ModActionParseErrorException;
use Storage;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModActionController extends Controller
{

    public function getContentAttributedToUser($fjusername, $from = null, $to = null)
    {
        if($fjusername == "self")
        {
            $fjusername = Auth::user()->fjuser->username;
        }
        $fjuser = FunnyjunkUser::where('username', $fjusername)->firstOrFail();
        if($from == null && $to == null)
        {
            $from = Carbon::now()->subDay();
            $to = Carbon::now();
        }

        $contents = FJContent::with('modaction')
                    ->with('modaction.notes')
                    ->with('user')
                    ->with('modaction.user')
                    ->where('attributedTo', $fjuser->fj_id)
                    ->whereBetween('created_at', [$from, $to])
                    ->get();
        
        $meta['from'] = $from ?? "NO RANGE";
        $meta['to'] = $to ?? "SHOWING 24";
        $meta['user'] = $fjuser->username;
        $meta['count'] = $contents->count();
        return view('moderator.modaction')->with('contents', $contents)->with('meta', $meta);
    }

    public function getContentWithNoAttribution()
    {
        $contents = FJContent::with('modaction')
                    ->with('modaction.notes')
                    ->with('user')
                    ->with('modaction.user')
                    ->where('attributedTo', null)
                    ->get();

        $meta['from'] = $from ?? "NO RANGE";
        $meta['to'] = $to ?? "SHOWING ALL";
        $meta['user'] = "No Attribution";
        $meta['count'] = $contents->count();
        return view('moderator.modaction')->with('contents', $contents)->with('meta', $meta);

    }

    public function attributeContent(FJContent $content, $userid)
    {
        $content->attributedTo = $userid;
        $content->save();
        $content->modaction->first()->addNote('content_attribute', Auth::user()->fjuser->username . ' attributed content to ' . $userid);
    }

    public function parseJson()
    {
        $input = Storage::disk('local')->get('testing.json');
        $input = json_decode($input, true);
        $input = collect($input)->map(function($row){
            return collect($row);
        });
        $input = $input->reverse();

        //need get latest and discard any old ones to optimise this crap lol
        DB::transaction(function () use($input){
            foreach($input->chunk(1) as $chunk)
            {  
                $chunk = $chunk->first();
                //if($chunk->get('reference_id') != 6713502)
                    //break;

                $action = ModAction::create($chunk->toArray());
                
                //Insert $chunk into mod table thing
                if($chunk->get('reference_type') == 'content')
                {
                    //ACTION RELATING TO CONTENT
                    try{
                        $content = FJContent::findOrFail($chunk->get('reference_id'));
                    } catch(ModelNotFoundException $e)
                    {   
                        $content = new FJContent;
                    }
                    
                    if($content->exists == false)
                        $content->attributedTo = $chunk->get('user_id');
                    else{
                        if($content->attributedTo != $chunk->get('user_id')){
                            $content->attributedTo = null;
                            $action->addNote('fjmeme_parser_message', 'Attribution removed due to moderator conflict');
                        }
                    }
                        
                    $content->id = $chunk->get('reference_id');
                    $content->url = $chunk->get('url');
                    $content->fullsize_image = $chunk->get('fullsize_image');
                    $content->thumbnail = $chunk->get('thumbnail');
                    $content->in_nsfw = $chunk->get('in_nsfw');
                    $content->flagged = $chunk->get('flagged');
                    $content->owner = $chunk->get('owner');
                    $content->title = $chunk->get('title');
                    $content->flagged = $chunk->get('flagged');

                    switch($chunk->get('category')){
                        case 'pc_level':
                            $level = $this->getLevelFromString($chunk->get('info'));
                            $content->rating_pc = $level;
                            break;
                        case 'skin_level':
                            $level = $this->getLevelFromString($chunk->get('info'));
                            $content->rating_skin = $level;
                            break;
                        case 'category': //rating_category
                            $category = $this->getCategoryFromString($chunk->get('info'));
                            $content->rating_category = $category;
                            break;
                        case 'flag':
                            $flag = $this->getLastWordFromString($chunk->get('info'));
                            $content->flagged_as = $flag;
                            break;
                        case 'unflag':
                            $content->flagged_as = null;
                            $content->hasIssue = true;
                            $action->addNote('fjmeme_parser_message', 'Issue raised due to content unflag');
                            break;
                    }
                    $content->save();
                }
            }
        });


        dd("DONE");
    }
    
    protected function getLevelFromString($string)
    {
        return (int) filter_var(($string), FILTER_SANITIZE_NUMBER_INT);
    }

    protected function getCategoryFromString($string)
    {
        if(preg_match('/"([^"]+)"/', $string, $result))
            return $result[1];
        else{
            throw new ModActionParseErrorException("CATEGORY Parsing Error " . $string);
        }
    }

    protected function getLastWordFromString($string)
    {
        $pieces = explode(' ', $string);
        $last_word = array_pop($pieces);
        return $last_word;
    }
}
