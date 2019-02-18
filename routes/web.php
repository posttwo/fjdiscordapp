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

Route::get('/usc/{modCase}/{hash}', 'ModCaseController@getCaseForUser')->name('moderator.case.viewbyuser');
Route::post('/usc/{modCase}/{hash}', 'ModCaseController@addCaseMessageByUser')->name('moderator.case.postbyuser');

Route::group(['middleware' => ['auth','web']], function () {
    Route::get('/verify/fj/{username}', 'VerificationController@sendPM');
    Route::get('/verify2/fj/{token}', 'VerificationController@verify');
    Route::group(['domain' => env('APP_URI')], function () {
        Route::get('/', 'HomeController@view')->name('home');
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
        Route::get('/mods/flagnotice', 'FlagNoticeController@index')->middleware('role:mod.isAMod')->name('moderator.flagnotice.index');

        //Mod Complaints
        Route::get('/mods/complaints/{modCase}', 'ModCaseController@getCase')->middleware('role:mod.isAMod')->name('moderator.case');
        //Route::get('/mods/complaints/{sourceType}/{sourceId}', 'ModCaseController@getCase')->middleware('role:mod.isAMod')->name('moderator.case');
        Route::get('/mods/complaints', 'ModCaseController@index')->middleware('role:mod.isAMod')->name('moderator.case.index');
        Route::post('/mods/complaints/{modCase}', 'ModCaseController@addCaseMessage')->middleware('role:mod.isExec')->name('moderator.case.postmessage');
        Route::get('/mods/complaints/{modCase}/resetAccessKey', 'ModCaseController@resetAccessKey')->middleware('role:mod.isExec')->name('moderator.case.resetaccesskey');
        Route::get('/mods/complaints/{modCase}/toggleCaseLock', 'ModCaseController@toggleCaseLock')->middleware('role:mod.isExec')->name('moderator.case.togglecaselock');
        Route::get('/mods/complaints/{modCase}/resolveCase', 'ModCaseController@resolveCase')->middleware('role:mod.isExec')->name('moderator.case.resolvecase');
        //setCaseStatus
        //Modstats
        Route::get('/test2', 'ModActionController@getLastTimeUserRatedContent');
        Route::get('/mods/ratings/nobody', 'ModActionController@getContentWithNoAttribution')->middleware('role:mod.isExec')->name('moderator.ratings.nobody');

        Route::get('/mods/ratings/{fjusername}/{from?}/{to?}', 'ModActionController@getContentAttributedToUser')->middleware('role:mod.isAMod')->name('moderator.ratings.viewuser');
        Route::get('/mods/contentInfo/{fjcontent}', 'ModActionController@getContentById')->middleware('role:mod.isAMod')->name('moderator.contentInfo');
        Route::post('/mods/ratings/nobody/attribute/{content}/{userid}', 'ModActionController@attributeContent')->middleware('role:mod.isExec');

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
    Route::get('login/discord', 'AuthController@loginWithDiscord')->name('login.discord');
    Route::get('login/callback', 'AuthController@handleCallback');
    Route::get('logout', 'AuthController@logout');
    Route::get('/list/cah', 'ListController@listCahCards')->name('cahcards');
}); 

Route::post('webhook/mail/{key}', 'WebhookController@process');
