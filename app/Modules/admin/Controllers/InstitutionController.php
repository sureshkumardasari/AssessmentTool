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

		$country_arr = $this->institution->getcountries();
		$id = $parent_id = $country_id = 0;
		$name = $address1 = $address2 = $address3 = $city = $state = $phoneno = $pincode = '';

		return view('admin::institution.edit',compact('id','parent_id','name','country_id','address1','address2','address3','city','state','country_arr','phoneno','pincode','inst_arr'));
	}

	public function edit($id = 0)
	{
		$country_arr = $this->institution->getcountries();
		if(isset($id) && $id > 0)
		{
			$institution = $this->institution->find($id);
			$id = $institution->id; 
			$parent_id = $institution->parent_id; 
			$name = $institution->name; 

			$country_id = $institution->country_id; 
			$address1 = $institution->address1; 
			$address2 = $institution->address2; 
			$address3 = $institution->address3; 
			$city = $institution->city; 
			$state = $institution->state; 
			$phoneno = $institution->phoneno;
			$pincode = $institution->pincode;
		}
		else
		{
			$id = $parent_id = $country_id = 0;
			$name = $address1 = $address2 = $address3 = $city = $state = $phoneno = $pincode = '';
		}

		return view('admin::institution.edit',compact('id','parent_id','name','country_id','address1','address2','address3','city','state','country_arr','phoneno','pincode'));
	}

	public function update($id = 0)
	{
		$post = Input::All();

		$rules = [
			/*'parent institution_id' => 'required|not_in:0',*/
			'name' => 'required|min:3|unique:institution',
			'address1' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country_id' => 'required|not_in:0',
			'pincode' => 'required',
			'phoneno' => 'required',];

		if($post['id'] > 0)
		{
			$rules['name'] = 'required|min:3|unique:institution,name,' . $post['id'];

		}

		$validator = Validator::make($post, $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		else
		{
			$params = Input::All();
			//var_dump($params);
			$this->institution->updateInstitution($params);

			return redirect('/user/institution');
		}
	}
	
	public function updateold($id = 0)
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
