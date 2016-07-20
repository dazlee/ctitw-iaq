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

/*
Route::get('/', function () {
    return view('home');
});*/

Route::get('/', ['uses' => 'HomeController@index']);

Route::auth();

Route::get('/dashboard/{device_id}',       ['middleware' => ['role:client|department'], 'uses' => 'DashboardController@index']);
// Route::get('/devices/{id}',                ['middleware' => ['role:client'], 'uses' => 'DashboardController@index']);
Route::get('/stats',                       ['middleware' => ['role:client|department'], 'uses' => 'StatsController@index']);
Route::get('/history',                     ['middleware' => ['role:client|department'], 'uses' => 'StatsController@history']);
Route::get('/all',                         ['middleware' => ['role:client|department'], 'uses' => 'StatsController@all']);
Route::get('/files',                       ['middleware' => ['role:admin|client'], 'uses' => 'FilesController@index']);
Route::get('/files/{file_id}',             ['middleware' => ['role:admin|client'], 'uses' => 'FilesController@downloadFile']);
Route::post('/files/{file_id}/delete',     ['middleware' => ['role:client'], 'uses' => 'FilesController@deleteFile']);

Route::post('/client/{client_id}/file',     ['middleware' => ['role:client'], 'uses' => 'FilesController@uploadFile']);

Route::get('/stats-files',                 ['middleware' => ['role:admin|client'], 'uses' => 'FilesController@statsFiles']);
Route::get('/stats-files/{deviceAccount}/{year}/{quarter}', ['middleware' => ['role:admin|client'], 'uses' => 'FilesController@getStatsFiles']);

Route::get('/settings',                    ['middleware' => ['role:admin|client'], 'uses' => 'SettingController@index']);

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/client/{id}/stats',   ['as' => '_client_stats',    'uses' => 'StatsController@index']);
    Route::get('/client/{id}/history', ['as' => '_client_history',  'uses' => 'StatsController@history']);
    Route::get('/client/{id}/all',     ['as' => '_client_all',      'uses' => 'StatsController@all']);
    Route::get('/client/{id}/dashboard/{device_id}',      ['as' => '_client_device', 'uses' => 'DashboardController@indexForAdmin']);

    Route::post('/settings',           ['uses' => 'SettingController@update']);
});

Route::group(['middleware' => ['role:client']], function () {
    Route::post('/client/{client_id}/settings',     ['uses' => 'SettingController@updateClientSetting']);
});

Route::group(['prefix'=>'accounts'], function () {


    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('agent',       ['as' => 'agents', 'middleware' => ['role:admin'], 'uses' => 'AccountsController@agent']);
        Route::get('agent/{id}',  ['middleware' => ['role:admin'], 'uses' => 'AccountsController@agentDetails']);
        Route::post('agent',      ['middleware' => ['role:admin'], 'uses' => 'AccountsController@createAgent']);
        Route::post('agent/{id}', ['middleware' => ['role:admin'], 'uses' => 'AccountsController@updateAgent']);

        Route::post('agent/{id}/deactive', ['middleware' => ['role:admin'], 'uses' => 'AccountsController@deactive']);
        Route::post('agent/{id}/active',   ['middleware' => ['role:admin'], 'uses' => 'AccountsController@active']);
        Route::post('agent/{id}/delete',   ['middleware' => ['role:admin'], 'uses' => 'AccountsController@delete']);
    });


    Route::get('client',      ['as' => 'clients', 'middleware' => ['role:admin|agent'], 'uses' => 'AccountsController@client']);
    Route::get('client/{id}', ['middleware' => ['role:admin|agent'], 'uses' => 'AccountsController@clientDetails']);
    Route::post('client',     ['middleware' => ['role:admin|agent'], 'uses' => 'AccountsController@createClient']);
    Route::post('client/{id}',['middleware' => ['role:admin|agent'], 'uses' => 'AccountsController@updateClient']);
    Route::post('department/{id}/delete',   ['middleware' => ['role:admin|agent|client'], 'uses' => 'AccountsController@deleteDepartment']);

    Route::get('department',        ['as' => 'departments', 'middleware' => ['role:admin|client'], 'uses' => 'AccountsController@department']);
    Route::get('department/{id}',   ['middleware' => ['role:admin|client'], 'uses' => 'AccountsController@departmentDetails']);
    Route::post('department',       ['middleware' => ['role:admin|client'], 'uses' => 'AccountsController@createDepartment']);
    Route::post('department/{id}',  ['middleware' => ['role:admin|client'], 'uses' => 'AccountsController@updateDepartment']);
});

/**
 * APIs
 */
Route::group(['prefix'=>'api'], function () {
    Route::post('devices/file', 'DeviceHistoryController@upload');
    Route::resource('devices', 'DeviceHistoryController');

    Route::resource('stats/summary', 'StatsController@summary');
});
