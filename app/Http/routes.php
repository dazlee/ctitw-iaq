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

Route::get('/dashboard/{id}',       ['middleware' => 'auth', 'uses' => 'DashboardController@index']);
Route::get('/stats',                ['middleware' => 'auth', 'uses' => 'StatsController@index']);
Route::get('/history',              ['middleware' => 'auth', 'uses' => 'StatsController@history']);
Route::get('/all',                  ['middleware' => 'auth', 'uses' => 'StatsController@all']);
Route::get('/accounts/agent',       ['middleware' => ['role:admin'], 'uses' => 'AccountsController@agent']);
Route::post('/accounts/agent',      ['middleware' => ['role:admin'], 'uses' => 'AccountsController@createAgent']);
Route::get('/accounts/client',      ['middleware' => ['role:admin'], 'uses' => 'AccountsController@client']);
Route::post('/accounts/client',     ['middleware' => ['role:admin'], 'uses' => 'AccountsController@createClient']);
Route::get('/accounts/department',  ['middleware' => ['role:admin'], 'uses' => 'AccountsController@department']);
Route::post('/accounts/department', ['middleware' => ['role:admin'], 'uses' => 'AccountsController@createDepartment']);
Route::get('/accounts/device',      ['middleware' => 'auth', 'uses' => 'AccountsController@device']);

/**
 * APIs
 */
Route::group(['prefix'=>'api'], function () {
    Route::post('devices/file', 'DeviceHistoryController@upload');
    Route::resource('devices', 'DeviceHistoryController');

    Route::resource('stats/summary', 'StatsController@summary');
});
