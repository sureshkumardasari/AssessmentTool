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
		$obj->startdatetime = date("Y-m-d H:i:s", strtotime($params['startdatetime']));//$params['startdatetime'];
		$obj->enddatetime = (isset($params['enddatetime']) && ($params['enddatetime'] != "" && $params['enddatetime'] != null)) ? date("Y-m-d H:i:s", strtotime($params['enddatetime'])) : '';
		//gmdate("Y-m-d H:i:s", strtotime($params['enddatetime']));//$params['enddatetime'];
		$obj->neverexpires = (isset($params['never'])) ?  $params['never'] : 0;
		$obj->launchtype = $params['launchtype'];
		$obj->proctor_user_id = (isset($params['proctor_id'])) ? $params['proctor_id'] : 0;
		$obj->proctor_instructions = (isset($params['proctor_instructions'])) ? $params['proctor_instructions'] : '';
		$obj->institution_id = $params['institution_id'];
		$obj->delivery_method = $params['delivery_method'];
		$obj->status = 'upcoming';//$params['status'];
		//$obj->save();	
		
		if($obj->save()){

			$last_id=$obj->id;

			$users = AssignmentUser::find($last_id);
			if($users)
				$users->delete();	

			if(isset($params['student_ids']))
			{
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

	public function getTestsold($user_id = 0, $category_id = 0) {

        $data = array();
        $user_id = ($user_id > 0) ? $user_id : Auth::user()->id;
        // available tests
        $_data['Available'] = DB::table('assessment')
                                        ->join('assignment', 'assessment.id', '=', 'assignment.assessment_id')
                                        ->join('assignment_user', 'assignment.id', '=', 'assignment_user.assignment_id')
                                        //->where('assessment.category_id', $category_id)
                                        ->whereRaw('((assignment.startdatetime <= \''. date('Y-m-d H:i:s') .'\'')
                                        ->whereRaw('assignment.enddatetime >= \''. date('Y-m-d H:i:s') .'\' AND assignment.neverexpires = \'1\') OR (assignment.startdatetime <= \''. date('Y-m-d H:i:s') .'\' AND (assignment.neverexpires = \'0\')))')
                                        ->where('assignment_user.user_id', $user_id)
                                        ->where('assignment_user.status', '<>', 'completed')
                                        ->where('assignment.delivery_method', '=', 'online')
                                        ->where('assignment.launchtype','=', 'system')
                                        ->select('assignment.id','assignment.assessment_id','assignment.name','assignment.startdatetime','assignment.enddatetime','assignment.neverexpires')
                                        ->get();
        // upcoming tests
        $_data['Upcoming'] = DB::table('assessment')
                                        ->join('assignment', 'assessment.id', '=', 'assignment.assessment_id')
                                        ->join('assignment_user', 'assignment.id', '=', 'assignment_user.assignment_id')
                                        //->where('assessment.category_id', $category_id)
                                        ->whereRaw('(assignment.startdatetime > \''. date('Y-m-d H:i:s') .'\')') 
                                        ->where('assignment_user.user_id', $user_id)
                                        ->where('assignment_user.status', '<>', 'completed')
                                        ->where('assignment_user.status', '<>', 'test')
                                        ->where('assignment_user.status','<>','inprogress')
                                        ->where('assignment.delivery_method', '=', 'online')
                                        ->where('assignment.launchtype','=', 'system')
                                        ->select('assignment.id','assignment.assessment_id','assignment.name','assignment.startdatetime','assignment.enddatetime','assignment.neverexpires')
                                        ->get();

        $proctorlaunchtestupcoming = DB::table('assessment')
            ->join('assignment', 'assessment.id', '=', 'assignment.assessment_id')
            ->join('assignment_user', 'assignment.id', '=', 'assignment_user.assignment_id')
            //->where('assessment.category_id', $category_id)
            ->whereRaw('((assignment.enddatetime >= \''. date('Y-m-d H:i:s') .'\' AND assignment.neverexpires = \'1\') OR  (assignment.neverexpires = \'0\'))')
            ->where('assignment_user.user_id', $user_id)
            ->where('assignment_user.status','=','upcoming')
            ->where('assignment.delivery_method', '=', 'online')
            ->where('assignment.launchtype','=', 'proctor')
            ->select('assignment.id','assignment.assessment_id','assignment.name','assignment.startdatetime','assignment.enddatetime','assignment.neverexpires')
            ->get();

        $proctorlaunchtestavailable = DB::table('assessment')
            ->join('assignment', 'assessment.id', '=', 'assignment.assessment_id')
            ->join('assignment_user', 'assignment.id', '=', 'assignment_user.assignment_id')
            //->where('assessment.category_id', $category_id)
            //->whereRaw('("assignment"."Status" = \'Instructions\') OR ("assignment"."Status" = \'Test\')')
            ->whereRaw('((assignment.startdatetime <= \''. date('Y-m-d H:i:s') .'\'')
            ->whereRaw('assignment.enddatetime >= \''. date('Y-m-d H:i:s') .'\' AND assignment.neverexpires = \'1\') OR (assignment.startdatetime <= \''. date('Y-m-d H:i:s') .'\' AND (assignment.neverexpires = \'0\')))')
            ->where('assignment_user.user_id', $user_id)
            ->where('assignment_user.status', '<>', 'completed')
            ->whereRaw('(assignment_user.status = \'test\') OR ((assignment_user.status=\'inprogress\')  )')
            ->where('assignment.delivery_method', '=', 'online')
            ->where('assignment.launchtype','=', 'proctor')
            ->select('assignment.id','assignment.assessment_id','assignment.name','assignment.startdatetime','assignment.enddatetime','assignment.neverexpires')
            ->get();

        $_data['Upcoming'] += $proctorlaunchtestupcoming;
        $_data['Available'] += $proctorlaunchtestavailable;

        $data = $_data;         
        return $data;
    }

    public function getTests($user_id = 0) {
        
        $delivery_method = 'online';
        $user_id = ($user_id > 0) ? $user_id : Auth::user()->id;

        $assessmentStatus =  DB::table('assessment')->join('assignment', 'assessment.id', '=', 'assignment.assessment_id')
                                        ->join('assignment_user', 'assignment.id', '=', DB::raw('assignment_user.assignment_id AND assignment_user.user_id = '. $user_id))
                                        ->where('assignment.delivery_method', $delivery_method)
                                        ->where('assignment_user.user_id', $user_id)                            
                                        ->orderBy("assessment.id", "DESC")
                                        ->orderBy("assignment.id", "DESC")
                                        ->orderBy("assignment.name", "ASC")
                                        ->select(["assignment.name", "assignment_user.status AS AssignmentStatus", "assessment.id AS AssessmentsId",  "assignment.id AS AssignmentId", "assignment.startdatetime AS StartDateTime", "assignment.enddatetime AS EndDateTime", "assignment.neverexpires AS Expires", "assignment.launchtype"])
                                        ->get();
        return $assessmentStatus;
    }
}
