<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/roster', 'RosterController@index');
Route::get('/loot', ['uses' => 'LootController@index', 'as' => 'loot']);
Route::get('/loot/mythic-plus', ['uses' => 'LootController@mythicPlus', 'as' => 'loot.mythic-plus']);
Route::get('/loot/mythic-plus-cache', ['uses' => 'LootController@mythicPlusCache', 'as' => 'loot.mythic-plus-cache']);
Route::get('/loot/type/legendary', ['uses' => 'LootController@legendary', 'as' => 'loot.legendary']);
Route::get('/loot/raid', ['uses' => 'LootController@mythicRaid', 'as' => 'loot.raid']);
Route::get('/loot/{characterName}', ['uses' => 'LootController@character', 'as' => 'loot.character']);
Route::get('/logs', ['uses' => 'LogsController@index', 'as' => 'logs']);
Route::get('/logs/averages', ['uses' => 'LogsController@averages', 'as' => 'logs.averages']);
Route::get('/logs/{character}', ['uses' => 'LogsController@character', 'as' => 'logs.character']);
Route::get('/artifacts', ['uses' => 'ArtifactController@index', 'as' => "artifacts.index"]);
Route::get('/boosts', 'BoostController@index');
Route::get('/availability', 'AvailabilityController@index');