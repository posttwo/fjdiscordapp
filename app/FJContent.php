<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FJContent extends Model
{
    public function modaction()
    {
        return $this->hasMany('App\ModAction', 'reference_id', 'id')->where('reference_type', 'content');
    }

    public function user()
    {
        return $this->belongsTo('App\FunnyjunkUser', 'attributedTo', 'fj_id');
    }
}
