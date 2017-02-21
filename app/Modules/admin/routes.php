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
    Route::get('state/{id}', array('as' => 'useredit', 'uses'=>'UserController@state'));
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
    Route::get('institutionBulkUpload', array('as' => 'InstitutionBulkUpload','uses'=>'InstitutionController@InstitutionsBulkUpload'));
    Route::get('bulkinstitutionTemplate',array('as'=> 'bulkInstitutionTemplate','uses'=>'InstitutionController@bulkInstitutionTemplate'));
    Route::post('bulkinstitutionUpload', array('as'=>'bulkInstitutionUpload','uses'=>'InstitutionController@bulkInstitutionUpload'));


    Route::get('role', array('as' => 'role-list', 'uses'=>'UserController@roleslist'));
    Route::get('roleadd', array('as' => 'role-add', 'uses'=>'UserController@roleadd'));
    Route::get('roleedit/{id}', array('as' => 'role-edit', 'uses'=>'UserController@roleedit'));
    Route::get('roledel/{id}', array('as' => 'role-delete', 'uses'=>'UserController@roledelete'));
    Route::post('roleupdate', array('as' => 'role-update', 'uses'=>'UserController@roleupdate'));

    Route::get('brandings', array('as'=>'branding-view','uses'=>'BrandingController@display'));
    Route::get('brandingadd', ['as' => 'branding-add', 'uses' => 'BrandingController@add']);
    Route::post('brandingcreate', ['as' => 'branding-create', 'uses' => 'BrandingController@create']);
    Route::get('brandingedit/{id}', ['as' => 'branding-edit', 'uses' => 'BrandingController@edit']);
    Route::get('brandingdel/{id}', ['as' => 'branding-del', 'uses' => 'BrandingController@delete']);
    Route::post('brandingupdate/{id}', ['as' => 'branding-update', 'uses' => 'BrandingController@update']);
        Route::get('users_list/{type}','UserController@index');

});