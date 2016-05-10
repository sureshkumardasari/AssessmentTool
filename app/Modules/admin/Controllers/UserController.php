<?php namespace App\Modules\Admin\Controllers;

use Illuminate\Support\Facades\Auth;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Admin\Models\Role;

class UserController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(User $user)
	{
		$this->middleware('auth');
		$this->user = $user;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($institution_id = 0)
	{
		//$institution_id = Auth::user()->institution_id;
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;

		$users=$this->user->getUsers($institution_id);
		//dd($users);
        
        return view('admin::user.list',compact('users'));
	}
    public function profile()
	{
			$userid = Auth::user()->id;
			$this->edit($userid);            
	}

	public function add()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$roles_arr = $this->user->getRoles();

		$id = $institution_id = $role_id = 0;
		$name = $email = $status = $enrollno = $password = '';

		return view('admin::user.edit',compact('id','institution_id','role_id','name','email','status','enrollno','inst_arr','roles_arr','password'));
	}
	public function edit($userid = 0)
	{
		$userid = ($userid > 0) ? $userid :	Auth::user()->id;
		$params = Input::All();
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();

		$roles_arr = $this->user->getRoles();

		if(isset($userid) && $userid > 0)
		{
			$user = $this->user->find($userid);
			$id = $user->id; 
			$role_id = $user->role_id; 
			$institution_id = $user->institution_id; 
			$name = $user->name; 
			$email = $user->email; 
			$enrollno = $user->enrollno; 
			$status = $user->status;
			$password = $user->password;
		}
		else
		{
			$id = $institution_id = $role_id = 0;
			$name = $email = $status = $enrollno = $password = '';
		}

		return view('admin::user.edit',compact('id','institution_id','role_id','name','email','status','enrollno','inst_arr','roles_arr','password'));
	}

	public function update($institutionId = 0)
	{
		$post = Input::All();
		
		$rules = [
                'institution_id' =>'required|not_in:0',
                'role_id' =>'required|not_in:0',
                'name' => 'required|min:3|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
                'enrollno' =>'required'];

		if($post['id'] > 0)
		{
			$rules['name'] = 'required|min:3|unique:users,name,' . $post['id'];
			$rules['email'] = 'required|email|max:255|unique:users,email,' . $post['id'];
		}
        
        $validator = Validator::make($post, $rules);
        
        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        else {
            $params = Input::All();
            //var_dump($params);
            $this->user->updateUser($params);

            return redirect('/user');
        }
        /*
		$params = Input::All();
		//var_dump($params);
		$this->user->updateUser($params);

		return redirect('/user');*/
	}

	public function delete($userid = 0)
	{
		if($userid > 0)
		{
			$this->user->deleteUser($userid);
		}
		return redirect('/user');
	}

	public function roleslist()
	{
		$roles = Role::lists('name', 'id');
        return view('admin::role.list',compact('roles'));
	}
    
	public function roleadd()
	{		
		$id = 0;
		$name = '';
		return view('admin::role.edit',compact('id','name'));
	}

	public function roleedit($id = 0)
	{
		if(isset($id) && $id > 0)
		{
			$Obj = Role::find($id);
			$id = $Obj->id; 
			$name = $Obj->name; 
		}
		else
		{
			$id = 0;
			$name = '';
		}

		return view('admin::role.edit',compact('id','name'));
	}

	public function roleupdate($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->user->updateRole($params);

		return redirect('/user/role');
	}

	public function roledelete($id = 0)
	{
		if($id > 0)
		{
			$this->user->deleteRole($id);
		}
		return redirect('/user/role');
	}
}
