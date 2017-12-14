<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModHelp extends Model
{
    use Uuids;
    protected $table = 'mod_help_queries';
    public $incrementing = false;
}
