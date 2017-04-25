<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Posttwo\FunnyJunk\FunnyJunk;
use Posttwo\FunnyJunk\PM;
use Posttwo\FunnyJunk\User as FJUser;
use App\FunnyjunkUser;
use App\Token;
use App\User;
use Auth;
use DateTime;
use App\Jobs\VerifyUserDiscord;
use Debugbar;

class VerificationController extends Controller
{

    public function __construct()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }

    public function sendPM($username)
    {
        $this->fj->acceptFriends();
        //get id of user
        $user = new FJUser();
        $user->set(array('username' => $username));
        $user->getId();

        //generate token
        $token = new Token();
        $token->generateToken();
        $token->username = $username;

        Auth::user()->verificationTokens()->save($token);
        //send pm
        $pm = new PM();
        $pm->sendToUser($user->id, $user->username, "Discord Verification", $token->token);
        //return $username;
    }

    public function verify($token)
    {
        $date = new DateTime;
        $date->modify('-15 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        $token = Token::where('token', $token)->where('created_at', '>=', $formatted_date)->firstOrFail();
        Debugbar::info("thanks");
        if(Auth::user()->id == $token->user->id)
        {
            $fj = new FJUser();
            $fj->set(array('username' => $token->username));
            $fj->getId();

            $fjuser = new FunnyjunkUser();
            $fjuser->fj_id = $fj->id;
            $fjuser->username = $token->username;
            $fjuser->level = 0; //@TODO
            $fjuser->ismod = 0; //@TODO
            Auth::user()->fjuser()->save($fjuser);
            dispatch(new VerifyUserDiscord(Auth::user()));
        }
        else
        {
            abort(404);
        }
    }

    public function test()
    {
        //dd(Auth::user());
        $discord = new \RestCord\DiscordClient(['token' => env('DISCORD_TOKEN'), 'throwOnRatelimit' => true]);
    }
}
