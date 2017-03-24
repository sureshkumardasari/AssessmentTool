<?php

use Zizaco\Entrust\EntrustFacade;
use Zizaco\Entrust\Entrust;
//use \App\Modules\Accounts\Models\Institution;
//use \DB;
use mikehaertl\wkhtmlto\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;

/**
 * Generates random bullets based on passed $type
 *
 * @param $type string 'default' || 'alternate'
 * @return array of bullets
 *
 */
function generateBullets($type) {

    $ansOptions = [];
    // Index 0 - Represents the even question mapping
    // Index 1 - Represents the odd question mapping
    if (strtolower($type) == 'alternate') {
        $ansOptions = [
            ['A', 'B', 'C', 'D', 'E'],
            ['F', 'G', 'H', 'J', 'K']
        ];
    } else {
        $ansOptions = [
            ['A', 'B', 'C', 'D', 'E'],
            ['A', 'B', 'C', 'D', 'E']
        ];
    }

    return $ansOptions;
}

function multiKeyExists(Array $array, $key) {
    if (array_key_exists($key, $array)) {
        return true;
    }
    foreach ($array as $k => $v) {
        if (!is_array($v)) {
            continue;
        }
        if (array_key_exists($key, $v)) {
            return true;
        }
    }
    return false;
}
function swapValue($value) {
    if ($value == "No Response" || $value == "no-response") {
        return "Open";
    } else if ($value == "correct") {
        return "Yes";
    } else {
        return "No";
    }
}

/**
 * breadcrumb()
 * The purpose of this method is to make breadcrumb interactive.
 * This method is being used in main layout.
 *
 * Note: Every developer has to add relevant breadcrumb data in the format defined in method
 *
 */
