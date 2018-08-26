<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModActionNote extends Model
{
    use \Awobaz\Compoships\Compoships;

    protected $fillable = ['user_id', 'info', 'category'];
    
    public function action()
    {
        return $this->belongsTo('App\ModActionNote');
    }
}
