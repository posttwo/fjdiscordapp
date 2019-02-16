<?php

namespace App;

use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;

class FunnyjunkUser extends Model
{
    use Uuids;
    use Rememberable;
    public $incrementing = false;

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function modaction()
    {
        return $this->hasMany('App\ModAction', 'user_id', 'fj_id');
    }

    public function modActionsAgainst()
    {
        return $this->hasMany('App\ModAction', 'owner', 'username');
    }
    
    public function attributedContent()
    {
        return $this->hasMany('App\FJContent', 'attributedTo', 'fj_id');
    }
}
