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
use App\Events\UserJoinedGroup;
use App\Role;
use RestCord\DiscordClient;

class VerificationController extends Controller
{

    public function __construct()
    {
        $this->fj = new FunnyJunk();
        $this->fj->login(env("FJ_USERNAME"), env("FJ_PASSWORD"));
    }

    public function sendPM($username)
    {
        if(Auth::user()->fjuser !== null)
            abort(400);
            
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
        logger("Verification: Sending PM", ["id" => Auth::user()->id, "fj_username" => $username, "token" =>$token->token]);
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
            logger("Verification: User has been verified", ["id" => Auth::user()->id, "verified_as" => $token->username]);
            $fj = new FJUser();
            $fj->set(array('username' => $token->username));
            $fj->getId();

            $fjuser = new FunnyjunkUser();
            $fjuser->fj_id = $fj->id;
            $fjuser->username = $token->username;
            $fjuser->level = 0; //@TODO REMOVE
            $fjuser->ismod = 0; //@TODO REMOVE
            Auth::user()->fjuser()->save($fjuser);
            dispatch(new VerifyUserDiscord(Auth::user()));
            if(env('FJ_API_ENABLED') == true)
                    $this->sync();
        }
        else
        {
            logger()->notice("Verification: User token mismatch", ["id" => Auth::user()->id, "token_id" => $token->user->id]);
            abort(404);
        }
    }

    public function sync()
    {
        logger("Verification: Synching Permissions", ["id" => Auth::user()->id]);
        $user = new FJUser();
        $user->set(array('username' => Auth::user()->fjuser->username));
        $user->populate();
        dd(Auth::user()->fjuser);
        if(Auth::user()->cannot('user.verified') && $user->username != null)
        {
            Auth::user()->givePermissionTo('user.verified');
        }
        if($user->contributor_account && Auth::user()->cannot('user.patreon'))  
            Auth::user()->givePermissionTo('user.patreon');
        if($user->has_oc_item && Auth::user()->cannot('user.occreator'))
            Auth::user()->givePermissionTo('user.occreator');
        if($user->level > 99 && Auth::user()->cannot('user.level100'))
            Auth::user()->givePermissionTo('user.level100');
        if($user->level > 199 && Auth::user()->cannot('user.level200'))
            Auth::user()->givePermissionTo('user.level200');
        if($user->level > 399 && Auth::user()->cannot('user.level400'))
            Auth::user()->givePermissionTo('user.level400');
        if($user->level > 9 && Auth::user()->cannot('user.level10'))
            Auth::user()->givePermissionTo('user.level10');
        return "ok";
    }

    public function test()
    {
        logger()->alert("TEST FUNCTION ACCESSED", ["id" => Auth::user()->id]);
    }
}
