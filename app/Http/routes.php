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
Route::group(['prefix'=>'accounts'], function () {
    Route::get('agent',       ['as' => 'agents', 'middleware' => ['role:admin'], 'uses' => 'AccountsController@agent']);
    Route::get('agent/{id}',  ['middleware' => ['role:admin'], 'uses' => 'AccountsController@agentDetails']);
    Route::post('agent',      ['middleware' => ['role:admin'], 'uses' => 'AccountsController@createAgent']);
    Route::post('agent/{id}', ['middleware' => ['role:admin'], 'uses' => 'AccountsController@updateAgent']);

    Route::get('client',      ['middleware' => ['role:admin|agent'], 'uses' => 'AccountsController@client']);
    Route::post('client',     ['middleware' => ['role:admin|agent'], 'uses' => 'AccountsController@createClient']);

    Route::get('department',  ['middleware' => ['role:admin|client'], 'uses' => 'AccountsController@department']);
    Route::post('department', ['middleware' => ['role:admin|client'], 'uses' => 'AccountsController@createDepartment']);
});

/**
 * APIs
 */
Route::group(['prefix'=>'api'], function () {
    Route::post('devices/file', 'DeviceHistoryController@upload');
    Route::resource('devices', 'DeviceHistoryController');

    Route::resource('stats/summary', 'StatsController@summary');
});
