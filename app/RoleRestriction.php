<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleRestriction extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function restriction()
    {
        return $this->hasOne('Spatie\Permission\Models\Permission', 'name', 'permission');
    }
}
