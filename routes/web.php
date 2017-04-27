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
Route::group(['domain' => '{slug}.fjme.me', 'middleware' => 'auth'], function () {
        Route::get('/', 'GroupController@slugJoin');
    });
Route::middleware('auth')->get('/verify/fj/{username}', 'VerificationController@sendPM');
Route::middleware('auth')->get('/verify2/fj/{token}', 'VerificationController@verify');



Route::group(['middleware' => 'auth'], function () {
    Route::group(['domain' => env('APP_URI')], function () {
        Route::get('/', 'HomeController@view')->name('home');
        //Route::get('/test2', 'VerificationController@test');
        Route::get('/join/{name}', 'GroupController@join');
        Route::get('/leave/{name}', 'GroupController@leave');

        Route::get('/roles', 'AdminController@viewRoles')->middleware('role:admin.roles')->name('admin.roles');
        Route::post('/roles', 'AdminController@addRole')->middleware('role:admin.roles')->name('admin.roles');
     });

    Route::group(['domain' => '{slug}.' . env('APP_URI')], function () {
        Route::get('/', 'GroupController@slugJoin');
    });
});



Route::get('login', 'AuthController@redirect')->name('login');
Route::get('login/callback', 'AuthController@handleCallback');
Route::get('logout', 'AuthController@logout');
