<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $incrementing = false;
    use Uuids;
    
    protected $fillable = [
        'name', 'description', 'discord_id', 'icon', 'slug'
    ];


    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
