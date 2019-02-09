<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\User as FJUser;
use App\Slack;

class ModComplaintController extends Controller
{
    public function getComplaints()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
        $data = collect($this->fj->getComplaints());
        return $data;
    }

    public function checkComplaintsAndAlertMods()
    {
        $complaints = $this->getComplaints();
        $noresponse = $complaints->where('status', 0);

        foreach($noresponse as $complaint)
        {
            //Is user activately banned?
            $u = new FJUser();
            $u->id = $complaint->id_user;
            $u->getUsername();
            $u->populate();
            $lastBan = (collect($u->ban_history)->last());
            if($lastBan->user_ban_time > 0)
            {
                //User currently banned, high importance
                $slack = new Slack;
                $slack->target = 'mod-notify';
                $slack->username = 'Complaints SEV1';
                $slack->avatar = 'https://i.imgur.com/H4LXDgR.png';
                $slack->title = "Banned User Complaining";
                $slack->text = 'https://funnyjunk.com/sfw_mod/complaints/ Currently banned user has filed a complaint <@&151904333703675904> From:' . $u->username;
                $slack->embedFields = ['id' => $complaint->id, 'Issue' => $complaint->complaint];
                $slack->footer = "Users flag history shows a ban as last entry!";
                $slack->color = "error";
                \Notification::send($slack, new \App\Notifications\ModNotify(null));
            }
        }
        
        if($noresponse->count() > 3)
        {
            $slack = new Slack;
            $slack->target = 'mod-notify';
            $slack->username = 'Complaints Bugger';
            $slack->avatar = 'https://i.imgur.com/H4LXDgR.png';
            $slack->title = "Banned User Complaining";
            $slack->text = 'https://funnyjunk.com/sfw_mod/complaints/ There is too many complaints in the queue';
            $slack->embedFields = ['Count' => $noresponse->count()];
            $slack->footer = "Gay!";
            $slack->color = "warning";
            \Notification::send($slack, new \App\Notifications\ModNotify(null));
        }
        return "hi";
    }
}
