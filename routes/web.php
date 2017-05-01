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

        Route::get('/list/cah', 'ListController@listCahCards');
     });

    Route::group(['domain' => '{role}.' . env('APP_URI')], function () {
        Route::get('/', 'GroupController@slugJoin');
    });
});

Route::group(['middleware' => ['web']], function () {
    Route::get('login', 'AuthController@redirect')->name('login');
    Route::get('login/callback', 'AuthController@handleCallback');
    Route::get('logout', 'AuthController@logout');
}); 
