<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */


namespace App\Modules\Admin\Models;
use DB;
use \PHPExcel,
	//\PHPExcel_Style_Fill,
		\PHPExcel_IOFactory,
		\PHPExcel_Style_NumberFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\countries;
use App\states;
use \Validator;

//use App\states;
class Institution extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'institution';
	protected $primaryKey = 'id';

	public function getInstitutions($parent_id = 0)
	{
		//$users = User::get();
		$obj = new Institution();
		if($parent_id > 0)
		{
			$institutions = $obj->where("id", $parent_id)->orWhere('parent_id', $parent_id)->lists('name', 'id');
		}
		else
		{
			$sessRole = getRole() ;
			if($sessRole != 'administrator')
			{
				$institutions = $obj->where('id','=' , Auth::user()->institution_id)->lists('name', 'id');
			}
			else{
				$institutions = $obj->lists('name', 'id');
			}			
		}
		
		return $institutions;
	}

	public function getInstitutionInfo($id = 0)
	{
		$institution = Institution::find($id);
		return $institution;
	}

	public function deleteInstitution($id = 0)
	{
		$institution = Institution::find($id);
		$institution->delete();
	}
	public  function getcountries()
	{
		$countries = DB::table('countries')->lists('country_name', 'id');
		return $countries;
	}
	public  function getstates()
	{
		$states = DB::table('states')->lists('state_name', 'id');
		return $states;
	}

	public function getroles(){
		$roles=DB::table('roles')->where('id','!=',2)->lists('name','id');
		return $roles;
      }
	/*public function getstates()
	{
		$states  = DB::table('states')->lists('state_name','id');
		return $states;
	}*/
	public function updateInstitution($params = 0)
	{
		$obj = new Institution();
		if($params['id'] > 0)
		{
			$obj = Institution::find($params['id']);				
			$obj->updated_by = Auth::user()->id;	
		}
		else
		{
			$obj->added_by = Auth::user()->id;
		}
		
		$obj->name = $params['name'];
		$obj->parent_id = $params['parent_id'];
		
		$obj->address1 = $params['address1'];
		$obj->address2 = $params['address2'];
		$obj->address3 = $params['address3'];
		$obj->city = $params['city'];
		$obj->state = $params['state'];
		$obj->country_id = $params['country_id'];
		$obj->phoneno = $params['phoneno'];
		$obj->pincode = $params['pincode'];

		$obj->save();	
	}

	public function bulkInstitutionTemplate($filename, $userType)
	{
		$objPHPExcel = new PHPExcel();
		$countries = $this->getcountries();
		$states = $this->getstates();
		$role = $this->getroles();
		//Create Validation for School and State
		$objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
		$indexSchool = 1;
		$indexState = 1;

		$exportFields = array(
				'Institution Id' => array(),
				'Institution Name' => array(),
				'Address1' => array(),
				'Address2' => array(),
				'Address3' => array(),
				'City' => array(),
				'State'=>array('options'=>$states),
				'Phone' => array(),
				'Pin' => array(),
				'Country' => array('options' => $countries),
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
				} else {

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

	public static function createColumnsArray($end_column, $first_letters = '') {
		$columns = array();
		$length = strlen($end_column);
		$letters = range('A', 'Z');

		// Iterate over 26 letters.
		foreach ($letters as $letter) {

			// Paste the $first_letters before the next.
			$column = $first_letters . $letter;

			// Add the column to the final array.
			$columns[] = $column;

			// If it was the end column that was added, return the columns.
			if ($column == $end_column)
				return $columns;
		}

		// Add the column children.
		foreach ($columns as $column) {

			// Don't itterate if the $end_column was already set in a previous itteration.
			// Stop iterating if you've reached the maximum character length.
			if (!in_array($end_column, $columns) && strlen($column) < $length) {
				$new_columns = self::createColumnsArray($end_column, $column);

				// Merge the new columns which were created with the final columns array.
				$columns = array_merge($columns, $new_columns);
			}
		}

		return $columns;
	}

	public static function validateBulUpload($fileType, $data, $index) {
		$error = array();

		$dataArr = $data->toArray();
		$validationRule = [
				/*'institutionid' => 'required|numeric|exists:institution,id',
			//'username' => 'required|unique:users,UserName|max:50|regex:/^[a-zA-Z0-9@._]+$/',
				'enrollment_no' => 'required',
				'email' => 'required|email|max:50',
				'password' => ['required','min:8','max:50','at_least_one_upper_case','at_least_one_lower_case','at_least_one_number','not_contains:'.$dataArr['first_name'].','.$dataArr['last_name']],
				'first_name' => 'required|max:50|regex:/^[a-zA-Z\s-\']+$/',
			//'middle_name' => 'max:50|regex:/^[a-zA-Z\s-\']+$/',
				'last_name' => 'required|max:50|regex:/^[a-zA-Z\s-\']+$/',
				'gender' => 'required|in:Male,Female',
			// 'phone' => 'required_without:primary_phone|regex:/^\([0-9]{3}\)\s[0-9]{3}-[0-9]{4}$/',
			//'phone' => 'regex:/^\([0-9]{3}\)\s[0-9]{3}-[0-9]{4}$/',
				'phone' => 'required|regex: /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
			// 'primary_phone' => 'required_without:phone|regex:/^\([0-9]{3}\)\s[0-9]{3}-[0-9]{4}$/',
			//'primary_phone' => 'regex:/^\([0-9]{3}\)\s[0-9]{3}-[0-9]{4}$/',
			//'primary_phone_type' => 'required_with:primary_phone',
			// 'email' => 'email|required|max:50',
				'status' => 'required|in:Active,Inactive',
			// 'address' => 'max:100|required',
				'address' => 'required|max:100',
			// 'city' => 'max:50|required',
				'city' => 'required|max:50',
				'state' => 'required',
				'country' => 'required',
			// 'zip' => 'numeric|max:999999|required',
				'pin' => 'required|regex:/\b\d{6}\b/',
				'role' => 'required',*/
		];

		$messages = [
				/*'first_name.regex' => 'The :attribute field accepts only Alpha, space, - and \'',
				'last_name.regex' => 'The :attribute field accepts only Alpha, space, - and \'',
				'phone.regex' => 'The :attribute field should be in format 9999999999.',
				'password.min' => 'The password must be at least 8 characters',
				'password.at_least_one_upper_case' => 'The :attribute field must have at least one uppercase character',
				'password.at_least_one_lower_case' => 'The :attribute field must have at least one lowercase character',
				'password.at_least_one_number' => 'The :attribute field must have at least one number',
				'password.not_contains' => 'The :attribute field must not contains first name, last name or username',*/
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

	public static function createBulkInstitutions( $row)
	{
		//dd($row);
		$country_id=countries::select('id')->where('country_name',$row->country)->first();
		$state_id=states::select('id')->where('state_name',$row->state)->first();
		$obj = new self;
		$obj->id = $row->institutionid;
		$obj->address1 = $row->address1;
		$obj->address2 = $row->address2;
		$obj->address3 = $row->address3;
		$obj->city = $row->city;
		$obj->state = $state_id->id;
		$obj->phoneno = $row->phone;
		$obj->pincode = $row->pin;
		$obj->country_id = $country_id->id;

		$obj->save();

		//$roleobj = DB::select(DB::raw("insert into institution (user_id,role_id) values (".$obj->id.",".$obj->role_id.")"));
	}
}
