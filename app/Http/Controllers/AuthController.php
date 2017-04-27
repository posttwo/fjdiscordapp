<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;
use App\Jobs\JoinUserDiscord;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function redirect()
    {
        $agent = new Agent();
        if($agent->isRobot())
            return view('forbots');
        return Socialite::with('discord')->stateless()->redirect();
    }

    public function handleCallback()
    {
        $discord = Socialite::driver('discord')->stateless()->user();         //http://i.imgur.com/cda8ZGI.png

        //Check if user exists
        $user = User::where('discord_id', $discord->id)->first();
        if($user === null)
        {
            //user doesnt exist, lets create it
            $user = User::create([
                'discord_id' => $discord->id,
                'nickname'   => $discord->nickname,
                //'email'      => $discord->email,
                'token'      => $discord->token,
                'refreshToken' => $discord->refreshToken,
            ]);
            dispatch(new JoinUserDiscord($user));
        }else{
            $user->token = $discord->token;
            $user->refreshToken = $discord->refreshToken;
            $user->save();
        }
        Auth::loginUsingId($user->id, true);
        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
