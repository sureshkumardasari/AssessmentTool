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

Route::group(array('module'=>'resources', 'prefix' => 'resources', 'middleware' => 'auth', 'namespace' => 'App\Modules\Resources\Controllers'), function() {
    
    Route::get('/', ['as' => 'list', 'uses' => 'ResourceController@subject']);
    Route::get('subject', ['as' => 'list', 'uses' => 'ResourceController@subject']);
    Route::get('subjectadd', array('as' => 'add', 'uses'=>'ResourceController@subjectadd'));
    Route::get('subjectedit/{id}', array('as' => 'edit', 'uses'=>'ResourceController@subjectedit'));
    Route::get('subjectdel/{id}', array('as' => 'delete', 'uses'=>'ResourceController@subjectdelete'));
    Route::post('subjectupdate', array('as' => 'update', 'uses'=>'ResourceController@subjectupdate'));

    Route::get('lesson', array('as' => 'list', 'uses'=>'ResourceController@lesson'));
	Route::get('lessonadd', array('as' => 'add', 'uses'=>'ResourceController@lessonadd'));
    Route::get('lessonedit/{id}', array('as' => 'edit', 'uses'=>'ResourceController@lessonedit'));
    Route::get('lessondel/{id}', array('as' => 'delete', 'uses'=>'ResourceController@lessondelete'));
    Route::post('lessonupdate', array('as' => 'update', 'uses'=>'ResourceController@lessonupdate'));

    Route::get('category', array('as' => 'list', 'uses'=>'ResourceController@category'));
    Route::get('categoryadd', array('as' => 'add', 'uses'=>'ResourceController@categoryadd'));
    Route::get('categoryedit/{id}', array('as' => 'edit', 'uses'=>'ResourceController@categoryedit'));
    Route::get('categorydel/{id}', array('as' => 'delete', 'uses'=>'ResourceController@categorydelete'));
    Route::post('categoryupdate', array('as' => 'update', 'uses'=>'ResourceController@categoryupdate'));
});