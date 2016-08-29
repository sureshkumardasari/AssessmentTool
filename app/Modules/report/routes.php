<?php

    Route::group(array('module'=>'report', 'prefix' => 'report', 'middleware' => 'auth',
    'namespace' => 'App\Modules\Report\Controllers'), function() {


    Route::get('/',['as'=>'report','uses'=>'ReportController@view']);
    Route::get('class_average_and_student_scores_report',['as'=>'assessmentreport','uses'=>'ReportController@class_average_and_student_scores_report']);

    Route::get('assignment',['as'=>'assignmentreport','uses'=>'ReportController@assignment']);
        Route::get('report123',['as'=>'report123','uses'=>'ReportController@assignmentdash']);
       /* Route::get('report1234',['as'=>'report1234','uses'=>'ReportController@assessmentdash']);*/

    Route::get('student',['as'=>'studentreport','uses'=>'ReportController@student']);
    Route::get('answer',['as'=>'answerreport','uses'=>'ReportController@answer']);
    Route::post('assignment_inst/{id}',['as'=>'assignreport','uses'=>'ReportController@report_inst']);
    Route::post('assignment_qstn/{id}',['as'=>'instreport','uses'=>'ReportController@assignments']);
    Route::post('assignment_inst/{inst_id}/{assi_id}',['as'=>'reportassignment','uses'=>'ReportController@report_assignment']);
    //student-answer-report
    Route::get('student-answer-report',['as'=>'student-answer-report','uses'=>'ReportController@studentAnswerReport']);
    Route::post('students_ans_list/{inst_id}/{assign_id}/{student_id}','ReportController@studentAnsList');
    //close student-answer-report
     Route::post('assessment_inst/{assignment_id}','ReportController@assess_inst');
     Route::post('assessment_inst/{inst_id}/{assi_id}',['as'=>'reportassessment','uses'=>'ReportController@class_average_and_student_scores']);

     Route::post('students_inst/{id}','ReportController@student_inst');
     Route::post('assignmt_inst/{id}','ReportController@assignmtInst');
      Route::post('student_assignmt_inst/{inst_id}/{assign_id}','ReportController@stuentsAssignmtInst');
     Route::post('students_inst/{inst_id}/{student_id}','ReportController@inst_student');

     Route::post('inst_question/{id}','ReportController@inst_question');
        Route::get('download',['as'=>'reportdownload','uses'=>'ReportController@getDownload']);
        Route::get('test-history',['as'=>'test-history','uses'=>'ReportController@TestHistoryReport']);
        Route::post('test_history/{id}','ReportController@TestHistory');
	  Route::post('assignment_qstn/{inst_id}/{assi_id}/{sub_id}',['as'=>'reportassignment','uses'=>'ReportController@report_questions']);
        Route::get('QuestionsexportPDF/{inst_id}/{assi_id}/{sub_id}',['as'=>'reportdownload','uses'=>'ReportController@QuestionsexportPDF']);
	   Route::get('exportPDF/{inst_id}/{assi_id}',['as'=>'reportdownload','uses'=>'ReportController@exportPDF']);
        Route::post('assignment_subjects/{inst_id}-{assi_id}',['as'=>'reportassignment','uses'=>'ReportController@subjects_list']);

        Route::get('wholeclass',['as'=>'wholeclass','uses'=>'ReportController@wholeclassscorereport']);
        Route::post('assignment_wholeclass/{id}','ReportController@report_inst');
        Route::post('assignment_subject/{id}','ReportController@subject_change');
        Route::post('assignment_inst/{id}',['as'=>'instreport','uses'=>'ReportController@report_inst']);
        Route::post('assignment_wholeclass/{inst_id}/{assi_id}/{sub_id}',['as'=>'wholeclassreport','uses'=>'ReportController@report_wholeclass']);
        Route::get('wholeclasstile',['as'=>'wholeclasstile','uses'=>'ReportController@dashboardwholeclass']);


        Route::get('dashboard',['as'=>'Dashboard','uses'=>'ReportController@dashboard']);
        Route::get('studentquestionteacher',['as'=>'studentquestionteacher','uses'=>'ReportController@sqt']);
        Route::get('testhistorytile',['as'=>'testhistorytile','uses'=>'ReportController@dashboard1']);

        Route::get('leastscore',['as'=>'leastscorereport','uses'=>'ReportController@leastscore']);


        Route::get('SAR_PDF/{inst_id}/{assi_id}/{std_id}',['as'=>'reportdownload','uses'=>'ReportController@SAR_pdf']);

    });


