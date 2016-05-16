<?php namespace App\Models;

use \Storage;
use Config;
class S3 {

	private $disk;
	private $bucket = 'appstek/';
	private $directories = [
		'grades' 						=> 'appstek/@environment@/data/grades/',
		'programs_academic_calendar' 	=> 'appstek/@environment@/data/programs_academic_calendar/',
		'programs_syllabus_file_path' 	=> 'appstek/@environment@/data/programs_syllabus_file_path/',
		'resource_file_path' 			=> 'appstek/@environment@/data/files/',
		'resource_scorm_path' 			=> 'appstek/@environment@/data/scorm-files/',
		'messages_path' 				=> 'appstek/@environment@/data/message-attachments/',
		'question_attachments' 			=> 'appstek/@environment@/data/question_attachments/',
		'reports' 						=> 'appstek/@environment@/data/reports/',
        'assessment_fixedform_path' 	=> 'appstek/@environment@/data/assessment_pdf/',
        'assessment_pdf_images' 		=> 'appstek/@environment@/data/assessment_pdf_images/',
        'assessment_pdf' 				=> 'appstek/@environment@/data/assessment_pdf/',
        'subsection_pdf' 				=> 'appstek/@environment@/data/subsection-pdf/',
        'user_profile_pic_orignal' 		=> 'appstek/@environment@/data/uploaded_images/orignal/',
        'user_profile_pic_103'    		=> 'appstek/@environment@/data/uploaded_images/103x103/',
        'user_profile_pic_128'    		=> 'appstek/@environment@/data/uploaded_images/128x128/',
        'user_profile_pic_192'    		=> 'appstek/@environment@/data/uploaded_images/192x192/',
        'user_profile_pic_200'    		=> 'appstek/@environment@/data/uploaded_images/200x200/',
        'user_profile_pic_400'    		=> 'appstek/@environment@/data/uploaded_images/400x400/',
        'user_profile_pic_48'    		=> 'appstek/@environment@/data/uploaded_images/48x48/',
        'user_profile_pic_80'    		=> 'appstek/@environment@/data/uploaded_images/80x80/',
        'user_profile_pic_croped'    	=> 'appstek/@environment@/data/uploaded_images/croped/',
        'user_profile_pic_thumbnail'    => 'appstek/@environment@/data/uploaded_images/400x400/',
        'resource_scorm_extracted_path' => 'appstek/@environment@/data/scorm-files/extracted/',
        'bubblesheet_print' => 'appstek/@environment@/data/bubblesheet_print/',
        'preslug_apperson_files' => 'appstek/@environment@/data/preslug_apperson_files/'
	];

	public function __construct() {
		$this->disk = Storage::disk('s3');

		$this->environment = env('host_name', 'dev');
		foreach ($this->directories as $key => $value) {
			$this->directories[ $key ] = str_replace('@environment@', $this->environment, $value);
		}

		$this->bucket = Config::get('filesystems.disks.s3.bucket');
	}
        
        // $file array
	// $path string at which file will be stored Note: should be without trailing /
	public function uploadByObject($files, $path) {

		try {
			$response = [];
			$files = is_array($files) ? $files : [$files];

			foreach ($files as $key => $file) {

				$path = $this->directories[$path].$file->getClientOriginalName();				
				$uploaded = $this->disk->put($path, file_get_contents($file), 'public');
				
				if ($uploaded) {
					//$response[] = $this->disk->getDriver()->getAdapter()->getClient()->getObjectUrl('aacontent', $path);
					$response[] = $this->disk->getDriver()->getAdapter()->getClient()->getObjectUrl($this->bucket, $path);
				}
			}

			return $response;

		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Uploads the file by path
	 * @param  string|array $files An array or string of files
	 * @param  string $path        Path to which the file is uploaded
	 * @return string        	   The paths to which the file(s) was/were uploaded
	 */
	public function uploadByPath($files, $path) {

		if ( !isset($this->directories[ $path ])) {
			return false;
		}

		try {

			$response = [];
			$files = is_array($files) ? $files : [$files];

			foreach ($files as $key => $file) {

				$parts = explode('/', $file);

				$fileName = $parts[ count($parts) - 1 ];

				$path = $this->directories[$path] . $fileName;

				$uploaded = $this->disk->put($path, file_get_contents($file), 'public');

				if ( $uploaded ) {
					//$response[] = $this->disk->getDriver()->getAdapter()->getClient()->getObjectUrl('aacontent', $path);
					  $response[] = $this->disk->getDriver()->getAdapter()->getClient()->getObjectUrl($this->bucket, $path);
				}
			}

			return $response;

		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Returns the download URL for the required file that exists in the specified directory
	 * @param  String $fileName  File which is to be downloaded
	 * @param  String $directory The directory to which the file belongs
	 * @return String            The download URL for the file.
	 */
	public function getDownloadUrl( $fileName, $directory )
	{

		$path = $this->directories[ $directory ] . $fileName;

		$s3 = $this->disk->getDriver()->getAdapter()->getClient();

		/*$downloadUrl = $s3->getObjectUrl('aacontent', $path, '+5 minutes', array(
		    'ResponseContentDisposition' => 'attachment; filename="' . $fileName . '"',
		));*/
		$downloadUrl = $s3->getObjectUrl($this->bucket, $path, '+5 minutes', array(
		    'ResponseContentDisposition' => 'attachment; filename="' . $fileName . '"',
		));


		return $downloadUrl;
	}


	public function getFileUrl( $fileName, $directory )
	{
		$path = $this->directories[ $directory ] . $fileName;

		$s3 = $this->disk->getDriver()->getAdapter()->getClient();

		//$bucket = 'aacontent';
		$bucket = $this->bucket;

		$info = $s3->doesObjectExist($bucket, $path);
		if ($info){
		    $fileUrl = $s3->getObjectUrl($bucket, $path);
		}else{
		    $fileUrl = false;
		}

		return $fileUrl;
	}

	public function fileExists( $fileName, $directory ) {

		$path = $this->directories[ $directory ] . $fileName;

		return $this->disk->exists($path);
	}

	public function makeDirectory( $toMake, $directory )
	{
		$path = $this->directories[ $directory ] . $toMake;
		$result = Storage::disk('s3')->makeDirectory( $path );
	}

	public function uploadByPathToPath($file, $toPath, $directory )
	{
		$path = $this->directories[ $directory ] . $toPath;

		$parts = explode('/', $file);
		$fileName = $parts[ count($parts) - 1 ];

		$path = $path . '/' . $fileName;

		$this->disk->put($path, file_get_contents($file), 'public');
	}

	public function getFiles( $path, $directory )
	{
		$path = $this->directories[ $directory ] . $path;

		$files = Storage::disk('s3')->files( $path );

		return $files;
	}

	public function deleteDirectory( $toDelete, $directory ) {

		$path = $this->directories[ $directory ] . $toDelete;
		Storage::disk('s3')->deleteDirectory($path);
	}
}