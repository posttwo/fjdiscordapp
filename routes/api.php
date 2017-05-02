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

//For FJ Admin
Route::middleware('api')->post('/fjuser/{fjid}', function (Request $request, $fjid) {
    if($request->input('key') != env('FJ_ADMIN_KEY'))
        abort(403);
    $user = \App\FunnyjunkUser::where('fj_id', $fjid)->orderBy('created_at', 'desc')->firstOrFail();
    logger("API Request for", ["fj_username", $user->username]);
    return $user->user;
});