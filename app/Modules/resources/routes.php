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
    
    Route::get('/', ['as' => 'subject-list', 'uses' => 'ResourceController@subject']);
    Route::get('subject', ['as' => 'subject-list', 'uses' => 'ResourceController@subject']);
    Route::get('subjectadd', array('as' => 'subject-add', 'uses'=>'ResourceController@subjectadd'));
    Route::get('subjectedit/{id}', array('as' => 'subject-edit', 'uses'=>'ResourceController@subjectedit'));
    Route::get('subjectdel/{id}', array('as' => 'subject-delete', 'uses'=>'ResourceController@subjectdelete'));
    Route::post('subjectupdate', array('as' => 'subject-update', 'uses'=>'ResourceController@subjectupdate'));

    Route::get('lesson', array('as' => 'lesson-list', 'uses'=>'ResourceController@lesson'));
	Route::get('lessonadd', array('as' => 'lesson-add', 'uses'=>'ResourceController@lessonadd'));
    Route::get('lessonedit/{id}', array('as' => 'lesson-edit', 'uses'=>'ResourceController@lessonedit'));
    Route::get('lessondel/{id}', array('as' => 'lesson-delete', 'uses'=>'ResourceController@lessondelete'));
    Route::post('lessonupdate', array('as' => 'lesson-update', 'uses'=>'ResourceController@lessonupdate'));

    Route::get('category', array('as' => 'category-list', 'uses'=>'ResourceController@category'));
    Route::get('categoryadd', array('as' => 'category-add', 'uses'=>'ResourceController@categoryadd'));
    Route::get('categoryedit/{id}', array('as' => 'category-edit', 'uses'=>'ResourceController@categoryedit'));
    Route::get('categorydel/{id}', array('as' => 'category-delete', 'uses'=>'ResourceController@categorydelete'));
    Route::post('categoryupdate', array('as' => 'category-update', 'uses'=>'ResourceController@categoryupdate'));

    Route::get('passage', array('as' => 'list', 'uses'=>'PassageController@passage'));
    Route::get('passageadd', array('as' => 'add', 'uses'=>'PassageController@passageadd'));
    Route::get('passageedit/{id}', array('as' => 'edit', 'uses'=>'PassageController@passageedit'));
    Route::get('passagedel/{id}', array('as' => 'delete', 'uses'=>'PassageController@passagedelete'));
    Route::post('passageupdate', array('as' => 'update', 'uses'=>'PassageController@passageupdate'));
});