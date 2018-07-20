<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlagNotice extends Model
{
    use SoftDeletes;
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function poster()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
