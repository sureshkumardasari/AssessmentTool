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

class Assignment extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'assignment';
	protected $primaryKey = 'id';

	public function getassignment($assignment_id = 0)
	{
		//$users = User::get();
		$obj = new Assignment();
		if($assignment_id > 0)
		{
			$assignments = $obj->where("id", $assignment_id)->lists('name', 'id');
		}
		else
		{
			$assignments = $obj->lists('name', 'id');
		}
		
		return $assignments;
	}

	public function getassignmentInfo($id = 0)
	{
		$assignment = Assignment::find($id);
		return $assignment;
	}

	public function deleteassignment($id = 0)
	{
		$assignment = Assignment::find($id);
		$assignment->delete();
	}

	public function updateassignment($params = 0)
	{
		$obj = new Assignment();
		if($params['id'] > 0)
		{
			$obj = Assignment::find($params['id']);
			$obj->updated_by = Auth::user()->id;				
		}
		else
		{
			$obj->added_by = Auth::user()->id;				
		}
		$obj->id = $params['id'];
		$obj->name = $params['name'];
		$obj->description = $params['assignment_text'];
		$obj->assessment_id = $params['assessment_id'];
		$obj->startdatetime = gmdate("Y-m-d H:i:s", strtotime($params['startdatetime']));//$params['startdatetime'];
		$obj->enddatetime = gmdate("Y-m-d H:i:s", strtotime($params['enddatetime']));//$params['enddatetime'];
		$obj->launchtype = $params['launchtype'];
		$obj->proctor_user_id = $params['proctor_id'];
		$obj->proctor_instructions = $params['proctor_instructions'];
		$obj->institution_id = $params['institution_id'];
		$obj->delivery_method = $params['delivery_method'];
		$obj->status = $params['status'];
		//$obj->save();	

		if($obj->save()){

			$last_id=$obj->id;

			$users = AssignmentUser::find($last_id);
			if($users)
				$users->delete();	

	 		foreach ($params['student_ids'] as $key => $value) {

				$user_assign = new AssignmentUser();
							
				$user_assign->assessment_id = $params['assessment_id'];
				$user_assign->assignment_id = $last_id;
				$user_assign->user_id = $value;							
				$user_assign->save();

			}
		}

	}
}
