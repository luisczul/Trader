<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('uses' => "TraderController@index"));
Route::get('dashboard', array('uses' => "TraderController@index"));
Route::post('widgets/stockData', array('uses' => "TraderController@stockData"));
Route::post('widgets/stockStatus', array('uses' => "TraderController@stockStatus"));
Route::post('Trade/modify', array('uses' => "TraderController@modify"));

//CLIENTS
/*
Route::post('/widgets/clients', array('before' => 'auth','uses' => "ClientsController@index"));
Route::post('/widgets/clients/full', array('before' => 'auth','uses' => "ClientsController@indexFull"));
Route::get('/widgets/clients', array('before' => 'auth','uses' => "ClientsController@index"));
Route::get('/widgets/clients/{param}', array('before' => 'auth','uses' => "ClientsController@show"));
Route::post('/widgets/clients/addEdit/', array('before' => 'auth','uses' => "ClientsController@AddEdit"));
Route::delete('/widgets/clients/{param}', array('before' => 'auth','uses' => "ClientsController@destroy"));
Route::post('/Trainer/addClient', array('before' => 'auth','as'=>"ProfileTrainer",'uses' => "ClientsController@addClient"));
Route::post('/Trainer/addClientWithId', array('before' => 'auth','as'=>"ProfileTrainer",'uses' => "ClientsController@addClientWithId"));
Route::get('/Clients/Invitation/{invite}', array('before' => 'auth','as'=>"ProfileTrainer",'uses' => "ClientsController@confirmClientByInvitation"));
Route::get('/Client/{id}/{username}', array('before' => 'auth','as'=>"Profile",'uses' => "ClientsController@clientProfile"));
*/


