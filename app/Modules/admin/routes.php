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
    
    Route::get('/', ['as' => 'userlist', 'uses' => 'UserController@index']);
    Route::get('profile', array('as' => 'profile', 'uses'=>'UserController@edit'));
    Route::get('add', array('as' => 'useradd', 'uses'=>'UserController@add'));
    Route::get('edit/{id}', array('as' => 'useredit', 'uses'=>'UserController@edit'));
    Route::get('del/{id}', array('as' => 'userdelete', 'uses'=>'UserController@delete'));
    Route::post('update', array('as' => 'userupdate', 'uses'=>'UserController@update'));
    Route::get('search', array('as' => 'usersearch', 'uses'=>'UserController@searchByInstitution'));
    Route::get('userBulkUpload', array('as' => 'userBulkUpload', 'uses'=>'UserController@userBulkUpload'));
    Route::get('bulkusertemplate', array('as'=>'bulkUserTemplate','uses'=>'UserController@bulkUserTemplate'));
    Route::post('bulkuserupload', array('as'=>'bulkUserUpload','uses'=>'UserController@bulkUserUpload'));
    Route::get('downloadExcelforusers/{type}', 'UserController@downloadExcel');
    Route::get('download', array('as' => 'download', 'uses'=>'UserController@download'));

    Route::post('upload_image', array('as' => 'upload_image', 'uses' => 'UserController@uploadImage'));
    Route::post('save_crop', array('as' => 'save_crop', 'uses' => 'UserController@saveCrop'));
    Route::get('usersjson', array('as'=>'usersjson','uses'=>'UserController@usersJson'));
    //Route::get('usersjson/{id}', array('as'=>'usersjson','uses'=>'UserController@usersJson'));
    
    Route::get('institution', array('as' => 'institution-list', 'uses'=>'InstitutionController@index'));
	Route::get('institutionadd', array('as' => 'institution-add', 'uses'=>'InstitutionController@add'));
    Route::get('institutionedit/{id}', array('as' => 'institution-edit', 'uses'=>'InstitutionController@edit'));
    Route::get('institutiondel/{id}', array('as' => 'institution-delete', 'uses'=>'InstitutionController@delete'));
    Route::post('institutionupdate', array('as' => 'institution-update', 'uses'=>'InstitutionController@update')); 

    Route::get('role', array('as' => 'role-list', 'uses'=>'UserController@roleslist'));
    Route::get('roleadd', array('as' => 'role-add', 'uses'=>'UserController@roleadd'));
    Route::get('roleedit/{id}', array('as' => 'role-edit', 'uses'=>'UserController@roleedit'));
    Route::get('roledel/{id}', array('as' => 'role-delete', 'uses'=>'UserController@roledelete'));
    Route::post('roleupdate', array('as' => 'role-update', 'uses'=>'UserController@roleupdate'));





    Route::get('branding/brandview', array('as'=>'brandview','uses'=>'BrandingController@display'));
    Route::post('branding/brandcreate','BrandingController@create');
    Route::get('branding/brand','BrandingController@index');
    Route::get('{id}/edit','BrandingController@edit');
    Route::get('delete/{id}','BrandingController@destroy');
    Route::post('update/{id}','BrandingController@update');

    Route::post('add-category','BrandingController@store');


});