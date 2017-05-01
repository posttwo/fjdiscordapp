<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cah extends Model
{
    use Uuids;
    protected $table = 'cah';
    public $incrementing = false;
}
