<?php

    Route::group(array('module'=>'report', 'prefix' => 'report', 'middleware' => 'auth',
    'namespace' => 'App\Modules\Report\Controllers'), function() {


    Route::get('/',['as'=>'report','uses'=>'ReportController@view']);
    Route::get('assessment',['as'=>'report','uses'=>'ReportController@scores']);

    Route::get('assignment',['as'=>'report','uses'=>'ReportController@assignment']);

    Route::get('student',['as'=>'report','uses'=>'ReportController@student']);
    Route::get('answer',['as'=>'report','uses'=>'ReportController@answer']);
    Route::post('assignment_inst/{id}',['as'=>'report','uses'=>'ReportController@report_inst']);
    Route::post('assignment_inst/{inst_id}/{assi_id}',['as'=>'report','uses'=>'ReportController@report_assignment']);

     Route::post('assessment_inst/{id}',['as'=>'report','uses'=>'ReportController@assess_inst']);
     Route::post('assessment_inst/{inst_id}/{assi_id}',['as'=>'report','uses'=>'ReportController@report_assessment']);
});


