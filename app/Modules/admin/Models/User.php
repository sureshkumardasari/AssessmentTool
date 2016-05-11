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
}
