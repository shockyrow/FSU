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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'LeagueController@home');

Route::get('/games', 'LeagueController@playedGames');

Route::get('/first', 'LeagueController@firstGame');

Route::get('/refresh', 'LeagueController@refresh');

Route::get('/edit/{id}', 'LeagueController@edit');

Route::get('/finish_week', 'LeagueController@finishWeek');

Route::get('/finish_league', 'LeagueController@finishLeague');

Route::post('/save', 'LeagueController@save');