function breadcrumb($displayName = '', $urlSlug = '') {
    // Default breadcrumb, refering to home.
    $breadcrumbLinks = array('displayName' => 'Home', 'route' => 'home');
    // Note: Every developer has to add relevant breadcrumb data in the format defined below.
    // Standard array structure to fill in array for all possible breadcrumb info.

    $current_params = Route::current();
    if(is_null($current_params)){
        echo view('breadcrumb', compact('breadcrumbLinks'));
        return;
    }
    $current_params = $current_params->parameters();
         //dd($current_params);
    $links = array(
        //------------------users---------------------
        // 'mainhome' => array(
        //     'displayName' => 'Dashboard',
        //     'route' => 'userlist',
        //     'postfix' => array('mainhome' => 'Home'), //, 'userlist' => 'Users'
        // ),
        'userlist' => array(
            'displayName' => 'Users',
            'route' => 'userlist',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration'), //, 'userlist' => 'Users'
        ),
        'profile' => array(
            'displayName' => 'Profile',
            'route' => 'profile',
            'postfix' => array('mainhome' => 'Home'), 
        ),
        'useradd' => array(
            'displayName' => 'Create User',
            'route' => 'useradd',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'userlist' => 'User'), //
        ),
        'useredit' => array(
            'displayName' => 'Modify User',
            'route' => 'useredit',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'userlist' => 'User'), //
        ),
        //--------institutions-------------
        'institution-list' => array(
            'displayName' => 'Institutions',
            'route' => 'institution-list',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration'), //
        ),
        'institution-add' => array(
            'displayName' => 'Create Institution',
            'route' => 'institution-add',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'institution-list' => 'Institutions'), //
        ),
        'institution-edit' => array(
            'displayName' => 'Modify Institution',
            'route' => 'institution-edit',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'institution-list' => 'Institutions'), //
        ),
        //------------roles-------------------
        'role-list' => array(
            'displayName' => 'Roles',
            'route' => 'role-list',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration'), //
        ),
        'role-add' => array(
            'displayName' => 'Create Role',
            'route' => 'role-add',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'role-list' => 'Roles'), //
        ),
        'role-edit' => array(
            'displayName' => 'Modify Role',
            'route' => 'role-edit',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'role-list' => 'Roles'), //
        ),
        //---------------branding ----------------
         'branding-view' => array(
            'displayName' => 'Branding',
            'route' => 'branding-view',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration'), //
        ),
        'branding-add' => array(
            'displayName' => 'Create Branding',
            'route' => 'branding-add',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'branding-view' => 'Branding'), //
        ),
        'branding-edit' => array(
            'displayName' => 'Modify Branding',
            'route' => 'branding-edit',
            'postfix' => array('mainhome' => 'Home', 'branding-view' => 'Administration', 'branding-view' => 'Branding'), //
        ),
        //------------category--------------------        
        'category-list' => array(
            'displayName' => 'Category',
            'route' => 'category-list',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources'), //
        ),
        'category-add' => array(
            'displayName' => 'Create Category',
            'route' => 'category-add',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'category-list' => 'Category'), //
        ),
        'category-edit' => array(
            'displayName' => 'Modify Category',
            'route' => 'category-edit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'category-list' => 'Category'), //
        ),
        //------------subject--------------------   
        'subject-list' => array(
            'displayName' => 'Subjects',
            'route' => 'subject-list',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources'), //
        ),
        'subject-add' => array(
            'displayName' => 'Create Subject',
            'route' => 'subject-add',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'subject-list' => 'Subjects'), //
        ),
        'subject-edit' => array(
            'displayName' => 'Modify Subject',
            'route' => 'subject-edit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'subject-list' => 'Subjects'), //
        ),
        ////------------lesson--------------------   
        'lesson-list' => array(
            'displayName' => 'Lessons',
            'route' => 'lesson-list',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources'), //
        ),
        'lesson-add' => array(
            'displayName' => 'Create Lesson',
            'route' => 'lesson-add',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'lesson-list' => 'Lessons'), //
        ),
        'lesson-edit' => array(
            'displayName' => 'Modify Lesson',
            'route' => 'lesson-edit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'lesson-list' => 'Lessons'), //
        ),
        //------------questions--------------------   
    
        'question-list' => array(
            'displayName' => 'Questions',
            'route' => 'question-list',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources'), //
        ),
        'question-add' => array(
            'displayName' => 'Create Questions',
            'route' => 'question-add',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'question-list' => 'Questions'), //
        ),
        'question-edit' => array(
            'displayName' => 'Modify Questions',
            'route' => 'question-edit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'question-list' => 'Questions'), //
        ),
        'edit' => array(
            'displayName' => 'Questions View',
            'route' => 'edit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'question-list' => 'Questions'), //
        ),
        //------------passages--------------------   
         'passage-list' => array(
            'displayName' => 'Passages',
            'route' => 'passage-list',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources'), //
        ),
        'passage-add' => array(
            'displayName' => 'Create Passage',
            'route' => 'passage-add',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'passage-list' => 'Passages'), //
        ),
        'passage-edit' => array(
            'displayName' => 'Modify Passage',
            'route' => 'passage-edit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'passage-list' => 'Passages'), //
        ),
        'passageview' => array(
            'displayName' => 'Passage View',
            'route' => 'passageview',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'passage-list' => 'Passages'), //
        ),
         //------------assessments--------------------   
         'assessmentlist' => array(
            'displayName' => 'Assessments',
            'route' => 'assessmentlist',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources'), //
        ),
        'assessment-create' => array(
            'displayName' => 'Create Assessment',
            'route' => 'assessment-create',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'assessmentlist' => 'Assessments'), //
        ),
        'assessmentedit' => array(
            'displayName' => 'Modify Assessment',
            'route' => 'assessmentedit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'assessmentlist' => 'Assessments'), //
        ),
         'assessmentview' => array(
            'displayName' => 'Assessment View',
            'route' => 'assessmentview',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'assessmentlist' => 'Assessments'), //
        ),
         'template'=> array(
            'displayName' => 'Assessment Save',
            'route' => 'template',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'assessmentlist' => 'Assessments'), //
        ),
         //------------assignments--------------------   
         'assignment-list' => array(
            'displayName' => 'Assignments',
            'route' => 'assignment-list',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources'), //
        ),
        'assignment-add' => array(
            'displayName' => 'Create Assignment',
            'route' => 'assignment-add',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'assignment-list' => 'Assignments'), //
        ),
        'assignment-edit' => array(
            'displayName' => 'Modify Assignment',
            'route' => 'assignment-edit',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'assignment-list' => 'Assignments'), //
        ),
        'assignmentview' => array(
            'displayName' => 'Assignment View',
            'route' => 'assignmentview',
            'postfix' => array('mainhome' => 'Home', 'category-list' => 'Resources', 'assignment-list' => 'Assignments'), //
        ),
         //------------grading-----studentGrading----------questionGrading----------   
         'allassignment' => array(
            'displayName' => 'Grading',
            'route' => 'allassignment',
            'postfix' => array('mainhome' => 'Home'), //
        ),
        'studentGrading' => array(
            'displayName' => 'Grade By Student',
            'route' => 'studentGrading',
            'postfix' => array('mainhome' => 'Home','allassignment' => 'Grading'),//
        ),
         'studentQuestion' => array(
            'displayName' => 'Student Questions',
            'route' => 'studentQuestion',
            'postfix' => array('mainhome' => 'Home','allassignment' => 'Grading', 'studentGrading' => 'Grade By Student'), //
        ),
        'questionGrading' => array(
            'displayName' => 'Grade By Question',
            'route' => 'questionGrading',
            'postfix' => array('mainhome' => 'Home','allassignment' => 'Grading'), //
        ),
         'questiongrade' => array(
            'displayName' => 'Questions Grade',
            'route' => 'questiongrade',
            'postfix' => array('mainhome' => 'Home','allassignment' => 'Grading', 'questionGrading' => 'Grade By Question'), //
        ),
         //------------------reports------------------
         'report' => array(
            'displayName' => 'Reports',
            'route' => 'report',
            'postfix' => array('mainhome' => 'Home'), //
        ),
        'assessmentreport' => array(
            'displayName' => 'Class Average and Student Scores Report',
            'route' => 'assessmentreport',
            'postfix' => array('mainhome' => 'Home','report' => 'Reports'), //
        ),
         'wholeclass' => array(
            'displayName' => 'Most Recent Whole Class Score Report',
            'route' => 'wholeclass',
            'postfix' => array('mainhome' => 'Home','report' => 'Reports'), //
        ),
         'test-history' => array(
            'displayName' => 'Test History  Class Averages',
            'route' => 'test-history',
            'postfix' => array('mainhome' => 'Home','report' => 'Reports'), //
        ),
          'student-answer-report' => array(
            'displayName' => 'Student Answer Report',
            'route' => 'student-answer-report',
            'postfix' => array('mainhome' => 'Home','report' => 'Reports'), //
        ),

         'answerreport' => array(
            'displayName' => 'Question And Answers',
            'route' => 'answerreport',
            'postfix' => array('mainhome' => 'Home','report' => 'Reports'), //
        ),
         
         'leastscorereport' => array(
            'displayName' => 'Least Scores Report',
            'route' => 'leastscorereport',
            'postfix' => array('mainhome' => 'Home','report' => 'Reports'), //
        ),

         //-------------------proctor--dashboard------------------------
         'proctordashboard' => array(
            'displayName' => 'Proctor Dashboard',
            'route' => 'proctordashboard',
            'postfix' => array('mainhome' => 'Home'), //
        ),
         'start_test_by_proctor' => array(
            'displayName' => 'Start Test By Proctor',
            'route' => 'start_test_by_proctor',
            'postfix' =>  array('mainhome' => 'Home', 'proctordashboard' => 'Proctor Dashboard' ),
            ),





         //--------------myassignments------------------
         'myassignment' => array(
            'displayName' => 'My Assignments',
            'route' => 'myassignment',
            'postfix' => array('mainhome' => 'Home'),
            ),
            'tests-instructions' => array(
                'displayName' => 'Instructions',
                'route' =>'tests-instructions',
                'postfix' =>array('mainhome' => 'Home' , 'myassignment' => 'My Assignments'),
                ),

    );
    $currentRoute = Route::currentRouteName();
    if (array_key_exists($currentRoute, $links)) {
        $breadcrumbLinks = $links[$currentRoute];
    }
    //dd($currentRoute);
    echo view('breadcrumb', compact('breadcrumbLinks','current_params','currentRoute'))->__tostring() ;
}

