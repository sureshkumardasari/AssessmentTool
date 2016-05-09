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

Route::group(array('module'=>'admin', 'prefix' => 'user', 'middleware' => 'auth', 'namespace' => 'App\Modules\Admin\Controllers'), function() {
    
    Route::get('/', ['as' => 'list', 'uses' => 'UserController@index']);
    Route::get('profile', array('as' => 'profile', 'uses'=>'UserController@edit'));
    Route::get('add', array('as' => 'add', 'uses'=>'UserController@add'));
    Route::get('edit/{id}', array('as' => 'edit', 'uses'=>'UserController@edit'));
    Route::get('del/{id}', array('as' => 'delete', 'uses'=>'UserController@delete'));
    Route::post('update', array('as' => 'update', 'uses'=>'UserController@update'));

    Route::get('institution', array('as' => 'list', 'uses'=>'InstitutionController@index'));
	Route::get('institutionadd', array('as' => 'add', 'uses'=>'InstitutionController@add'));
    Route::get('institutionedit/{id}', array('as' => 'edit', 'uses'=>'InstitutionController@edit'));
    Route::get('institutiondel/{id}', array('as' => 'delete', 'uses'=>'InstitutionController@delete'));
    Route::post('institutionupdate', array('as' => 'update', 'uses'=>'InstitutionController@update')); 

    Route::get('role', array('as' => 'list', 'uses'=>'UserController@roleslist'));
    Route::get('roleadd', array('as' => 'add', 'uses'=>'UserController@roleadd'));
    Route::get('roleedit/{id}', array('as' => 'edit', 'uses'=>'UserController@roleedit'));
    Route::get('roledel/{id}', array('as' => 'delete', 'uses'=>'UserController@roledelete'));
    Route::post('roleupdate', array('as' => 'update', 'uses'=>'UserController@roleupdate'));           
});