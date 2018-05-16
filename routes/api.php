<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//For FJ Admin, lets make it easy for him
Route::middleware('throttle:1000,1,1')->post('/fjuser/{fjid}', function (Request $request, $fjid) {
    if($request->input('key') != env('FJ_ADMIN_KEY'))
        abort(403);
    $user = \App\FunnyjunkUser::where('fj_id', $fjid)->orderBy('created_at', 'desc')->firstOrFail();
    logger("API Request for", ["fj_username", $user->username]);
    return $user->user;
});


//For everyone else, fuck you we do it properly here
Route::group(['middleware' => 'throttle:60,1,1'], function(){
    Route::get('/fjuser/basicUserByName/{username}', 'API\FJUserController@getBasicUserByUsername')->middleware(['auth:api', 'scope:fjapi-userinfo-basic', 'role:mod.isAMod']);
    Route::get('/fjuser/modUserByName/{username}', 'API\FJUserController@getModUserByUsername')->middleware(['auth:api', 'scope:fjapi-userinfo-mod', 'role:mod.isAMod']);
    Route::get('/fjuser/discordByID/{fjid}', function($fjid){
	if(Auth::user()->cannot('mod.isAMod'))
                abort(403);
        $user = \App\FunnyjunkUser::where('fj_id', $fjid)->with('user')->get()->pluck('user');
        return $user;
    })->middleware(['auth:api', 'scope:fjapi-userinfo-mod', 'role:mod.isAMod']);
    Route::post('/mods/discord/help', 'API\DiscordHelpController@sendHelpRequest')->middleware(['auth:api', 'scope:discord-post-modhelp', 'role:mod.isAMod']);
    Route::get('/mods/notetoken', 'ModeratorController@getOrCreateNotesToken')->middleware(['auth:api', 'role:mod.isAMod', 'scope:fjmod-token']);
});

Route::get('/userinfo/', function(Request $request){
	if(Auth::user()->cannot('mod.isAMod'))
                abort(403);
	$return['user'] = $request->user();
	$return['user']['email'] = $request->user()->fjuser->username . '@changeme.local';
	$return['user']['fjuser'] = $request->user()->fjuser;
	$return['user']['roles'] = $request->user()->permissions;
	return $return;
})->middleware(['auth:api', 'scope:fjapi-userinfo-basic']);
