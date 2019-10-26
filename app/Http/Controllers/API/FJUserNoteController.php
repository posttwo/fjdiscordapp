<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Auth;
use App\Role;
use App\FunnyjunkUser;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\User;
use Cache;
use App\UserNote;
use Posttwo\FunnyJunk\User as FJUser;

class FJUserNoteController extends \App\Http\Controllers\Controller
{

    public function getUserNotes($fjUserId){
        $response = Cache::remember('fjapi.getUserNotes.' . $fjUserId, 60*60, function() use($fjUserId){
            $notes = UserNote::where('fj_id', $fjUserId)->with('createdBy.fjuser')->orderBy('highlight', 'desc')->orderBy('id', 'asc')->get();
            return $notes;
        });

        //DEBUG
        //Cache::forget('fjapi.getUserNotes.' . $fjUserId);
        //ENDDEBUG
        
        return $response;
    }
    
    public function addUserNote($fjUserId, Request $request){
        //return $request->all();
        $request->validate([
            'description' => 'required|string',
            'color' => 'starts_with:#|nullable|size:7'
        ]);

        $x = new UserNote;
        $x->fj_id = $fjUserId;
        $x->created_by_id = Auth::user()->id;
        $x->description = $request->input('description');
        $x->color = $request->input('color');
        $x->save();
            
        Cache::forget('fjapi.getUserNotes.' . $fjUserId);
        return $x;
    }

    public function setUserNoteHighlight($noteId, Request $request){
        $x = UserNote::findOrFail($noteId);
        $x->highlight = $request->input('highlight');
        $x->save();
        Cache::forget('fjapi.getUserNotes.' . $x->fj_id);
        return $x;
    }

    public function removeUserNote($noteId){
        $x = UserNote::findOrFail($noteId);
        $x->delete();
        Cache::forget('fjapi.getUserNotes.' . $x->fj_id);
        return $x;
    }

    public function importLegacyNotes($fjUserId, $username)
    {
        $data = array('token' => 'POOOPYDOOFACE'); //@TODO REMOVE
        $options = array(
            'http' => array(
              'ignore_errors' => true,
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method' => 'POST',
              'content' => http_build_query($data)
              )
          );
        $context  = stream_context_create($options);
        $t = file_get_contents('https://fjmod.posttwo.pt/notes/get/' . $username, false, $context);
        
        $notes = collect(json_decode($t));
        $fj = new FunnyJunk();
        $fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));

        foreach($notes as $note)
        {
            logger("Importing UserNote", ['note' => $note]);
            $x = new Usernote;
            $x->fj_id = $fjUserId;
            $x->created_by_id = '019a97e0-f820-11e9-b59b-979402cd9944'; //hardcoded fjmodbot default
            $x->description = ' ';
            $x->color = '#FFFFFF';
            $x->description = $note->message;

            //lets try resolve the posters username
            $user = new FJUser();
            $user->set(array('username' => $note->added_by));
            $user->getId();
            $resolve = FunnyjunkUser::where('fj_id', $user->id)->first();
            
            if($resolve != null && $resolve->user != null){
                $x->created_by_id = $resolve->user->id;
            } else {
                $x->description .= ' @NOTE BY: ' . $note->added_by;
            }

            $x->color = $note->color;
            $x->created_at = $note->created_at;
            $x->updated_at = $note->updated_at;
            
            $x->save();
            logger("Note imported", ['newnote' => $x]);
        }


        
    }

}