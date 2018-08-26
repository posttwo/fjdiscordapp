<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModActionNote extends Model
{
    protected $fillable = ['user_id', 'info', 'category'];
    
    public function action()
    {
        return $this->belongsTo('App\ModActionNote');
    }
}
