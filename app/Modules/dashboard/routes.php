<?php

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(array('module'=>'Dashboard', 'prefix'=>'dashboard' ,'middleware' => 'auth', 'namespace' => 'App\Modules\Dashboard\Controllers'), function() {

    Route::get('/', array('as' => 'home', 'uses'=>'DashboardController@home'));
    Route::get('/home', array('as' => 'home', 'uses'=>'DashboardController@home'));
});