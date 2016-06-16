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

Route::group(array('module'=>'assessment', 'prefix' => 'assessment', 'middleware' => 'auth', 'namespace' => 'App\Modules\Assessment\Controllers'), function() {
    
    Route::get('/', array('as' => 'myallassignment', 'uses'=>'AssessmentAssignmentController@myassignment'));
    Route::get('myassignment/', array('as' => 'myassignment', 'uses'=>'AssessmentAssignmentController@myassignment'));
    Route::get('testinstructions/{id}', ['as' => 'tests-instructions', 'uses' => 'AssessmentAssignmentController@getTestInstructions']);
    Route::get('testdetail/{assess_assign_id}', ['as' => 'tests-detail', 'uses' => 'AssessmentAssignmentController@testDetail']);
    Route::post('update-test-time', ['as' => 'update-test-time', 'uses' => 'AssessmentAssignmentController@updateTestTime']);
    Route::post('save-answer', ['as' => 'save-answer', 'uses' => 'AssessmentAssignmentController@saveAnswer']);
    Route::get('essay-popuop/{subSecQuestionId}/{questionId}', ['as' => 'essay-popuop', 'uses' => 'AssessmentAssignmentController@openEssayPopuop']);
    Route::get('submit-confirm-popup/{id}', ['as' => 'submit-confirm-popup', 'uses' => 'AssessmentAssignmentController@openSubmitConfirmPopuop']);
    Route::post('submit-test', ['as' => 'submit-test', 'uses' => 'AssessmentAssignmentController@submitTest']);
    Route::get('assignmentstatus',['as'=>'assignmentstatus','uses'=>'AssessmentAssignmentController@assignmentstatus']);

});