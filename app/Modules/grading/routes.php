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

Route::group(array('module'=>'grading', 'prefix' => 'grading', 'middleware' => 'auth', 'namespace' => 'App\Modules\Grading\Controllers'), function() {
    
    Route::get('/', array('as' => 'allassignment', 'uses'=>'GradingController@assignment'));

    Route::get('list-student/{id}-{assessment_id}', ['as' => 'studentGrading', 'uses' => 'GradingController@studentGradeListing']);
    Route::post('list-student-ajax/{id}', ['as' => 'studentGradingAjax', 'uses' => 'GradingController@studentGradeListingAjax']);
    Route::get('list-student-question/{id}-{assignment_id}-{assessment_id}', ['as' => 'studentQuestion', 'uses' => 'GradingController@studentQuestionList']);
//    Route::get('student-inner-grade/{id}', ['as' => 'studentGradingInner', 'uses' => 'GradingController@studentGradingInner']);

    Route::get('list-question/{id}', ['as' => 'questionGrading', 'uses' => 'GradingController@questionGradeListing']);

    Route::post('grade-question/save_answer_for_student_by_question_grade/{question_Id}','GradingController@saveAnswerByQuestionGrade');

    Route::get('grade-question/next_student_answers_for_grade_by_question/{user_id}/{question_id}','GradingController@nextStudentAnswersForQuestionGrade');

    Route::get('grade-question/{id}', ['as' => 'questiongrade', 'uses' => 'GradingController@questionGrade']);
    Route::post('list-student-question/essay_grading_submit/{assessment_id}/{assignment_id}/{user_id}',["uses"=>"GradingController@submit_essay_score"]);
    Route::post('list-student-question/save_answer_for_student_by_student_grade/{assessment_id}/{assignment_id}','GradingController@save_student_answers_by_gradeByStudentMethod');
    Route::get('list-student-question/manual_grade/{assessment_id}/{assignment_id}/{user_id}','GradingController@manualGrade');
    Route::get('list-student-question/{assessment_id}/{assignment_id}/{user_id}','GradingController@studentAnswers');
});
