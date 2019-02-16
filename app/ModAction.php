<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;

class ModAction extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'id',
        'date',
        'info',
        'url',
        'category',
        'user_id',
        'reference_type',
        'reference_id',
        'is_public',
        'modifier',
        'fullsize_image',
        'thumbnail',
        'in_nsfw',
        'flagged',
        'fullsize_exist',
        'text',
        'owner',
        'title',
        'role_name'
    ];

    protected $dates = ['date'];
    protected $attributes = [
        'text' => ''
    ];

    public function addNote($category, $info)
    {
        $this->notes()->create([
            'category' => $category,
            'info' => $info,
            'user_id' => Auth::user()->id ?? null
        ]);
    }
    public function notes()
    {
        return $this->hasMany('App\ModActionNote');
    }

    public function user()
    {
        return $this->belongsTo('App\FunnyjunkUser', 'user_id', 'fj_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\FunnyjunkUser', 'owner', 'username');
    }

    public function content()
    {
        if($this->reference_type != 'content')
            return false;
        return $this->belongsTo('App\FJContent', 'reference_id', 'id');
    }
    
}
