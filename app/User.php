<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use Uuids;


    /**
    * Indicates if the IDs are auto-incrementing.
    *
    * @var bool
    */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'nickname', 'token', 'refreshToken', 'avatar', 'discord_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token', 'refreshToken', 'email',
    ];

    public function fjuser()
    {
        return $this->hasOne('App\FunnyjunkUser');
    }

    public function verificationTokens()
    {
        return $this->hasMany('App\Token');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'user_role')->withTimestamps();
    }
}
