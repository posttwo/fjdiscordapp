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
