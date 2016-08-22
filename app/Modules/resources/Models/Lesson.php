<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Resources\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use \PHPExcel,
	//\PHPExcel_Style_Fill,
	\PHPExcel_IOFactory,
	\PHPExcel_Style_NumberFormat;
use App\Modules\Admin\Models\User;
use  App\Modules\Admin\Models\Institution;
use \Validator;
use \Session;

class Lesson extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'lesson';
	protected $primaryKey = 'id';

	public function getLesson($subject_id = 0)
	{
		//dd($institution_id ."-----". $category_id."-----------".$subject_id = 0);
		//dd($subject_id);
		$obj = DB::table('lesson'); //new Lesson();
		if($subject_id > 0){
		
			$obj->where('subject_id', $subject_id);
			$lessons = $obj->lists('name', 'id');
			//dd($lessons);
		}
		else
		{
			$lessons = $obj->lists('name', 'id');
		}
		//dd($lessons);
		return $lessons;
	}public function getSubjectCategoryLesson($institution_id = 0, $category_id = 0, $subject_id = 0)
	{
		//$users = User::get();
 		$obj = DB::table('lesson as l'); //new Lesson();
		$obj->join('category as c', 'c.id', '=', 'l.category_id');
		$obj->join('subject as s', 's.id', '=', 'l.subject_id');
		if($institution_id > 0 || $category_id > 0 || $subject_id > 0)
		{
			//$lessons = $obj->where("subject_id", $subject_id)->where('institution_id', $institution_id)->where('category_id', $category_id)->lists('name', 'id');
			if($institution_id > 0)
			{
				$obj->where('l.institution_id', $institution_id);
				if($category_id > 0)
				{
					$obj->where('l.category_id', $category_id);
					if($subject_id > 0)
						$obj->where('l.subject_id', $subject_id);
				}
			}
			$lessons  = $obj->select('l.category_id as l_cat_id', 's.category_id as s_cat_id','l.name as l_name', 's.name as subject_name','c.name as cat_name', 'l.id as l_id','c.id')->get();
		}
		else
		{
			$lessons  = $obj->select('l.category_id as l_cat_id', 's.category_id as s_cat_id','l.name as l_name', 's.name as subject_name','c.name as cat_name', 'l.id as l_id','c.id')->get();
		}

		return $lessons;
	}

	public function getLessonInfo($id = 0)
	{
		$lesson = Lesson::find($id);
		return $lesson;
	}

	public function deleteLesson($id = 0)
	{
		$lesson = Lesson::find($id);
		$lesson->delete();
	}

	public function updateLesson($params = 0)
	{
		$obj = new Lesson();
		if($params['id'] > 0)
		{
			$obj = Lesson::find($params['id']);	
			$obj->updated_by = Auth::user()->id;			
		}
		else
		{
			$obj->added_by = Auth::user()->id;				
		}
		
		$obj->name = $params['name'];
		$obj->subject_id = $params['subject_id'];
		$obj->institution_id = $params['institution_id'];
		$obj->category_id = $params['category_id'];
		$obj->save();	
	}
	public function bulklessonTemplate($filename, $lessonType, $instituteId = null,$categoryId = null, $subjectId= null, $findInstituteId = false)
	{

		$objPHPExcel = new PHPExcel();
	    $institution_name="";
		$category_name=Category::where('institution_id','=',$instituteId)->lists('name');
		//($category_name);
		$subject_name=Subject::where('category_id','=',$categoryId)->lists('id');
		//dd($subject_name);
		if($instituteId != null){
			$institution_name = Institution::find($instituteId)->id;
		}

		$institue = new Institution();

		$madeDataValidationColumn = array();
		if ($instituteId == null) {
			$institues =$institue::orderby('id', 'desc')->take(100)->lists('id');
		} else {
			if($findInstituteId){
				$institues = $institue->where('id', $instituteId)->lists('id');
				$madeDataValidationColumn = array();
			}else{
				$institues = $institue->where('id', $instituteId)->lists('id');
			}
		}
		$countries=[];// $this->getcountries();
		$states=[];//$this->getstates();
		//Create Validation for School and State
		$objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
		$indexSchool = 1;
		$indexState = 1;
		$exportFields = array(
			'institutionId' => array('value'=>[$institution_name]),
			'categoryId' => array('value'=>$category_name),
			'subjectId'  => array('options'=>$subject_name),
			'lesson_name' => array(),
		);
		$firstRow = false;
		$celli = 'A';
		$rowsToFill = 100;
		foreach ($exportFields as $field => $options) {
			$objPHPExcel->getActiveSheet()->setCellValue($celli . '1', $field);
			$objPHPExcel->getActiveSheet()->getStyle($celli . '1:' . $celli . $rowsToFill)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

			if (is_array($options) && isset($options['options'])) {
				if (isset($options['multiselect']) && $options['multiselect'] == true) {
					for ($j = 0; $j < count($options['options']); $j++) {
						$objPHPExcel->getActiveSheet()->setCellValue($celli . '1', $field . '-' . $options['options'][$j]);

						for ($i = 2; $i <= $rowsToFill; $i++) {
							$objValidation = $objPHPExcel->getActiveSheet()->getCell($celli . $i)->getDataValidation();
							$objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
							$objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
							$objValidation->setAllowBlank(false);
							$objValidation->setShowInputMessage(true);
							$objValidation->setShowErrorMessage(true);
							$objValidation->setShowDropDown(true);
							$objValidation->setErrorTitle('Input error');
							$objValidation->setError('Value is not in list.');
							$objValidation->setPromptTitle('Pick ' . $field);
							$objValidation->setPrompt('Please pick a value from the drop-down list.');
							$objValidation->setFormula1('"X"');
						}
						if ($j != count($options['options']) - 1)
							$celli++;
					}
				}else {

					for ($i = 2; $i <= $rowsToFill; $i++) {
						$objValidation = $objPHPExcel->getActiveSheet()->getCell($celli . $i)->getDataValidation();
						$objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
						$objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
						$objValidation->setAllowBlank(false);
						$objValidation->setShowInputMessage(true);
						$objValidation->setShowErrorMessage(true);
						$objValidation->setShowDropDown(true);
						$objValidation->setErrorTitle('Input error');
						$objValidation->setError('Value is not in list.');
						$objValidation->setPromptTitle('Pick ' . $field);
						$objValidation->setPrompt('Please pick a value from the drop-down list.');
						$objValidation->setFormula1('"' . implode(',', $options['options']) . '"');

						if (isset($options['validation'])) {
							if (($options['validation'] == 'state') && $indexState > 1) {
								$objValidation->setFormula1('options!$A$1:$A$' . ($indexState - 1));
							}
							if (($options['validation'] == 'school') && $indexSchool > 1) {
								$objValidation->setFormula1('options!$B$1:$B$' . ($indexSchool - 1));
							}
						}
					}
				}
			}

			$celli++;
		}
		if( !empty($instituteId)){
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('A2', $instituteId, \PHPExcel_Cell_DataType::TYPE_STRING);
		}
		if( !empty($categoryId)){
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('B2', $categoryId, \PHPExcel_Cell_DataType::TYPE_STRING);
		}
		if( !empty($subjectId)){
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('C2', $subjectId, \PHPExcel_Cell_DataType::TYPE_STRING);
		}
		$highestColumn = User::createColumnsArray($objPHPExcel->getActiveSheet()->getHighestColumn());
		foreach ($highestColumn as $columnID) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
		}

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		if (!is_dir(public_path() . '/data/tmp')) {
			mkdir(public_path() . '/data/tmp', 0777);
			chmod(public_path() . '/data/tmp', 0777);
		}
		$save = $objWriter->save(public_path() . '/data/tmp/' . $filename);
		return $save;
	}
	public static function validateBulUpload($fileType, $data, $index) {
		$error = array();

		$dataArr = $data->toArray();
		$validationRule = [
				'institutionid' => 'required|numeric|exists:institution,id',
				'categoryid'=>'required',
				'subjectid'=>'required',
				'lesson_name'=>'required|unique:lesson,name|min:3',
		];
		$messages = [
		];
		$validator = Validator::make($dataArr, $validationRule, $messages);
		if ($validator->fails()) {
			$messages = $validator->messages();
			foreach ($messages->all() as $row) {
				$error[] = array('Row #' => $index, 'Error Description' => $row);
			}
		}
		return $error;
	}
	public static function createBulkUser($row, $institutionId)
	{
		//dd($row);

		$category_id=Category::where('id',$row->categoryid)->first()->id;
		//dd($category_id);
		$subject_id=Lesson::where('id',$row->subjectid)->first()->id;
		//dd($category_id);
		$obj = new self;
		$obj->institution_id = $institutionId;
		$obj->category_id = $category_id;
		$obj->subject_id= $subject_id;
		$obj->name = $row->lesson_name;
		$obj->save();
	}
}
