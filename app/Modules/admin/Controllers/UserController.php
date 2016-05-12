<?php namespace App\Modules\Admin\Controllers;

use Illuminate\Support\Facades\Auth;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use View;
//use Input;
use Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Admin\Models\Role;
use Maatwebsite\Excel\Facades\Excel;


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
        
        $InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$roles_arr = $this->user->getRoles();

        //return view('admin::user.list',compact('users'));
        return view('admin::user.list', compact('inst_arr','roles_arr'))
        ->nest('usersList', 'admin::user._list', compact('users'));
	}

	public function searchByInstitution($institution_id = 0, $role_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$role_id = (isset($params['role_id'])) ? $params['role_id'] : $role_id;

		$users=$this->user->getUsers($institution_id, $role_id);
		//dd($users);
        
        $from = 'search';
        return view('admin::user._list', compact('users', 'from'));
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
		$country_arr = ['1'=>'India'];

		$id = $institution_id = $role_id = $country_id = 0;
		$name = $email = $status = $enrollno = $password = '';
		$first_name = $last_name = $address1 = $address2 = $address3 = $city = $phoneno = $pincode = $state = '';

		return view('admin::user.edit',compact('id','institution_id','role_id','name','email','status','enrollno','inst_arr','roles_arr','password'
			,'address1','address2','address3','city','state','phoneno','pincode','country_id','country_arr','first_name','last_name'));
	}
	public function edit($userid = 0)
	{
		$userid = ($userid > 0) ? $userid :	Auth::user()->id;
		$params = Input::All();
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$roles_arr = $this->user->getRoles();
		$country_arr = ['1'=>'India'];

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

			$first_name = $user->first_name; 
			$last_name = $user->last_name; 
			$address1 = $user->address1; 
			$address2 = $user->address2; 
			$address3 = $user->address3; 
			$city = $user->city; 
			$state = $user->state; 
			$phoneno = $user->phoneno;
			$pincode = $user->pincode;
			$country_id = $user->country_id;
		}
		else
		{
			$id = $institution_id = $role_id = $country_id = 0;
			$name = $email = $status = $enrollno = $password = '';
			$first_name = $last_name = $address1 = $address2 = $address3 = $city = $phoneno = $pincode = $state = '';
		}

		return view('admin::user.edit',compact('id','institution_id','role_id','name','email','status','enrollno','inst_arr','roles_arr','password'
			,'address1','address2','address3','city','state','phoneno','pincode','country_id','country_arr','first_name','last_name'));
	}

	public function update($institutionId = 0)
	{
		$post = Input::All();
		
		$rules = [
                'institution_id' =>'required|not_in:0',
                'role_id' =>'required|not_in:0',
                //'name' => 'required|min:3|unique:users',
                'first_name' =>'required|min:3',
                'last_name' =>'required',
                'email' => 'required|email|max:255|unique:users',
                'enrollno' =>'required',
                'address1' =>'required',
                'city' =>'required',
                'state' =>'required',
                'phoneno' =>'required',
                'pincode' =>'required',
                'country_id' =>'required'];

		if($post['id'] > 0)
		{
			//$rules['name'] = 'required|min:3|unique:users,name,' . $post['id'];
			$rules['email'] = 'required|email|max:255|unique:users,email,' . $post['id'];

			if($post['password'] != NULL)
			{
				$rules['password'] = 'confirmed|min:6';
			}	
		}
		else
		{
			$rules['password'] = 'required|confirmed|min:6';
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
		$post = Input::All();
		$rules = [
			'name' => 'required|min:3|unique:roles',
		];
		if($post['id'] > 0)
		{
			$rules['name'] = 'required|min:3|unique:roles,name,' . $post['id'];
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
			$this->user->updateRole($params);

			return redirect('/user/role');
		}
	}
	
	public function roleupdateold($id = 0)
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

	public function userBulkUpload()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		return view('admin::user.bulkupload',compact('inst_arr'));
	}

	public function bulkUserTemplate(Request $request)
	{
		$userType = $request->input('userType');
        $institution_id = $request->input('institution_id');

        $filename = 'user_template_' . $userType . '_' . date('Y-m-d') . '.xls';

        $save = $this->user->bulkUserTemplate($filename, $userType,$institution_id, false, true);
        if ($save == null) {
            return Response::json(array('file_name' => "/data/tmp/$filename"));
        } else {
            return Response::json(array('file_name' => false));
        }
	}

	public function bulkUserUpload(Request $request) {        
        
        $institutionId = $request->input('institutionId');
        if (empty($institutionId)) {
            $institutionId = Auth::user()->institution_id;
        }

        $uploadSuccess = false;
        $file = $request->file('file');
        $destFileName = '';

        $fileinfo = ['file' => $file, 'extension' => strtolower($file->getClientOriginalExtension())];

        $validator = \Validator::make($fileinfo, ['file' => 'required|max:5000', 'extension' => 'required|in:xls']);
        
        if ($validator->fails()) {
            $errorArray = array('status' => 'error', 'msg' => 'Invalid file uploaded');
            return json_encode($errorArray);
        }

        if ($file) {
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'xls') {
                $error = array('status' => 'error', 'msg' => 'Upload valid file type.');
                return json_encode($error);
            }
            // Moving the uploaded image to respective directory
            $destPath = public_path() . '/data/tmp';
            $destFileName = str_random(6) . '_' . $file->getClientOriginalName();
            $uploadSuccess = $file->move($destPath, $destFileName);
        } else {
            $errorArray = array('status' => 'error', 'msg' => 'File does not exist');
            return json_encode($errorArray);
        }
        
        $this->errorArray=array();
        return  $some=$this->fileupload($destPath,$destFileName, $institutionId);
        // return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
    }
    public function fileupload($destPath,$destFileName, $institutionId){

        $uploadSuccess = false;
        $orignalHeaders = ['institutionid','enrollment_no','email','password','first_name','last_name','gender','phone','status','address','city','state','country','pin','role'];
        $getFirstRow = Excel::load($destPath . '/' . $destFileName)->first()->toArray();

        $uploadedFileHeaders = [];
        if(!empty($getFirstRow[0])){
            $uploadedFileHeaders = array_keys(array_only($getFirstRow[0], $orignalHeaders));
        }
        $headerDifference = array_diff($orignalHeaders, $uploadedFileHeaders);
        
        if(!empty($headerDifference)){
            $error = array('status' => 'error', 'msg' => 'Invalid file.');
            return json_encode($error);
        }
        //        echo '<pre>'; print_r($getFirstRow); die;
        // if ($uploadSuccess != false) {
        $errorArray = array();
        //                    try{
        $output = Excel::load($destPath . '/' . $destFileName, function($results) use ($institutionId) {
            $phpExcel = $results->setActiveSheetIndex(1);
            $fileType = $phpExcel->getCell('D1')->getValue();
            $phpExcel = $results->setActiveSheetIndex(0);
            $rowCount = $phpExcel->getHighestRow();
            $emptyFile = true;
            if ($rowCount > 1) {
                 
                    $phpExcel = $results->setActiveSheetIndex(0);
                    $firstSheet = $results->get()[0];
                    foreach ($firstSheet as $key => $row) {
                        $arrayCol = $row->toArray();
                        //Ceck Empty Row
                        $rowSize = 0;
                        foreach ($arrayCol as $cell) {
                            $rowSize += strlen($cell);
                        }
                        if ($rowSize == 0) {
                            continue;
                        }
                        //Check Empty Row End
                        $emptyFile = false;
                        $status = User::validateBulUpload($fileType, $row, $key + 2);
                        if (count($status) > 0) {
                            $this->errorArray = array_merge($this->errorArray, $status);
                        } else {
                            User::createBulkUser($fileType, $row, $institutionId);
                        }
                    }
                
            } else {

                $this->errorArray[] = array('Row #' => '', 'Error Description' => 'File is empty');
            }
            if ($emptyFile) {
                $this->errorArray[] = array('Row #' => '', 'Error Description' => 'File is empty');
            }
        });
        //                    }catch(\Exception $e) {
        //                        $this->errorArray[] = array('Row #'=>'','Error Description'=>'You have tried to upload a file with invalid fields.');
        //                    }

        if (count($this->errorArray) > 0) {

            Excel::create('errorlog_' . explode('.', $destFileName)[0], function($excel) use($errorArray) {
                $excel->sheet('error_log', function($sheet) use($errorArray) {
                    $sheet->fromArray($this->errorArray);
                });
            })->store('xls', public_path('data/tmp'), true);

           return $errorArray = array('status' => 'error', 'msg' => 'Please download error log', 'error_log' => '/data/tmp/errorlog_' . $destFileName);


            //return json_encode($errorArray);

        } else {

            Session::flash('success', 'File uploaded successfully.');
            return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
           // return json_encode($sucessarray);
        }
        //}


    }
}
