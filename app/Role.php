<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $incrementing = false;
    use Uuids;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