/**
 * getLastQuery | Get Last Query which Run by Laravel Model
 * @return string
 */
function getLastQuery() {
    $queries = DB::getQueryLog();
    $lastQuery = end($queries);
    return $lastQuery;
}

/**
 * Convert 24 Hour time format to 12 Hour time format and return hours. minutes and am/pm separately
 *
 * @param $time24HourFormat string 'time to convert'
 * @return $time            array  '12 hour time format separately'
 *
 */
function convertTimeto12HourFormat($time24HourFormat) {
    return array('hours' => date('h', strtotime($time24HourFormat)),
        'minutes' => date('i', strtotime($time24HourFormat)),
        'am/pm' => date('a', strtotime($time24HourFormat)));
}

/**
 * deleteOldPdfFile:  This function is used to delete speicied old files from directory
 *
 * @param $dir string ($dir = "images/temp/") Directory from where file will going to delete
 * @param $howOld int  optional if file is 24 hours (86400 seconds) old then delete it
 * @return void(0)
 *
 */
function deleteOldPdfFile($dir, $howOld = 86400) {
    /** define the directory * */
    // $dir = "images/temp/";

    /*     * * cycle through all files in the directory ** */
    foreach (glob($dir . "*.pdf") as $file) {

        /*         * * if file is 24 hours (86400 seconds) old then delete it ** */
        $fileTime = filemtime($file);
        $deleteTime = time() - $howOld;
        if ($fileTime < $deleteTime) {
            unlink($file);
        }
    }
}

/**
 * Generates the CSV from the data passed
 * @param  array $data  Array of data which is to be converted to CSV
 * @return Response     CSV file gets downloaded
 */
function downloadCSV($data, $fileName = 'report-data') {

    if (empty($data)) {
        return false;
    }

    $fileName = time().'_'.$fileName;
    $headers = [
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
        , 'Content-type' => 'text/csv'
        , 'Content-Disposition' => 'attachment; filename=' . $fileName . '.csv'
        , 'Expires' => '0'
        , 'Pragma' => 'public'
    ];

    # add headers for each column in the CSV download
    array_unshift($data, array_keys($data[0]));

    // Remove any numbers from the start of the header
    foreach ($data[0] as $key => &$value) {
        $value = preg_replace('/^\d+_/', '', $value);
    }

    $callback = function() use ($data) {
        $FH = fopen('php://output', 'w');
        foreach ($data as $row) {
            fputcsv($FH, $row);
        }
        fclose($FH);
    };

    return \Response::stream($callback, 200, $headers);
}


