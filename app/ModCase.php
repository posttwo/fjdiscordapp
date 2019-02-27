<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\User as FJUser;
use Illuminate\Support\Facades\DB;
use App\ModCaseMessage;
use Illuminate\Support\Facades\Hash;

class ModCase extends Model
{

    private $fj;

    function __construct() {
        parent::__construct();
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }

    protected $casts = [
        'user_metadata' => 'array',
        'content_metadata' => 'array'
    ];

    public function bulkImport()
    {
        $data = collect($this->fj->getComplaints());
        //return $data;
        //get current max ID
        $largestSourceID = ModCase::where('source_type', 'fj-user-complaint')->max('source_id');
        DB::beginTransaction();
        foreach($data as $complaint)
        {
            if($complaint->id <= $largestSourceID)
            {
                echo("Skipping: " . $complaint->id . "\n");
                continue;
            }
            echo($complaint->id . "\n");
            //if($complaint->id == 3835){
            $case = new ModCase;
            $case->source_type = 'fj-user-complaint';
            $case->source_id   = $complaint->id;
            $case->fj_user_id  = $complaint->id_user;
            $case->save();
            
            $caseUserMessage = new ModCaseMessage;
            $caseUserMessage->title = '';
            $caseUserMessage->description = $complaint->complaint_description . $complaint->solution . "\n" . $complaint->link;
            $caseUserMessage->fj_user_id = $complaint->id_user;
            $caseUserMessage->internal = false;
            $case->messages()->save($caseUserMessage);

            $case->resolveLink($complaint->link); //@TODO FIX THIS

            $case->routeCase();
            $case->getUserData();
            $case->routeSeverity();
            
            $case->status = 1;
            $case->save();
            //}
            //$case->save();
        }
        DB::commit();
        return $data;
    }

    protected function resolveLink($link)
    {
        $commentIdOrEmpty = substr($link, strrpos($link, '/') + 1);
        $onPageCommentId = (int)substr($commentIdOrEmpty, 0, strpos($commentIdOrEmpty, "#"));
        if($onPageCommentId == '') $onPageCommentId = $commentIdOrEmpty;
        if(is_numeric($onPageCommentId))
        {
            $page = $this->fj->getByUrl($link);
            if(!isset($page->success) && $page->comments[0]->id != 'empty')
            {
                $comments = $page->comments;
                foreach($comments as $comment)
                {
                    if($comment->number == $onPageCommentId)
                    {
                        $this->reference_type = 'comment';
                        $this->reference_id   = $comment->id;
                        $this->reference_url  = 'https://funnyjunk.com' . $page->base_url . $comment->number;
                        $this->content_metadata = $page;

                        $this->addInternalAnnotation('resolveLink', "Resolved user link {$link} to COMMENT {$onPageCommentId} on https://funnyjunk.com{$page->base_url}");
                        break;
                    } 
                }
            } else {
                $this->addInternalAnnotation('resolveLink', "Comment assumed based on {$link} but could not resolve (NOT IN DB)!");
            }
        } else {
            //Treat is as content
            $page = $this->fj->getByUrl($link);
            $this->content_metadata = $page;
            if(!isset($page->success))
            {
                if(isset($this->content_metadata['is_profile']))
                {
                    //Is user profile
                    $this->reference_type = 'user';
                    $this->reference_id   = $this->content_metadata['userId'];
                    $this->reference_url  = 'https://funnyjunk.com/u/' . $this->content_metadata['username'];
                    $this->addInternalAnnotation('resolveLink', "Resolved user link {$link} to USERPROFILE {$this->reference_url}");
                } else {
                    //is normal content
                    $this->reference_type = 'content';
                    $this->reference_id = $this->content_metadata['id'];
                    $this->reference_url = 'https://funnyjunk.com' . $this->content_metadata['base_url'];
                    $this->addInternalAnnotation('resolveLink', "Resolved user link {$link} to CONTENT {$this->reference_url}");

                }
            } else {
                $this->addInternalAnnotation('resolveLink', "I have no clue what this retard is doing {$link}");
            }

        }
        return $this;
    }

    protected function routeCase()
    {
        $queues = [
            'user-complaint-nsfw' => 0,
            'user-complaint-sfw'  => 5
        ];

        //If related content is mature
        if(isset($this->content_metadata['is_mature']) 
            && $this->content_metadata['is_mature'] == 1
            && $this->content_metadata['flagged'] == 1)
            $queues['user-complaint-nsfw'] += 10;

        $max = max($queues);
        $key = array_search($max, $queues);
        $this->queue = $key;
        return $this;
    }

    protected function getUserData()
    {
        $u = new FJUser;
        $u->id = $this->fj_user_id;
        $u->getUsername();
        $u->getUserInfo();
        $this->user_metadata = $u; //banned_by
        return $this;
    }

    protected function routeSeverity()
    {
        $this->severity = 4;
        //if user currently banned, SEV3
        if(isset($this->user_metadata['banned_by']) && $this->user_metadata['banned_by'] != null)
            $this->severity = 3;
        
        return $this;
    }

    public function messages(){
        return $this->hasMany('App\ModCaseMessage');
    }

    public function addInternalAnnotation($topic, $message, $user = null)
    {
        $this->messages()->create([
            'title' => $topic,
            'description' => $message,
            'internal' => true,
            'fj_user_id' => $user
        ]);
    }

   
}
