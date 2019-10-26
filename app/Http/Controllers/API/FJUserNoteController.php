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

}