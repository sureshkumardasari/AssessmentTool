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
    Route::get('subjectsearch', array('as' => 'subject-search', 'uses'=>'ResourceController@subjectsearch'));
    Route::get('getsubject', array('as' => 'getsubject', 'uses'=>'ResourceController@getsubject'));

    Route::get('lesson', array('as' => 'lesson-list', 'uses'=>'ResourceController@lesson'));
    Route::get('lessonadd', array('as' => 'lesson-add', 'uses'=>'ResourceController@lessonadd'));
    Route::get('lessonedit/{id}', array('as' => 'lesson-edit', 'uses'=>'ResourceController@lessonedit'));
    Route::get('lessondel/{id}', array('as' => 'lesson-delete', 'uses'=>'ResourceController@lessondelete'));
    Route::post('lessonupdate', array('as' => 'lesson-update', 'uses'=>'ResourceController@lessonupdate'));
    Route::get('lessonsearch', array('as' => 'lesson-search', 'uses'=>'ResourceController@lessonsearch'));
    Route::get('getlesson', array('as' => 'getlesson', 'uses'=>'ResourceController@getlesson'));

    Route::get('category', array('as' => 'category-list', 'uses'=>'ResourceController@category'));
    Route::get('categoryadd', array('as' => 'category-add', 'uses'=>'ResourceController@categoryadd'));
    Route::get('categoryedit/{id}', array('as' => 'category-edit', 'uses'=>'ResourceController@categoryedit'));
    Route::get('categorydel/{id}', array('as' => 'category-delete', 'uses'=>'ResourceController@categorydelete'));
    Route::post('categoryupdate', array('as' => 'category-update', 'uses'=>'ResourceController@categoryupdate'));
    Route::get('categorysearch', array('as' => 'category-search', 'uses'=>'ResourceController@categorysearch'));
    Route::get('getcategory', array('as' => 'getcategory', 'uses'=>'ResourceController@getcategory'));

    Route::get('passage', array('as' => 'passage-list', 'uses'=>'PassageController@passage'));
    Route::get('passageadd', array('as' => 'passage-add', 'uses'=>'PassageController@passageadd'));
    Route::get('passageview/{id}', array('as' => 'view', 'uses'=>'PassageController@passageview'));
    Route::get('passageedit/{id}', array('as' => 'passage-edit', 'uses'=>'PassageController@passageedit'));
    Route::get('passagedel/{id}', array('as' => 'delete', 'uses'=>'PassageController@passagedelete'));
    Route::post('passageupdate', array('as' => 'update', 'uses'=>'PassageController@passageupdate'));
    Route::get('view/passage/{id?}', ['as' => 'viewPassage', 'uses' => 'PassageController@view']);

    //question
    Route::get('question', array('as' => 'question-list', 'uses'=>'QuestionController@question'));
    Route::get('questionadd', array('as' => 'question-add', 'uses'=>'QuestionController@questionadd'));
    Route::get('questionview/{id}', array('as' => 'edit', 'uses'=>'QuestionController@questionview'));
    Route::get('questionedit/{id}', array('as' => 'question-edit', 'uses'=>'QuestionController@questionedit'));
    Route::get('questiondel/{id}', array('as' => 'delete', 'uses'=>'QuestionController@questiondelete'));
    Route::get('launch-file-browser/{backet}', array('as' => 'launchFileBrowser', 'uses'=>'QuestionController@launchFileBrowser'));
    Route::post('file-browser-upload-file', array('as' => 'fileBrowserUploadFile', 'uses' => 'QuestionController@fileBrowserUploadFile'));
    Route::post('questionupdate', array('as' => 'update', 'uses'=>'QuestionController@questionupdate'));
    Route::post('question_update_submit/', array('as' => 'update', 'uses'=>'QuestionController@questionSubmit'));
    Route::post('filter_data_question', ['as' => 'filter_data_question', 'uses' => 'QuestionController@questionFilter']);

    Route::post('categoryList/{id}', array('as' => 'categoryList', 'uses'=>'QuestionController@categoryList'));
    Route::post('subjectList/{id}', array('as' => 'subjectList', 'uses'=>'QuestionController@subjectList'));
    Route::post('lessonsList/{id}', array('as' => 'lessonsList', 'uses'=>'QuestionController@lessonsList'));
    Route::post('questiontypeList/{id}', array('as' => 'questiontypeList', 'uses'=>'QuestionController@questiontype'));
    //assessmentst
    Route::get('assessment', array('as' => 'assessment-list', 'uses'=>'AssessmentController@index'));
    Route::get('assessmentcreate', array('as' => 'assessment-create', 'uses'=>'AssessmentController@assessmentcreate'));
    Route::get('assessmentview/{id}', array('as' => 'assessmentview', 'uses'=>'AssessmentController@assessmentview'));
    Route::get('assessmentpdf/{id}', array('as' => 'assessmentpdf', 'uses'=>'AssessmentController@assessmentpdf'));
    Route::get('template/{id}/{tplId?}', array('as' => 'template', 'uses'=>'AssessmentController@getTemplate'));
    Route::any('save-pdf', array('as' => 'savePdf', 'uses' => 'AssessmentController@savePdf'));
    Route::post('save-print-online-view', 'AssessmentController@savePrintOnlineView');

    Route::get('pdftest', array('as' => 'pdftest', 'uses'=>'AssessmentController@pdftest'));
    Route::get('assessmentedit/{id}', array('as' => 'assessmentedit', 'uses'=>'AssessmentController@assessmentedit'));
    Route::post('assessmentupdate/', array('as' => 'assessmentupdate', 'uses'=>'AssessmentController@assessmentupdate'));
    Route::get('assessmentdel/{id}', array('as' => 'assessmentdel', 'uses'=>'AssessmentController@assessmentdel'));
    Route::post('assessmentinsert', array('as' => 'assessmentinsert', 'uses'=>'AssessmentController@assessmentInsert'));
    Route::post('question/listing', ['as' => 'ajax-question-listing', 'uses' => 'AssessmentController@questionsListing']);
    Route::post('passage/listing', ['as' => 'ajax-question-listing', 'uses' => 'AssessmentController@passageListing']);
    Route::post('filter_data_assessment', ['as' => 'filter_data_assessment', 'uses' => 'AssessmentController@assessmentFilter']);
    Route::post('get_assessment_qst', ['as' => 'get_assessment_qst', 'uses' => 'AssessmentController@assessmentQst']);
    Route::post('get_assessmentold_pass', ['as' => 'get_assessmentold_pass', 'uses' => 'AssessmentController@assessmentOldPassage']);
    Route::post('get_assessment_remove_old_pass', ['as' => 'get_assessment_remove_old_pass', 'uses' => 'AssessmentController@assessmentRemoveOldPassage']);
    Route::post('get_assessment_append_qst', ['as' => 'get_assessment_append_qst', 'uses' => 'AssessmentController@assessmentAppendQst']);
    Route::post('get_qestion_passage', ['as' => 'get_qestion_passage', 'uses' => 'AssessmentController@assessmentQstPassage']);
    Route::post('filter_data_assessment_list', ['as' => 'filter_data_assessment_list', 'uses' => 'AssessmentController@assessmentFilterList']);
    Route::get('download_zip/{assessmentId}', ['as' => 'zipDownload', 'uses' => 'AssessmentController@zipDownload']);
    Route::get('get-print-answer-key-csv', ['as' => 'getPrintAnswerKeyCSV', 'uses' => 'AssessmentController@getPrintAnswerKeyCSV']);
    Route::post('get_passage_by_question', ['as' => 'get_passage_by_question', 'uses' => 'AssessmentController@getPassageByQuestion']);
    Route::post('remove_passage_by_passid', ['as' => 'remove_passage_by_passid', 'uses' => 'AssessmentController@getPassageByPassId']);
    Route::post('passage_filter_data_assessment', ['as' => 'passage_filter_data_assessment', 'uses' => 'AssessmentController@passageAssessmentFilter']);

    Route::get('assignment', array('as' => 'assignment-list', 'uses'=>'AssignmentController@assignment'));
    Route::get('assignmentadd', array('as' => 'assignment-add', 'uses'=>'AssignmentController@assignmentadd'));
    Route::get('assignmentedit/{id}', array('as' => 'assignment-edit', 'uses'=>'AssignmentController@assignmentedit'));
    Route::get('assignmentdel/{id}', array('as' => 'delete', 'uses'=>'AssignmentController@assignmentdelete'));
    Route::get('assignmentview/{id}', array('as' => 'view', 'uses'=>'AssignmentController@assignmentview'));
    Route::post('assignmentupdate', array('as' => 'update', 'uses'=>'AssignmentController@assignmentupdate'));
    Route::get('view/assignment/{id?}', ['as' => 'viewAssignment', 'uses' => 'AssignmentController@view']);
    Route::get('assignedusersjson', array('as'=>'assignedusersjson','uses'=>'AssignmentController@getAssignUsersInfo'));
    Route::get('unassignedusersjson', array('as'=>'unassignedusersjson','uses'=>'AssignmentController@getunAssignUsersInfo'));
});