<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserNote extends Model
{
    use SoftDeletes;

    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by_id', 'id');
    }

    public function fjuser()
    {
        return $this->belongsTo('App\FunnyjunkUser', 'fj_id', 'fj_id');
    }
}
