<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use Uuids;
    public $incrementing = false;

    public function generateToken()
    {
        $this->token = str_random(40);
        return $this;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
