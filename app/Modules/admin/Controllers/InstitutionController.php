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

class InstitutionController extends BaseController {

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
	public function __construct(Institution $institution)
	{
		$this->middleware('auth');
		$this->institution = $institution;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($parent_id = 0)
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;
		$institutions = $this->institution->getInstitutions($parent_id);
        return view('admin::institution.list',compact('institutions'));
	}
    
	public function add()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();

		$id = $parent_id = 0;
		$name = '';

		return view('admin::institution.edit',compact('id','parent_id','name','inst_arr'));
	}

	public function edit($id = 0)
	{
		if(isset($id) && $id > 0)
		{
			$institution = $this->institution->find($id);
			$id = $institution->id; 
			$parent_id = $institution->parent_id; 
			$name = $institution->name; 
		}
		else
		{
			$id = $parent_id = 0;
			$name = '';
		}

		return view('admin::institution.edit',compact('id','parent_id','name'));
	}

	public function update($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->institution->updateInstitution($params);

		return redirect('/user/institution');
	}

	public function delete($id = 0)
	{
		if($id > 0)
		{
			$this->institution->deleteInstitution($id);
		}
		return redirect('/user/institution');
	}
}