function storeReportCSV($data, $fileName = 'report-data.csv', $toS3 = false) {

    if (empty($data)) {
        return false;
    }

    # add headers for each column in the CSV download
    array_unshift($data, array_keys($data[0]));

    $fileName = time() . '-' . $fileName;
    $filePath = public_path('data/reports/' . $fileName);

    $handle = fopen($filePath, 'w');
    foreach ($data as $row) {
        fputcsv($handle, $row);
    }

    fclose($handle);

    if ( $toS3 === false ) {

        if (file_exists($filePath) && is_readable($filePath)) {
            return url('data/reports/' . $fileName);
        }

        return false;
    } else {

        $s3 = new \App\Models\S3;
        $url = $s3->uploadByPath( $filePath, 'reports');

        if ( !empty( $url ) && is_array( $url )) {
            unlink($filePath);
            return url('download/aws-file?fileName=' . $fileName . '&directory=reports&dummyUrl=/' . $fileName);

        } else {

            if (file_exists($filePath) && is_readable($filePath)) {
                return url('data/reports/' . $fileName);
            } else {
                return false;
            }

        }

    }
}

function storeCSV($data, $fileName = 'import.csv') {

    if (empty($data)) {
        return false;
    }

    # add headers for each column in the CSV download
    array_unshift($data, array_keys($data[0]));

    $filePath = public_path('data/grades/' . time() . '-' . $fileName);

    $handle = fopen($filePath, 'w');
    foreach ($data as $row) {
        fputcsv($handle, $row);
    }

    fclose($handle);

    if (file_exists($filePath) && is_readable($filePath)) {
        return url('data/grades/' . time() . '-' . $fileName);
    }

    return false;
}

/**
 * createPdfForReport:  This function will generate pdf file and return the cre
 *
 * @param $fileName string name of the file
 * @param $htmlForPdf string name of the file
 * @return $fileFullUrl string
 *
 */
function createPdfForReport($fileName, $htmlForPdfs, $footerHtml = "", $required = "", $defaultMarginBottom = '20mm'){
    ini_set('max_execution_time', 0);
    set_time_limit(0);
    ini_set('memory_limit', -1);

    //****Start Clean Directory
    $dir = public_path('data/reports/');
    if (!file_exists('data/reports/')) {
        mkdir('data/reports/', 0777, true);
    }
    // deleteOldPdfFile($dir);
    //****End Clean Directory
    $options1 = array(
        'encoding' => 'UTF-8', // option with argument
        'page-size' => 'A3', // option with argument
        //'user-style-sheet' => public_path('assets/css/style.css'),
        'use-xserver',
        'commandOptions' => array(
            'enableXvfb' => true,
            'procEnv' => array(
                'LANG' => 'en_US.utf-8',
            ),
            'xvfbRunOptions' => '--auto-servernum',
        ),
        // 'margin-right' => '15mm',
        'margin-bottom' => $defaultMarginBottom,
        // 'margin-left' => '14mm',
        // 'header-spacing' => 15,
        // 'footer-spacing' => 5,
        'disable-smart-shrinking',
        'no-outline'
    );
    $options = array(
            'javascript-delay' => 2000,
            'encoding'         => 'UTF-8',
            'footer-line',
            'footer-font-size' => 10,
            'footer-spacing'   => 10,
            'margin-bottom' => '25mm',
            'header-spacing' => 15,
        );  
    if (!empty($footerHtml)) {
        $options['footer-html'] = $footerHtml;
    }
    $pdf = new Pdf($options);
    $pdf->binary = 'wkhtmltopdf';
    // $pdf = new Pdf(array(
    //         'use-xserver',
    //         'commandOptions' => array(
    //             'enableXvfb' => true,
    //     )));
    //***On some systems you may have to set the binary path.
//    $footer = view('reports::detailpages.pdf-templates.footer')->render();
//    $page_options = [
//        'footer-html' => "".str_replace("\n", ' ', $footer).""
//    ];
    if (is_array($htmlForPdfs)) {
        foreach ($htmlForPdfs as $htmlForPdf) {
            $pdf->addPage($htmlForPdf);
        }
    } else {
        $pdf->addPage($htmlForPdfs);
    }

    $fileName = $fileName . '-' . uniqid() . '-'. time() . '.pdf';
    $fileFullPathWithName = $dir . $fileName;
   // dd($fileFullPathWithName);
    $pdf->saveAs($fileFullPathWithName);
    //dd($pdf);
    $fileFullUrl = url('data/reports/' . $fileName);
   //dd($fileFullUrl);
     //dd($fileFullPathWithName);
    if (file_exists($fileFullPathWithName)) {
        //dd($fileFullPathWithName);
        if (!empty($required)) {
            return $fileName;
        } else {
            return $fileFullUrl;
        }
    } else {
       // dd("else");
        //return 'Error: ' . $pdf->getError();
        return url('data/error.pdf');
    }
}

/**
 * get_group_concat_val | This method is used to group concat the multi vals from collection object
 * @param array $params | ['objArr'=> $objArr, 'propertyName'=>Name, 'separator'=>',', $startHtml='', endHtml = ''];
 * @return mixed string/boolean
 */
