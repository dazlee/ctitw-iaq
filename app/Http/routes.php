<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('home');
});

Route::auth();

Route::get('/dashboard/{id}', 'DashboardController@index');
Route::get('/stats', 'StatsController@index');
Route::get('/history', 'StatsController@history');
Route::get('/all', 'StatsController@all');
Route::get('/accounts/agent', 'AccountsController@agent');
Route::get('/accounts/client', 'AccountsController@client');
Route::get('/accounts/department', 'AccountsController@department');
Route::get('/accounts/device', 'AccountsController@device');

/**
 * APIs
 */
Route::group(['prefix'=>'api'], function () {
    Route::post('devices/file', 'DeviceHistoryController@upload');
    Route::resource('devices', 'DeviceHistoryController');
});
