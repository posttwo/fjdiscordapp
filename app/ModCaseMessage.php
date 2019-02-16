<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModCaseMessage extends Model
{
    protected $fillable = ['title', 'description', 'internal'];
    public function case()
    {
        return $this->belongsTo('App\ModCase');
    }

    public function fjuser()
    {
        return $this->belongsTo('App\FunnyjunkUser', 'fj_user_id', 'fj_id');
    }
}
