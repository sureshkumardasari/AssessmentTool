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

class Subject extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subject';
	protected $primaryKey = 'id';

	public function getSubject($category_id = 0)
	{		
		//$obj = new Subject();
		//dd($institution_id ."-----". $category_id);
		$obj = DB::table('subject');
		if($category_id > 0)
		{
			$obj->where('category_id', $category_id);					
			 $subjects = $obj->lists('name', 'id');
		}
		else
		{
			$subjects = $obj->lists('name', 'id');
				}
		
		return $subjects;
	}

  public function getSubjectCategory($institution_id = 0, $category_id = 0)
      {
           //$obj = new Subject();
         $obj = DB::table('subject as s');
          $obj->join('category as c', 'c.id', '=', 's.category_id');
          if($institution_id > 0 || $category_id > 0)
          {
               //$subjects = $obj->where("institution_id", $institution_id)->where("category_id", $category_id)->lists('name', 'id');
              if($institution_id > 0)
             {
                  $obj->where('s.institution_id', $institution_id);
                 if($category_id > 0)
                  {
                       $obj->where('category_id', $category_id);
                  }
              }
              $subjects = $obj->select('s.name as subject_name','c.name as cat_name', 's.id as s_id','c.id','category_id')->get();
 
          }
          else
          {
          	$sessRole = getRole() ;
		   if($sessRole != 'administrator')
		   {
			$subjects = $obj->where('s.institution_id','=' , Auth::user()->institution_id);
			//dd($subjects);
		   }
              $subjects = $obj->select('s.name as subject_name','c.name as cat_name', 's.id as s_id','c.id','category_id')->get();
          }
            return $subjects;
       }
   
    


	public function getSubjectInfo($id = 0)
	{
		$subject = Subject::find($id);
		return $subject;
	}

	public function deleteSubject($id = 0)
	{
		$subject = Subject::find($id);
		$subject->delete();
	}

	public function updateSubject($params = 0)
	{
		$obj = new Subject();
		if($params['id'] > 0)
		{
			$obj = Subject::find($params['id']);
			$obj->updated_by = Auth::user()->id;


		}
		else
		{
			$obj->added_by = Auth::user()->id;
			

		}
		$obj->institution_id = $params['institution_id'];
		$obj->category_id = $params['category_id'];
		$obj->name = $params['name'];
		$obj->save();	
	}

	public function getCategory()
	{
		$category = ['1' => 'Compititive'];
		return $category;
	}
	public function bulksubjectTemplate($filename, $subjectType, $instituteId = null,$categoryId = null,  $findInstituteId = false)
	{

		$objPHPExcel = new PHPExcel();
	    $institution_name="";
		$category_name=Category::where('institution_id','=',$instituteId)->lists('id');
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
			'categoryId' => array('options'=>$category_name),
			'subjectId' => array(),
		);
		//dd($exportFields);
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
		//dd($save);
		return $save;
	}
	public static function validateBulUpload($fileType, $data, $index) {
		$error = array();

		$dataArr = $data->toArray();
//		dd($dataArr);
		$validationRule = [
				'institutionid' => 'required|numeric|exists:institution,id',
				'category_id'=>'required',
		        'subject_name'=>'required|min:3',
		];
		$messages = [
		];

		$validator = Validator::make($dataArr, $validationRule, $messages);
		$messages = $validator->messages();
		$error=[];
		$data = Subject::where('institution_id', $dataArr['institutionid'])->where('category_id',$dataArr['category_id'])
			->where('name', $dataArr['subject_name'])->select('name')->first();
		if($dataArr['subject_name']==$data['name']){
			$num = Subject::where('institution_id', $dataArr['institutionid'])->where('category_id',$dataArr['category_id'])
				->where('name', $dataArr['subject_name'])->count();
			if ($num > 0) {
 				$error[] = array('subject already found');
			}else{

			}
		}
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
		//$category_id=Category::where('name',$row->category_name)->first()->id;
		//dd($category_id);
//		$obj = DB::table('subject');

		$obj = new Subject();
		$obj->institution_id = $institutionId;
		$obj->category_id = $row->category_name;
		$obj->name = $row->subject_name;
		$obj->save();
	}
}
