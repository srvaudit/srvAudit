<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register the API routes for your application as
| the routes are automatically authenticated using the API guard and
| loaded automatically by this application's RouteServiceProvider.
|
*/

Route::group([
    'middleware' => 'auth:api'
], function () {
    Route::post('/session/{sid}', 'SessionController@store');
    Route::patch('/session/{sid}', 'SessionController@update');
    Route::get('/session/{sid}/commands', 'SessionController@getSession');
    Route::get('/session/sessionchart', 'SessionController@getSessionChart');
    Route::get('/session/userchart', 'SessionController@getUserChart');
    
    Route::post('/command/{sid}', 'CommandController@store');

    Route::post('/addonesignalid', 'OneSignalController@addOneSignalID');
});