function get_group_concat_val($params) {
    $strVal = '';
    $strPropVal = '';
    $objArr = isset($params['objArr']) ? $params['objArr'] : null;
    $propertyName = isset($params['propertyName']) ? $params['propertyName'] : null;
    $separator = isset($params['separator']) ? $params['separator'] : ',';
    $startHtml = isset($params['startHtml']) ? $params['startHtml'] : '';
    $endHtml = isset($params['endHtml']) ? $params['endHtml'] : '';
    try {
        if ($objArr && $propertyName) {
            foreach ($objArr as $o) {
                $propertyNameArr = explode('->', $propertyName);
                if (count($propertyNameArr) > 1) {
                    if ($o->$propertyNameArr[0]) {
                        $strPropVal = $o->$propertyNameArr[0]->$propertyNameArr[1];
                    }
                } else {
                    $strPropVal = $o->$propertyNameArr[0];
                }
                if ($strVal === '') {
                    $strVal = $strPropVal;
                } else {
                    $strVal .= $separator . ' ' . $startHtml . $strPropVal . $endHtml;
                }
            }
        } else {
            return false;
        }
    } catch (\Exception $e) {
        return false;
    }
    return $strVal;
}


/**
 * Converts the CSV to an associative array
 * @param  string $filePath Path of the CSV file.
 * @return array            An associative array
 */
function csvToArray($filePath) {
    if (file_exists($filePath) && is_readable($filePath)) {
//        \Maatwebsite\Excel\Facades\Excel::load($filePath, function($reader) {
//            // ->all() is a wrapper for ->get() and will work the same
//            $results = $reader->all();
//            echo '<pre>'; print_r($results); die;
//
//        });

        $results = \Maatwebsite\Excel\Facades\Excel::load($filePath, function($reader) {

        })->all()->toArray();


        if (isset($results[0]['student_name'])) {
            return $results;
        }

//echo '<pre>'; print_r($results); die;
//        $csv = array_map('str_getcsv', file($filePath));
        // If there are any rows other than header
        if (count($results) > 1) {

//            $header = $results[0];
//            $rowed = [];
//
//            // Reset the headers
//            unset($results[0]);
//
//            // Prepare the associative array
//            foreach ($results as $row) {
//                $rowed[] = array_combine($header, $row);
//            }
//echo '<pre>';print_r($rowed); die();
            return $results;
        }

        return false;
    };

    return false;
}

function str_getcsvWithDelimeter($arg) {
    return str_getcsv($arg, ';');
}

function csvToArrayForSpecificDelimeter($filePath) {
    if (file_exists($filePath) && is_readable($filePath)) {
        $csv = array_map('str_getcsvWithDelimeter', file($filePath));

        // If there are any rows other than header
        if (count($csv) > 1) {

            $header = $csv[0];
            $rowed = [];

            // Reset the headers
            unset($csv[0]);

            // Prepare the associative array
            foreach ($csv as $row) {
                $rowed[] = array_combine($header, $row);
            }

            return $rowed;
        }

        return false;
    };

    return false;
}

/**
 * Removes the array of items from the $data
 *
 * @param  array $data  An array of items from which data is to be removed
 * @param  array $items An array of items that are to be removed
 * @return array        An array with data removed from it.
 */
function array_remove($data, $items) {
    $updatedArray = [];

    foreach ($data as $item) {
        // If it is a question head and not a fixed column
        if (!in_array((string)$item, $items)) {
            $updatedArray[] = $item;
        }
    }

    return $updatedArray;
}

function makeOption($array) {
    $options = '';
    foreach ($array as $key => $value) {
        $options.="<option value=" . $key . ">" . $value . "</option>";
    }
    return $options;
}
/**
 * Export csv
 *
 * @return route $route
 */
