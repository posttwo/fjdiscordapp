<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LolPlayer extends Model
{
    use Uuids;
    protected $table = 'lol_players';
    public $incrementing = false;
}
