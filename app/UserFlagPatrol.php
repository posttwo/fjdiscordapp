<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFlagPatrol extends Model
{

    public $fillable = ['cid', 'type'];
    
    public function userFlags()
    {
        return $this->hasMany('App\UserFlag', 'cid', 'cid')->where('type', $this->type);
    }

    public function incrementFlagCounter()
    {
        $this->increment('flags');
    }

    public function markAsPatrolled($user, $flagged)
    {
        $this->flagged = $flagged;
        $this->patrolled_by = $user;
        $this->save();
    }

    public function patroller()
    {
        return $this->belongsTo('App\FunnyjunkUser', 'patrolled_by', 'fj_id');
    }
}
