<?php

// srvAudit.com
Route::get('/', 'WelcomeController@show');
Route::get('/support', 'WelcomeController@support');
Route::get('/privacy', 'WelcomeController@privacy');

// The docs and stuff
Route::get('docs', 'DocsController@showRootPage');
Route::get('docs/{version}/{page?}', 'DocsController@show');

// The app
Route::get('/home', 'SessionController@index');
Route::get('/sessions', 'SessionController@listSessions');
Route::get('/sessions/{id}', 'SessionController@getSession');
Route::get('/sessions/server/{id}', 'SessionController@getServers');
Route::get('/sessions/user/{id}', 'SessionController@getUser');

// Installers
Route::get('/install', 'InstallController@install');
Route::get('/installcentos', 'InstallController@installcentos');
Route::get('/dockerinstall', 'InstallController@dockerinstall');

// OAuth apps
Route::get('auth/{driver}', ['as' => 'socialAuth', 'uses' => 'Auth\SocialController@redirectToProvider']);
Route::get('auth/{driver}/callback', ['as' => 'socialAuthCallback', 'uses' => 'Auth\SocialController@handleProviderCallback']);

// SSL auth
Route::get('.well-known/acme-challenge/OMPNteObEaEfolTurkKZVo2SOKf7g8yVNfA34FW5Uj0', 'WelcomeController@letsencrypt');
// Detectify
Route::get('00ab93d90a6b098ac2d26a4324b8c8f9.txt', 'WelcomeController@detectify');
