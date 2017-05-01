<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Rememberable\Rememberable;

class Role extends Model
{
    use Uuids;
    use Rememberable;
    
    public $incrementing = false;
    
    protected $fillable = [
        'name', 'description', 'discord_id', 'icon', 'slug'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function restrictions()
    {
        return $this->hasMany('App\RoleRestriction');
    }
}
