<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FunnyjunkUser extends Model
{
    use Uuids;
    public $incrementing = false;

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function modaction()
    {
        return $this->hasMany('App\ModAction', 'id', 'fj_id');
    }

    public function attributedContent()
    {
        return $this->hasMany('App\FJContent', 'attributedTo', 'fj_id');
    }
}
