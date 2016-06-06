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

	public function getAssignUsersInfo($assignment_id=0)
	{
		//$users = User::get();
		$query = DB::table('Users as u')
            ->join('assignment_user as au', function($join){
                $join->on('au.user_id', '=', 'u.id');
            })
            //->join('Roles as r', function($join){
                //$join->on('r.id', '=', 'u.role_id');
            //})->select('Users.name', 'Users.email','Institution.name', 'Roles.name')->get();
            //})
            ->select(DB::raw('u.name as username, u.email as email, u.status as status, u.id as id'));

        if($assignment_id > 0)
        {
        	$query->where("au.assignment_id", $assignment_id);
        }
       

        $users = $query->get();
		return $users;
	}

	 public function complete($aId, $aAId, $status) {

        AssignmentUser::where('assessment_id', $aAId)
                                ->where('user_id', Auth::user()->id)
                                ->update([
                                        'status' => $status,
                                        'takendate' => ($status == 'completed' || $status == 'completed') ? date('Y-m-d H:i:s') : null,
                                    ]);
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
          
          if($results->takendate != "0000-00-00 00:00:00"){
            $results->takendate = date('Y-m-d H:i:s');
          }  
        } else {
            $grade = 'inprogress';
        }
        $results->gradestatus = $grade;
        $results->gradeddate = date('Y-m-d H:i:s'); 
        return $results->save();

    }
}
