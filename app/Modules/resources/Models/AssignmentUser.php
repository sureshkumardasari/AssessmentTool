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
use App\Modules\Resources\Models\Assessment;
use Mail;
 class AssignmentUser extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    
	protected $table = 'assignment_user';
	protected $primaryKey = 'id';

	public function getAssignmentUser($assignment_id = 0)
	{
		//$users = User::get();
		$obj = new AssignmentUser();
		if($assignment_id > 0)
		{
			$users = $obj->where("assignment_id", $assignment_id)->lists('user_id', 'id');
		}
		else
		{
			$users = $obj->lists('user_id', 'id');
		}
		
		return $users;
	}

	public function getAssignusersInfo($assignment_id=0)
	{
		//$users = User::get();
		$query = DB::table('users as u')
            ->join('assignment_user as au', function($join){
                $join->on('au.user_id', '=', 'u.id');
            })
            //->join('Roles as r', function($join){
                //$join->on('r.id', '=', 'u.role_id');
            //})->select('users.name', 'users.email','Institution.name', 'Roles.name')->get();
            //})
            ->select(DB::raw('u.name as username, u.email as email, u.status as status, u.id as id, au.status,au.takendate,au.rawscore, au.id as auid'));

        if($assignment_id > 0)
        {
        	$query->where("au.assignment_id", $assignment_id);
        }
       

        $users = $query->get();
		return $users;
	}

    public function getAssignmentUserStatus($aId, $aAId)
    {
        $res = AssignmentUser::where('assessment_id', $aId)->where('assignment_id', $aAId)->where('user_id', Auth::user()->id)->first();
        //dd($res);
        return ($res != null) ? $res->status : '';
    }
	public function complete($aId, $aAId, $status) {
         AssignmentUser::where('assignment_id', $aAId)->where('assessment_id',$aId)
                                ->where('user_id', Auth::user()->id)
                                ->update([
                                        'status' => $status,
                                        'isgraded' => ($status == 'completed') ? true : false,
                                        'takendate' => ($status == 'completed') ? date('Y-m-d H:i:s') : null,
                                    ]);
        
        $details = AssignmentUser::join('assignment as assign', 'assign.id','=','assignment_user.assignment_id')
                                ->join('users as u','u.id','=','assignment_user.user_id')
                                ->where('assignment_user.assignment_id', $aId)
                                ->select('assign.name as assign_name','u.first_name as first_name','u.email as email')
                               ->get()->toArray();
        $total_details=[];                      
        foreach ($details as $key => $value) {
            $total_details=$value;
        } 
        $create=[];
                foreach ($total_details as $key => $create) {
                    $create=$total_details;
        }    
        if(getenv('mail_send')=='yes'){
        Mail::send('emails.assignment_complete', $total_details, function($message) use ($create){
            $message->to($create['email'],$create['first_name'])->subject('Assignment Completed');
        });}
    }
    public function updateUserGradeStatus($params){

      $obj =  $this->where('assignment_id',$params['assignment_id'])->where('user_id',$params['user_id'])->first();
      
      if( count($obj) ){
        $obj->gradestatus =  $params['status'];
        $obj->save();
      }

    }
    public function updateAssignmentRecords($filters){
        $results = $this->where('assignment_id',$filters['assignment_id'] )->where('user_id',$filters['user_id'])->first();
        $grade = "completed";
        $results->rawscore = $filters['rawscore'];
        
        $user = Auth::user();
        $userId = !empty( $user->id ) ? $user->id : 0;

        $results->grader_id    = $userId;
        $results->percentage  = $filters['percentage'];
        if(isset($filters['grade'])&&!empty($filters['grade'])){
          $results->grade = $filters['grade'];
        } else{
          $results->grade= null;
        }

        if( isset( $filters['status']) && !empty($filters['status']) ){
          $results->status = $filters['status'];
          
          // if($results->takendate != "0000-00-00 00:00:00"){
          //   $results->takendate = date('Y-m-d H:i:s');
          // }  
        } else {
            $grade = 'inprogress';
        }
        $results->gradestatus = $grade;
        $results->gradeddate = date('Y-m-d H:i:s'); 
        // $results->takendate = date('Y-m-d H:i:s');
        return $results->save();

    }

    public function updateAssignmentuserstatus()
    {
        $subsectionTimeType = 'Minutes';
                                
        $subsectionuserstatuses = AssignmentUser::join('assignment', 'assignment.id','=','assignment_user.assignment_id')
            ->join('assessment', function($query){ 
                $query->on('assessment.id','=','assignment.assessment_id')
                ->where('neverexpires','=','0')
                ->where('totaltime','>','0');
            })
            ->where('isgraded', false)->whereNotNull('starttime')->where('starttime','>', 0)->limit(5)->get();
        //initilizing grade
        $grade = new Grade;

        /*********start foreach********/
        foreach($subsectionuserstatuses as $subsectionuserstatus){
            //getting section start time
            $sectionStartTime = strtotime($subsectionuserstatus->starttime);
            //getting current time
            $currentTime = time();
            //getting time passed
            $timePassed = $currentTime - $sectionStartTime;
            //getting subsection total time
            $subsectionTotalTime = $subsectionuserstatus->totaltime;
            if($subsectionTimeType == 'Minutes'){
                $subsectionTotalTime = $subsectionTotalTime * 60;
            }
            else{
                if($subsectionTimeType == 'Hours'){
                    $subsectionTotalTime = $subsectionTotalTime * 60 * 60;
                }
            }

            if($timePassed >= 0 && $timePassed > $subsectionTotalTime){
                //getting assessmentAssignmentUser
                $assignmentStartedSection = $assessmentAssignmentUser = AssignmentUser::where('assignment_id', '=', $subsectionuserstatus->assignment_id)
                          ->where('user_id', $subsectionuserstatus->user_id)
                          ->where('assessment_id', $subsectionuserstatus->assessment_id)->first();

                $assessmentAssignmentUser->gradeprogress = 'processed';
                $assessmentAssignmentUser->save();

                 if($assignmentStartedSection->status == 'completed'){
                    $grade->gradeSystemStudents([
                       'assessment_id' => $assignmentStartedSection->assessment_id,
                       'assignment_id' => $assignmentStartedSection->assignment_id,
                        'user_id'       => $assignmentStartedSection->user_id
                       ]);
                    $assignmentStartedSection->isgraded = true;
                    $assignmentStartedSection->save();
                 }
                 else{ 
                    if($assignmentStartedSection->unlimitedtime != '1'){
                        //getting section start time
                        $sectionStartTime = strtotime($assignmentStartedSection->starttime);
                        //getting current time
                        $currentTime = time();
                        //getting time passed
                        $timePassed = $currentTime - $sectionStartTime;
                        //getting subsection total time
                        $subsectionTotalTime = $assignmentStartedSection->TotalTime;
                        
                        if($subsectionTimeType == 'Minutes'){
                            $subsectionTotalTime = $subsectionTotalTime * 60;
                        }
                        else{
                            if($subsectionTimeType == 'Hours'){
                                $subsectionTotalTime = $subsectionTotalTime * 60 * 60;
                            }
                        }
                        if($timePassed >= 0 && $timePassed > $subsectionTotalTime){
                             $grade->gradeSystemStudents([
                               'assessment_id' => $assignmentStartedSection->assessment_id,
                               'assignment_id' => $assignmentStartedSection->assignment_id,
                                'user_id'       => $assignmentStartedSection->user_id
                               ]);
                            $assignmentStartedSection->isgraded = true;
                            $assignmentStartedSection->status = 'completed';
                            $assignmentStartedSection->save();
                        }
                    }
                 }
            }

        }
        /*********end foreach********/
    }
    public function getunAssignusersInfo($assignment_id=0, $institution_id = 0)
    {
        $query = DB::table('users as u')
            ->leftjoin('assignment_user as au', function($join) use($assignment_id) {
                $join->on('au.user_id', '=', 'u.id')->where("au.assignment_id", '=', $assignment_id);
            })
            ->select(DB::raw('u.name as username, u.email as email, u.status as status, u.id as id'));

        if($assignment_id > 0)
        {            
            $query->where("u.institution_id", $institution_id)->where("u.role_id",'=',2);
            $query->whereNull("au.user_id");
        }      

        $users = $query->get();
       // dd($users);
        return $users;
    }
}
