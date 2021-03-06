<?php

namespace App\Http\Controllers;

use Request;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\PM;
use Posttwo\FunnyJunk\User as FJUser;
use App\FunnyjunkUser;

class WebhookController extends Controller
{
    public function __construct()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }

    public function process($key){
		if($key != env("WEBHOOK_TOKEN"))
			abort(403);
		$from = Request::input('mail_from');
		$parts = explode("@", $from);
		$from = $parts[1];
		
		$user = new FJUser();
		$parts = explode("@", Request::input('rcpt_to'));
		$username = $parts[0];
		$username = preg_replace("/[^[:alnum:][:space:]]/u", '', $username);
		$user->set(array('username' => $username));

		try{
			$fjuser = FunnyjunkUser::where('username', $username)->firstOrFail();
			$user->id = (int)$fjuser->fj_id;
			if(!is_numeric($user->id))
				throw new Illuminate\Database\Eloquent\ModelNotFoundException;
		} 
		catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
			$user->getId();
		}
		logger("Sending PM to: $username with $user->id");
		$pm = new PM();
		$pm->sendToUser($user->id, $user->username, Request::input('subject'), Request::input('plain_body'));
			
		return "Sent PM to $username with ID $user->id";
    }
}
