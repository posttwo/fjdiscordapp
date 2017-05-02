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
}
