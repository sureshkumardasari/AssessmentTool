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

class Category extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category';
	protected $primaryKey = 'id';

	public function getCategory($institution_id = 0)
	{
		//$obj = new Category();
		$obj = DB::table('category'); 
		
		if($institution_id > 0)
		{
			$obj->where('institution_id', $institution_id);								
		}			
		$category = $obj->lists('name', 'id');
		
		return $category;
	}

	public function getCategoryInfo($id = 0)
	{
		$category = Category::find($id);
		return $category;
	}

	public function deleteCategory($id = 0)
	{
		$category = Category::find($id);
		$category->delete();
	}

	public function updateCategory($params = 0)
	{

		$obj = new Category();
		if($params['id'] > 0)
		{
			$obj = Category::find($params['id']);
			$obj->updated_by = Auth::user()->id;
		}
		else
		{
			$obj->added_by = Auth::user()->id;
		}
		$obj->institution_id = $params['institution_id'];
		$obj->name = $params['name'];
		$obj->save();	
	}

	public function bulkcategoryTemplate($filename, $categoryType, $instituteId = null, $addSubjects = false, $findInstituteId = false)
    {
		$objPHPExcel = new PHPExcel();
		$institution_name="";
       if($instituteId != null){
    	$institution_name = Institution::find($instituteId)->name;
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
			'InstitutionID' => $madeDataValidationColumn,
			'category_name' => array(),
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
		if($findInstituteId && !empty($institues[0])){
			$objPHPExcel->getActiveSheet()->setCellValueExplicit('A2', $institues[0], \PHPExcel_Cell_DataType::TYPE_STRING);
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
				'category_name'=>'required|unique:category,name|min:3',
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
        $obj = new self;
		$obj->institution_id = $institutionId;
		$obj->name = $row->category_name;
		$obj->save();
	}
}
