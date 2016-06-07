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

Route::group(array('module'=>'grading', 'prefix' => 'grading', 'middleware' => 'auth', 'namespace' => 'App\Modules\Grading\Controllers'), function() {
    
    Route::get('/', array('as' => 'allassignment', 'uses'=>'GradingController@assignment'));

    Route::get('list-student/{id}', ['as' => 'studentGrading', 'uses' => 'GradingController@studentGradeListing']);

    Route::get('list-question/{id}', ['as' => 'questionGrading', 'uses' => 'GradingController@questionGradeListing']);
});