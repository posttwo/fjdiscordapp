<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['domain' => 'guide.' . env('APP_URI')], function () {
    Route::get('/', function(){
        return redirect("https://funnyjunk.com/Funnyjunk+discord/funny-pictures/6253653/");
    });
});   
Route::group(['domain' => 'rules.' . env('APP_URI')], function () {
    Route::get('/', function(){
        return redirect("https://redmine.posttwo.pt/projects/fj/wiki");
    });
});   
Route::group(['middleware' => ['auth','web']], function () {
    Route::get('/verify/fj/{username}', 'VerificationController@sendPM');
    Route::get('/verify2/fj/{token}', 'VerificationController@verify');
    Route::group(['domain' => env('APP_URI')], function () {
        Route::get('/', 'HomeController@view')->name('home');
        Route::get('/test2', 'VerificationController@test');
        Route::get('/join/{role}', 'GroupController@join');
        Route::get('/leave/{role}', 'GroupController@leave');

        Route::get('/roles', 'AdminController@viewRoles')->middleware('role:admin.roles')->name('admin.roles');
        Route::post('/roles', 'AdminController@addRole')->middleware('role:admin.roles')->name('admin.roles');

        Route::post('/roles/restrict', 'AdminController@addRestriction')->middleware('role:admin.roles')->name('admin.roles.restriction');
        Route::get('/permissions', 'AdminController@getListOfPermissions')->middleware('role:admin.roles')->name('admin.permissions.list');
        Route::get('/permissions/sync', 'VerificationController@sync')->name('user.permissions.sync');     

        //Mods
        Route::get('/mods', 'ModeratorController@index')->middleware('role:mod.isAMod')->name('moderator.index');
        Route::get('/mods/tokens', 'ModeratorController@indexTokens')->middleware('role:mod.isAMod')->name('moderator.tokens.index');
        Route::post('/mods/tokens', 'ModeratorController@storeToken')->middleware('role:mod.isAMod')->name('moderator.tokens.index');
        Route::delete('/mods/tokens/{id}', 'ModeratorController@revokeToken')->middleware('role:mod.isAMod');
        Route::get('/mods/tokens/scopes', 'ModeratorController@getAvailableScopes')->middleware('role:mod.isAMod');
        Route::get('/mods/notetoken', 'ModeratorController@getOrCreateNotesToken')->middleware('role:mod.isAMod');

        //DJ Mods
        Route::get('/mods/dj/{boardName}', 'DJController@index')->name('moderator.dj.index')->middleware('role:mod.isAMod');
        Route::get('/mods/dj/{boardName}/{djNumber}', 'DJController@initiateReplacement')->name('moderator.dj.replace')->middleware('role:mod.isAMod')->middleware('throttle:1');
    });

    Route::group(['domain' => '{role}.' . env('APP_URI')], function () {
        Route::get('/', 'GroupController@slugJoin');
    });
});

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'AuthController@redirect')->name('login');
    Route::get('login/callback', 'AuthController@handleCallback');
    Route::get('logout', 'AuthController@logout');
    Route::get('/list/cah', 'ListController@listCahCards')->name('cahcards');
}); 

Route::post('webhook/mail/{key}', 'WebhookController@process');
