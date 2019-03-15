<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Posttwo\FunnyJunk\FunnyJunk;
use DB;

class UserFlag extends Model
{
    private $fj;
    public $timestamps = false;

    function __construct() {
        parent::__construct();
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }

    public function bulkImport()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
        $largestSourceID = UserFlag::max('id');
        $flags = $this->fj->getUserFlags()->flags;

        DB::beginTransaction();
        foreach($flags as $flag)
        {
            if($flag->id <= $largestSourceID)
            {
                echo("Skipping: " . $flag->id . "\n");
                continue;
            }
            echo($flag->id . "\n");
            $userFlag = new UserFlag;
            $userFlag->id = $flag->id;
            $userFlag->user_id = $flag->user_id;
            $userFlag->first_flagged = $flag->first_flagged;
            $userFlag->cid = $flag->cid;
            $userFlag->type = $flag->type;
            $userFlag->amount = $flag->amount;
            $userFlag->reason = $flag->reason;
            $userFlag->flagger_username = $flag->flagger_username;
            $userFlag->save();
        }
        DB::commit();
        return true;
    }
}
