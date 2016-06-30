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

/*Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');*/

Route::get('/', array('as'=>'/','uses'=>'WelcomeController@index'));

Route::get('home', array('as'=>'home', 'uses' => 'HomeController@index'));

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
//-----------------------proctor launch assignments------------
Route::get('proctor_dashboard',array('as'=>'proctordashboard','uses'=>'ProctorDashboard@index'));
Route::get('launch_test_by_proctor/set_instructions/{assignment_id}','ProctorDashboard@launchAssgnmentSetInstructions');
Route::post('launch_test_by_proctor/start_test/update_status','ProctorDashboard@updateStatus');
Route::get('launch_test_by_proctor/start_test/{assignment_id}',array('as'=>'start_test_by_proctor','uses'=>'ProctorDashboard@startTest'));
//-----------------------proctor launch assignments------------

Route::get('temp/dir','TestController@Autocreatedir');
Route::get('temp/errorlog','TestController@errorlog'); 
