<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Admin\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use \PHPExcel,
    //\PHPExcel_Style_Fill,
    \PHPExcel_IOFactory,
    \PHPExcel_Style_NumberFormat;
    //\PHPExcel_Reader_Excel5,
    //\PHPExcel_Shared_Date,
    //\PHPExcel_Cell,
    //\PHPExcel_Style_Alignment
    //\PHPExcel_Cell_DataType;

class User extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	protected $primaryKey = 'id';

	public function getUsers($institution_id = 0, $role_id = 0)
	{
		//$users = User::get();
		$query = DB::table('Users as u')
            ->join('Institution as i', function($join){
                $join->on('i.id', '=', 'u.institution_id');
            })
            ->join('Roles as r', function($join){
                $join->on('r.id', '=', 'u.role_id');
            //})->select('Users.name', 'Users.email','Institution.name', 'Roles.name')->get();
            })->select(DB::raw('u.name as username, u.email, i.name as Instname, r.name as rolename, u.status, u.id'));

        if($institution_id > 0)
        {
        	$query->where("u.institution_id", $institution_id);
        }
        if($role_id > 0)
        {
        	$query->where("u.role_id", $role_id);
        }

        $users = $query->get();
		return $users;
	}

	public function getUserInfo($user_id = 0)
	{
		$user = User::find($user_id);
		return $user;
	}

	public function getRoles()
	{
		$roles = DB::table('Roles')->lists('name', 'id');
		return $roles;
	}

	public function deleteUser($userid = 0)
	{
		$user = User::find($userid);
		$user->delete();
		$role = DB::table('role_user')->where("user_id", $userid)->delete();
	}

	public function updateUser($params = 0)
	{
		$obj = new User();
		if($params['id'] > 0)
		{
			$obj = User::find($params['id']);

			if($params['password'] != "")
			{
				$obj->password = bcrypt($params['password']);
			}	
			$obj->updated_by = Auth::user()->id;
		}
		else
		{
			$obj->password = bcrypt($params['password']);
			$obj->added_by = Auth::user()->id;
		}
		$obj->name = $params['name'];
		$obj->email = $params['email'];
		$obj->enrollno = $params['enrollno'];
		$obj->role_id = $params['role_id'];
		$obj->institution_id = $params['institution_id'];
		$obj->status = $params['status'];		
		$obj->save();	

		$roleobj = DB::select(DB::raw("delete from role_user where user_id = '".$obj->id."'"));
		$roleobj = DB::select(DB::raw("insert into role_user (user_id,role_id) values (".$obj->id.",".$obj->role_id.")"));
	}

	public function deleteRole($id = 0)
	{
		$obj = Role::find($id);
		$obj->delete();
	}

	public function updateRole($params = 0)
	{
		$obj = new Role();
		if($params['id'] > 0)
		{
			$obj = Role::find($params['id']);				
		}
		
		$obj->name = $params['name'];
		$obj->save();	
	}

	public function bulkUserTemplate($filename, $userType, $instituteId = null, $addSubjects = false, $findInstituteId = false) {

	    $objPHPExcel = new PHPExcel();

	    $states = ['AndhraPradesh','Telangana'];

	    $institue = new Institution();
	    $madeDataValidationColumn = array();
	    if ($instituteId == null) {
	        $institues =$institue::orderby('Id', 'desc')->take(100)->lists('Id');
	    } else {
	        if($findInstituteId){
	            $institues = $institue->where('Id', $instituteId)->lists('Id');
	            $madeDataValidationColumn = array();
	        }else{
	            $institues = $institue->where('Id', $instituteId)->lists('Id');
	        }
	    }

	//Create Validation for School and State
	    $objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
	    $indexSchool = 1;
	    $indexState = 1;

	//     foreach ($states as $row) {
	//         $objWorkSheet->setCellValue('A' . $indexState, $row);
	//         $indexState++;
	//     }

	//     foreach ($institues as $row) {
	//         $objWorkSheet->setCellValue('B' . $indexSchool, $row);
	//         $indexSchool++;
	//     }

	//     $objWorkSheet->setCellValue('D1', $userType);
	// // Rename sheet
	//     $objWorkSheet->setTitle("options");
	// //Set Protection
	//     $objWorkSheet->getProtection()->setSheet(true);
	//     $objWorkSheet->getProtection()->setSort(true);
	//     $objWorkSheet->getProtection()->setInsertRows(true);
	//     $objWorkSheet->getProtection()->setFormatCells(true);
	//     $objWorkSheet->getProtection()->setPassword('password');
	    
	    $exportFields = array(
	    	'InstitutionID' => $madeDataValidationColumn,
	    	'Enrollment No' => array(),      
	    	'Email' => array(),
	    	'Password' => array(),
	        'First Name' => array(),
	        'Last Name' => array(),
	        //'Gender' => array('options' => array('Male', 'Female', 'Unknown')),
	        'Phone No' => array(),      
	        'Status' => array('options' => array('Active', 'Inactive')),
	        'Address' => array(),
	        'City' => array(),
	        'State' => array('options' => array(), 'validation' => 'state'),
	        'Country' => array(),
	        'Pin' => array(),
	        'Role' => array('options' => ['student'])
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
}
