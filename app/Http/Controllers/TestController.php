<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Hash;
use Debugbar;
use Schema;

class TestController extends Controller
{
  

    public function Autocreatedir(){
      $directories = [
        'grades' => 'data/grades/',
        'programs_academic_calendar' => 'data/programs_academic_calendar/',
        'programs_syllabus_file_path' => 'data/programs_syllabus_file_path/',
        'resource_file_path' => 'data/files/',
        'resource_scorm_path' => 'data/scorm-files/',
        'messages_path' => 'data/message-attachments/',
        'question_attachments' => 'data/question_attachments/',
        'reports' => 'data/reports/',
        'assessment_fixedform_path' => 'data/assessment_pdf/',
        'assessment_pdf_images' => 'data/assessment_pdf_images/',
        'assessment_pdf' => 'data/assessment_pdf/',
        'subsection_pdf' => 'data/subsection-pdf/',
        'user_profile_pic_orignal' => 'data/uploaded_images/orignal/',
        'user_profile_pic_103' => 'data/uploaded_images/103x103/',
        'user_profile_pic_128' => 'data/uploaded_images/128x128/',
        'user_profile_pic_192' => 'data/uploaded_images/192x192/',
        'user_profile_pic_200' => 'data/uploaded_images/200x200/',
        'user_profile_pic_400' => 'data/uploaded_images/400x400/',
        'user_profile_pic_48' => 'data/uploaded_images/48x48/',
        'user_profile_pic_80' => 'data/uploaded_images/80x80/',
        'user_profile_pic_croped' => 'data/uploaded_images/croped/',
        'user_profile_pic_thumbnail' => 'data/uploaded_images/400x400/',
        'resource_scorm_extracted_path' => 'data/scorm-files/extracted/',
        'bubblesheet_print' => 'data/bubblesheet_print/',
        'students_bubblesheets'=>'data/students_bubblesheets/',
        'extracted-scorm-files' => 'data/extracted-scorm-files/',
        'meta' => 'data/meta/',
        'tmp' => 'data/tmp/',
        'grades-pdf' => 'data/grades-pdf/',
        'barcode-image'=> 'data/barcode-image/',
        'print-templates' => 'data/print-templates/'
        ];

        foreach ($directories as $key => $value) {        
            if (!file_exists($directories[$key])) {
                echo "Files created successfully" .$key;
                echo "<br>";
                mkdir($value, 0775, true);
            } 
        }
    }

    public function errorlog() {       
        $fullPath = storage_path(). '/logs/laravel-'.date('Y-m-d').'.log';
        $params['Download'] = true;
        $pathTmp = 'data/tmp/';

        $zip = new \ZipArchive();
        $zipFileName = time() . ".zip";
        $path = public_path($pathTmp);
        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }
        $zipFileNameFullPath = $path . $zipFileName; // Zip name
        $zip->open($zipFileNameFullPath, \ZipArchive::CREATE);
        $fullPath = str_replace('\\', '/', $fullPath);//exit;
        // if (file_exists($fullPath)) {
        $zip->addFromString(basename($fullPath), file_get_contents($fullPath));
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

   

 }
