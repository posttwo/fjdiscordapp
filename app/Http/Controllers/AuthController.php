<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\User;
use Auth;
use App\Jobs\JoinUserDiscord;
use Jenssegers\Agent\Agent;
use App\Role;

class AuthController extends Controller
{
    public function redirect(Request $request)
    {
        $agent = new Agent();
        if($agent->isRobot()){
            logger('Robot requested access to auth area, exposing memes.', ['useragent' => $agent->getUserAgent()]);
            $subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];
            $role = Role::where('slug', $subdomain)->first();
            return view('forbots')->with('role', $role);
        }
        logger('Begining authentication process');
        return Socialite::with('discord')->stateless()->redirect();
    }

    public function handleCallback()
    {
        try {
            $discord = Socialite::driver('discord')->stateless()->user();         //http://i.imgur.com/cda8ZGI.png
        } catch (\Exception $e) {
            if($e->getCode() == 400){
                logger()->info("User didn't authorize us to his discord account");
                return response()->view('layout.errors.discordauth', [], 400);
            } else {
                logger()->error("Discord auth failed in some other way, throwing");
                throw $e;
            }
        }
        //Check if user exists
        $user = User::where('discord_id', $discord->id)->first();
        if($user === null)
        {
            //user doesnt exist, lets create it
            $user = User::create([
                'discord_id' => $discord->id,
                'nickname'   => $discord->nickname,
                'token'      => $discord->token,
                'refreshToken' => $discord->refreshToken,
                'avatar' => $discord->avatar,
            ]);
            dispatch(new JoinUserDiscord($user));
            logger('User has been created', ['id' => $user->id, "username" => $user->nickname]);
        }else{
            $user->nikcname = $discord->nickname;
            $user->token = $discord->token;
            $user->refreshToken = $discord->refreshToken;
            $user->avatar = $discord->avatar;
            $user->save();
            logger('User has been updated', ['id' => $user->id, "username" => $user->nickname]);
        }
        Auth::loginUsingId($user->id, true);
        logger('User has been authenticated', ['id' => $user->id, "username" => $user->nickname]);
        return redirect()->intended('/');
    }

    public function logout()
    {
        logger('User has been logged out', ['id' => Auth::user()->id]);
        Auth::logout();
        return redirect()->route('home');
    }
}
