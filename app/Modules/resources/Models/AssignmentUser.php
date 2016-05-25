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

	
}