function array_to_csv_download($data, $filename = "", $delimiter = ";") {

    if (empty($filename)) {
        $filename = Auth::user()->id . '_' . time() . '.csv';
    }

    header('Content-Encoding: UTF-8');
    header('Content-Type: text/csv;  charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    $file = public_path("data/tmp/" . $filename);

    $output = fopen($file, 'w');
    foreach ($data as $fields) {
        fputcsv($output, $fields);
    }
    fclose($output);

    return "/data/tmp/$filename";
}
/**
 *
 * upca
 * This method handles only upca type barcode and it takes a mandatory param to draw code.
 *
 * @param number $text
 * @param $barHeight Optional parameter with defualt value.
 * @return send the headers and the image
 */
function generateBarcode($text, $barHeight = 30) {

//    echo '/data/barcode-image/barcode.gif';
    $digits = 11;
    //$code = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    $barcodeOptions = array('text' => $text, 'barHeight' => $barHeight, 'factor' => 1.6, 'withBorder' => true, 'withQuietZones' => true);
    $rendererOptions = array('imageType' => 'png');
    $image = Zend\Barcode\Barcode::factory(
        'Upca', 'image', $barcodeOptions, $rendererOptions
    )->draw();
    ob_start();
    imagepng($image);
    $data = ob_get_clean();
    return base64_encode($data);
    // Draw the barcode in a new image,
    // send the headers and the image
}
/**
 * Calculates the average of a single dimensional array values
 * @param  array $arr An array of numbers whose average is to be calculated
 * @return Number|Boolean      Returns thea verage number if possible or false otherwise
 */
function array_average($arr) {
    $score = ( count($arr) === 0 ) ? false : ( array_sum($arr) / count($arr) );

    if ($score !== false) {
        return number_format($score, 2, '.', '');
    }

    return false;
}

function arrayToObject($array) {
    if (!is_array($array)) {
        return $array;
    }

    $object = new stdClass();
    if (is_array($array) && count($array) > 0) {
        foreach ($array as $name => $value) {
            $name = trim($name);
            if (!empty($name)) {
                $object->$name = arrayToObject($value);
            }
        }
        return $object;
    } else {
        return FALSE;
    }
}
function getS3DownloadUrl( $fileName, $directory )
{
    $s3 = new \App\Models\S3;
    return $s3->getDownloadUrl( $fileName, $directory );
}

function s3FileExists( $fileName, $directory )
{
    $s3 = new \App\Models\S3;
    return $s3->fileExists( $fileName, $directory );
}

function getS3ViewUrl( $fileName, $directory )
{
    $s3 = new \App\Models\S3;
    return $s3->getFileUrl( $fileName, $directory );
}

function getRole()
{
    if (Auth::guest()) return '';
    $roles = DB::table('roles')->where('id', '=', Auth::user()->role_id)->select('name')->lists('name');
    return (isset($roles[0]) ) ? $roles[0] : '';
}

function getInstitutionsSelectBox($name = 'institution_id', $id = 'institution_id', $selected = 0, $required = '', $default = 'Select')
{
    $parent_id = 0;
    $sessrole = getRole();

    //$users = User::get();
    $obj = DB::table('institution');
    if ($parent_id > 0) {
        $inst_arr = $obj->groupBy('id')->where("id", $parent_id)->orWhere('parent_id', $parent_id)->lists('name', 'id');
    } else {
        if ($sessrole != 'administrator')
            $obj->where('id', '<>', '1');
        $inst_arr = $obj->groupBy('id')->lists('name', 'id');
    }

    if ($sessrole == 'administrator') {
        $institutions_select = '<div class="form-group required">
                             <label class="col-md-4 control-label">Institution</label>
                            <div class="col-md-6">
                                <select class="form-control" name="' . $name . '" id="' . $id . '">
                                    <option value="0">--' . $default . '--</option>';
        foreach ($inst_arr as $id => $val)
            $institutions_select .= '<option value="' . $id . '" ' . (($id == $selected) ? 'selected = "selected"' : '') . '>' . $val . '</option>';

        $institutions_select .= '</select></div>
                        </div>';
    } else {
        $selected = ($selected > 0) ? $selected : Auth::user()->institution_id;
        $institutions_select = '<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . $selected . '" >';
    }

    echo $institutions_select;
}
function getItemIconClass($name = ''){
    $className = 'unknown-icon';
    if(!empty($name)){
        $office = ['xls', 'xlsx', 'doc', 'docx', 'csv'];
        $images = ['jpg', 'jpeg', 'png', 'bmp', 'gif'];
        $compressed = ['zip', 'gzip', 'rar', 'tar', 'tz', '7zip'];
        $audios = ['wma', 'wav', 'amr', 'mp3'];
        $videos = ['mp4', 'wm4', 'flv', 'mov', '3gp', 'mpeg', 'mpg', '4mpg'];
        $falsh = ['swf', 'fla'];
        $pdf = ['pdf'];
        $nameArr = explode('.', $name);
        $ext = $nameArr[count($nameArr) - 1];
        if(in_array(strtolower($ext), $office)){
            $className = 'office-icon';
        }else if(in_array(strtolower($ext), $images)){
            $className = 'img-icon';
        }else if(in_array(strtolower($ext), $compressed)){
            $className = 'compress-icon';
        }else if(in_array(strtolower($ext), $audios)){
            $className = 'audio-icon';
        }else if(in_array(strtolower($ext), $videos)){
            $className = 'video-icon';
        }else if(in_array(strtolower($ext), $falsh)){
            $className = 'flash-icon';
        }else if(in_array(strtolower($ext), $pdf)){
            $className = 'pdf-icon';
        }
    }
    return $className;
}

/**
 * generateImagesFromPDF | This method is used to generate images from given PDF
 * @param  $pdf_file | PDF_FILE
 * @param  $images_directory | Directory where images will be saved
 * @return void
 */
function generateImagesFromPDF($pdf_file, $images_directory) {
    if (!is_dir($images_directory)) {
        File::makeDirectory($images_directory);
    }
    $images = new Imagick($pdf_file);
    foreach ($images as $i => $image) {
        //$image->setResolution(6600, 5100);
        $image_path = $images_directory . '/' . ($i + 1) . '.jpg';

        $imageWidth = $image->getimagewidth();
        $imageHeight = $image->getimageheight();

        $draw = new ImagickDraw();
        $color = 'red';
        $strokeColor = new ImagickPixel($color);
        $fillColor = new ImagickPixel($color);

        $draw->setStrokeColor($strokeColor);
        $draw->setFillColor($fillColor);
        $draw->setStrokeWidth(1);

        $draw->rectangle(0, 0, $imageWidth, 20);
        $draw->rectangle(0, 0, 20, $imageHeight);
        $draw->rectangle(($imageWidth - 20), 0, $imageWidth, $imageHeight);
        $draw->rectangle(0, $imageHeight - 20, $imageWidth, $imageHeight);

        $image->drawimage($draw);
        $image->transformImage($imageWidth . 'x' . $imageHeight, '6600x5100');
        $image->setresolution(200, 200);

        //$image->setimagecompressionquality(0);


        $image->writeImage($image_path);
    }
}
function generateImagesFromPDFCommandLine($pdf_file, $images_directory) {
    if (!is_dir($images_directory)) {
        File::makeDirectory($images_directory);
    }
    //checking image orientation
    $images = new Imagick($pdf_file);
    foreach ($images as $i => $image) {
        $imageGeometry = $image->getImageGeometry();
        $imageRatio = round($imageGeometry['width']/$imageGeometry['height'],3);
        if($imageRatio != 1.294){
            return ['error' => 'Image Ratio is Not Correct'];
        }
    }

    $imageWidth = 6600;
    $imageHeight = 5100;

    exec('convert -geometry ' . $imageWidth . 'x' . $imageHeight . ' -brightness-contrast -60x80 -blur 0 -density 600x600 -quality 100 -fill white -stroke white -draw "rectangle 0,0,' . $imageWidth . ',40" -draw "rectangle 0,0,40,' . $imageHeight . '" -draw "rectangle ' . ($imageWidth - 40) . ',0,' . $imageWidth . ',' . $imageHeight . '"  -draw "rectangle 0,' . ($imageHeight - 40) . ',' . $imageWidth . ',' . $imageHeight . '"  "' . $pdf_file . '" ' . $images_directory . 'image.jpg');
    unlink($pdf_file);
    return ['success' => 'true'];
}
/**
 * Limit the number of words in a string.
 *
 * @param  string  $value
 * @param  int     $limit
 * @param  string  $end
 * @return string
 */
function limit_words($value, $limit, $end = '...') {
    $words = explode(" ", $value);

    if (count($words) > $limit) {
        return implode(" ", array_splice($words, 0, $limit)) . $end;
    } else {
        return $value;
    }
}

/**
 * Fromats date in following format (Today 12:25 PM, Jan 3:30 PM)
 *
 * @param  string  $value
 * @return string
 */
function msg_date_format($value) {
    $passed_date = date('M j, Y', strtotime($value));
    $today = date('M j, Y');

    if ($passed_date == $today) {
        return "Today " . date('h:i A', strtotime($value));
    } else {
        if (date('Y', strtotime($value)) == date('Y')) {
            return date("M d h:i A", strtotime($value));
        } else {
            return date("M d, Y h:i A", strtotime($value));
        }
    }
}

/**
 * Converts bytes into kilobytes, megabytes, gigabytes *
 * @param  string  $bytes
 * @param  string  $precision
 * @return string
 */
function formatBytes($bytes, $precision = 1) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

/*
 * makeZipFile a function to attach all files into one zip-file and save it into tmp folder and return path of the zip-file
 * @param array $params,
 * @return array $response
 */

function makeZipFile(array $params) {

    $files = (array) $params['files'];
    $s3Bucket = !empty($params['s3_path']) ? $params['s3_path']: 'assessment_fixedform_path';
    $fileBasePath = $params['fileBasePath'];
    $currentUserId = isset($params['currentUserId']) ? $params['currentUserId'] : Auth::user()->id;
    $pathTmp = isset($params['zipPath']) ? $params['zipPath'] : 'data/tmp/';

    $zip = new \ZipArchive();
    $zipFileName = $currentUserId . '_' . time() . ".zip";
    $path = public_path($pathTmp);
    if (!is_dir($path)) {
        @mkdir($path, 0777, true);
    }
    $zipFileNameFullPath = $path . $zipFileName; // Zip name
    $zip->open($zipFileNameFullPath, \ZipArchive::CREATE);
    foreach ($files as $f) {
        $fullPath = $fileBasePath . $f;

        $fullPath = getS3DownloadUrl($f, $s3Bucket);
        $fullPath = preg_replace('/\?.*/', '', $fullPath);

        // if (file_exists($fullPath)) {
        $zip->addFromString(basename($fullPath), file_get_contents($fullPath));
        // } else {
        // $response['errors'][] = "file $f does not exist";
        // }
    }
    $zip->close();
    if (file_exists($zipFileNameFullPath)) {
        $response['success'] = 'Yes';
        $response['zipFileFullPath'] = $zipFileNameFullPath;
    } else {
        $response['errors'] = array('Zip-File was not successfully created.');
    }

    if (isset($params['Download']) && $params['Download'] == true) {

        $filePathInfo = pathinfo($zipFileNameFullPath);
        $fileName = $filePathInfo['basename'];

        // Poooof!!! All done now download that file :)
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-length: " . filesize($zipFileNameFullPath));
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("$zipFileNameFullPath");
    }

    return $response;
}

/*
 * This function Save Email data to Mail Que table..
 *
 */

function sendEmail($subject, $message, $toEmail, $toName = null, $from = 'srevu@appstekcorp.com', $bcc = '', $attachment = null, $reference_module = null, $institution_id = null) {

    $mailQue = new App\Models\MailQue();
    $result = false;
    if (isset($toEmail) && trim($toEmail) != '') {

        $mailQue->Email = $toEmail;
        $mailQue->ToName = empty($toName) ? null : $toName;
        $mailQue->Subject = $subject;
        $mailQue->Content = (string) $message;
        $mailQue->From = $from;
        $mailQue->Bcc = $bcc;
        $mailQue->SendOn = date('Y-m-d H:i:s');
        $mailQue->ReferenceModule = $reference_module;
        $mailQue->InstitutionId = $institution_id;
        $mailQue->Attachment1Path = $attachment;
        $result = $mailQue->save(); //if success it returns int 1
        if ($result) {
            return (bool) $result;
        } else {
            return (bool) $result;
        }
    } else {
        return (bool) $result;
    }
}

/*
 * This function to attach image uploading functionality including html tags
 * @param array $params,
 * @return view
 */

function imageUpload($pic_data = array(), $params = array('name' => 'image', 'id' => 'image', 'class' => 'uploadImage')) {
    return view('admin::helpers.imageUpload', compact('params', 'pic_data'))->__tostring() ;
}

/*
 * This function to attach crop image functionality including html tags
 * @param array $params,
 * @return view
 */

function cropUploadedImage($pic_data = []) {
    $image400x400fromS3 = '';
    if(!empty($pic_data['image'])){
        if(getenv('s3storage'))
        {
            $image400x400fromS3 = getS3ViewUrl($pic_data['image'], 'user_profile_pic_400');
        }
        else
        {
            $image400x400fromS3 = asset('/data/uploaded_images/400x400/'.$pic_data['image']);
        }
    }
    return view('admin::helpers.crop_uploaded_image', compact('pic_data','image400x400fromS3'))->__tostring() ;
}

function gradeFormat($grade) {
//    $gradeStr = $grade;
    $gradeInt = $grade;
    if ($gradeInt == 0 && is_numeric($gradeInt)) {

    } elseif ($gradeInt == 1 && is_numeric($gradeInt)) {
        $grade = (int) $gradeInt . 'st';
    } elseif ($gradeInt == 2 && is_numeric($gradeInt)) {
        $grade = (int) $gradeInt . 'nd';
    } elseif ($gradeInt == 3 && is_numeric($gradeInt)) {
        $grade = (int) $gradeInt . 'rd';
    } elseif ($gradeInt >= 3 && !is_numeric($gradeInt)) {
        $grade = $gradeInt . 'th';
    } elseif ($gradeInt > 3 && is_numeric($gradeInt)) {
        $grade = (int) $gradeInt . 'th';
    } elseif (trim($gradeInt) == 'K-2' && is_string($gradeInt) ) {
        $grade = $gradeInt . 'nd';
    }

    return $grade;
}
/**
 * Functino responsible to resize and upload image
 *
 * @param string $orignialPath : Original Path to Upload
 * @param string $imageName : Name of the Image
 * @param string $resizedPath : Resized Image Path
 *
 * @param integer $width  The target width for the image
 * @param integer $height The target height for the image
 * @param boolean $ratio  Determines if the image ratio should be preserved
 * @param boolean $upsize Determines whether the image can be upsized
 *
 * @return type Array
 */
function resizeImage($orignialPath, $imageName, $resizedPath = null, $width = null, $height = null, $ratio = false, $upsize = true
) {
    if (!is_dir($resizedPath)) {
        @mkdir($resizedPath, 0777, true);
    }
    // open an image file
    $img = Image::make($orignialPath . '/' . $imageName);
    // resize the uploaded ad image with respect to showable size
    if (!empty($resizedPath)) {
//            $img->resize($width, $height, $ratio, $upsize);
        $img->resize($width, $height, function ($constraint) use($ratio, $upsize) {
            if ($ratio)
                $constraint->aspectRatio();
            if ($upsize)
                $constraint->upsize();
        });
    }
    // save resized image
    $img->save($resizedPath . $imageName);
    return $imageName;
}

function cropImage($inputs, $savePath, $resizeImage = array()) {
    $coords = $inputs['coords'];
    $imageName = $inputs['image_name'];

    if(getenv('s3storage'))
    {
        $s3 = new \App\Models\S3();
        $imageFromS3 = $s3->getFileUrl($imageName, 'user_profile_pic_400');
    }
    else
    {
        $imageFromS3 = asset('/data/uploaded_images/400x400/'.$imageName);
    }

    $img = Image::make($imageFromS3);
    $img->crop($coords['w'], $coords['h'], $coords['x'], $coords['y']);
    $img->save($savePath . $imageName);
    if (!empty($resizeImage)) {
        foreach ($resizeImage as $folder => $size) {
            $resizePath = public_path() . '/data/uploaded_images/' . $folder . '/';
            if (!is_dir($resizePath)) {
                mkdir($resizePath, 0777, true);
            }
            $dimensions = explode('x', $size);
            resizeImage($savePath, $imageName, $resizePath, $dimensions[0], $dimensions[1], false, false);
        }
    }
}
function getbranding()
{
    if (Auth::guest()) return [];

    $branding = DB::table('brandings')->where('institution_id', '=', Auth::user()->institution_id)->first();
    return $branding;
}