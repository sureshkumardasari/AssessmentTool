<?php

    Route::group(array('module'=>'report', 'prefix' => 'report', 'middleware' => 'auth',
    'namespace' => 'App\Modules\Report\Controllers'), function() {


    Route::get('/',['as'=>'report','uses'=>'ReportController@view']);
    Route::get('assessment',['as'=>'assessmentreport','uses'=>'ReportController@scores']);

    Route::get('assignment',['as'=>'assignmentreport','uses'=>'ReportController@assignment']);

    Route::get('student',['as'=>'studentreport','uses'=>'ReportController@student']);
    Route::get('answer',['as'=>'answerreport','uses'=>'ReportController@answer']);
    Route::post('assignment_inst/{id}',['as'=>'assignreport','uses'=>'ReportController@report_inst']);
    Route::post('assignment_inst/{id}',['as'=>'instreport','uses'=>'ReportController@report_inst']);
    Route::post('assignment_inst/{inst_id}/{assi_id}',['as'=>'reportassignment','uses'=>'ReportController@report_assignment']);

     Route::post('assessment_inst/{id}','ReportController@assess_inst');
     Route::post('assessment_inst/{inst_id}/{assi_id}',['as'=>'reportassessment','uses'=>'ReportController@report_assessment']);

     Route::post('students_inst/{id}','ReportController@student_inst');
     Route::post('students_inst/{inst_id}/{student_id}','ReportController@inst_student');

     Route::post('inst_question/{id}','ReportController@inst_question');
        Route::get('download',['as'=>'reportdownload','uses'=>'ReportController@getDownload']);
        Route::get('test-history',['as'=>'test-history','uses'=>'ReportController@TestHistoryReport']);
        Route::post('test_history/{id}','ReportController@TestHistory');

 });


