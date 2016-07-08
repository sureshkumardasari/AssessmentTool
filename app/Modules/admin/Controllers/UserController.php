<?php namespace App\Modules\Admin\Controllers;

use App\Modules\Resources\Models\Assignment;
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
use \Session;
use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Admin\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\S3;
use App\Modules\Admin\Requests\imageRequest;
use App\Modules\Admin\Models\RoleUser;
use App\Modules\resources\Models\AssignmentUser;
//use Mail;

class UserController extends BaseController
{

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

		$users = $this->user->getUsers($institution_id);
		//dd($users);

		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$roles_arr = $this->user->getRoles();


		//return view('admin::user.list',compact('users'));
		return view('admin::user.list', compact('inst_arr', 'roles_arr'))
			->nest('usersList', 'admin::user._list', compact('users'));
	}

	public function usersJson($institution_id = 0)
	{
		$params = Input::All();
		//dd($params);
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : 0;
		//$institution_id = ($institution_id > 0) ? $institution_id :	Auth::user()->institution_id;
		if ($institution_id > 0) {
			$users = $this->user->getUsers($institution_id, 2);
		} else {
			$users = [];
		}

		return json_encode($users);
	}

	public function searchByInstitution($institution_id = 0, $role_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$role_id = (isset($params['role_id'])) ? $params['role_id'] : $role_id;

		$users = $this->user->getUsers($institution_id, $role_id);
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
		$country_arr = $this->user->getcountries();
		$state_arr = $this->user->getstates();

		$id = $institution_id = $role_id = $country_id = $state = 0;
		$name = $email = $status = $gender = $enrollno = $password = '';
		$first_name = $last_name = $address1 = $address2 = $address3 = $city = $phoneno = $pincode = $state = $profile_picture = '';

		$profile_picture = $this->getProfilePicURL();
		$pic_data = [];
		return view('admin::user.edit', compact('id', 'institution_id', 'role_id', 'name', 'email', 'status', 'gender', 'enrollno', 'inst_arr', 'roles_arr', 'password'
			, 'address1', 'address2', 'address3', 'city', 'state', 'state_arr', 'phoneno', 'pincode', 'country_id', 'country_arr', 'first_name', 'last_name', 'profile_picture', 'pic_data'));
	}

	public function edit($userid = 0)
	{
		$userid = ($userid > 0) ? $userid : Auth::user()->id;
		$params = Input::All();
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$roles_arr = $this->user->getRoles();
		$country_arr = $this->user->getcountries();
		$state_arr = $this->user->getstates();

		$pic_data = [];
		if (isset($userid) && $userid > 0) {
			$user = $this->user->find($userid);
			$id = $user->id;
			$role_id = $user->role_id;
			$institution_id = $user->institution_id;
			$name = $user->name;
			$email = $user->email;
			$enrollno = $user->enrollno;
			$status = $user->status;
			$gender = $user->gender;
			$password = $user->password;
			$first_name = $user->first_name;
			$last_name = $user->last_name;
			$address1 = $user->address1;
			$address2 = $user->address2;
			$address3 = $user->address3;
			$city = $user->city;
//			$state = $user->state;
			$phoneno = $user->phoneno;
			$pincode = $user->pincode;
			$state = $user->state;
			$country_id = $user->country_id;
			//$profile_picture = $user->profile_picture;
			$profile_picture = $this->getProfilePicURL($user->profile_picture);
			$pic_data = ['coords' => $user->pic_coords, 'image' => $user->profile_picture, 'id' => $user->id];
		} else {
			$id = $institution_id = $role_id = $country_id = $state = 0;
			$name = $email = $status = $enrollno = $password = '';
			$first_name = $last_name = $address1 = $address2 = $address3 = $city = $phoneno = $pincode = $state = $profile_picture = '';
		}

		return view('admin::user.edit', compact('id', 'institution_id', 'role_id', 'name', 'email', 'status', 'gender', 'enrollno', 'inst_arr', 'roles_arr', 'password'
			, 'address1', 'address2', 'address3', 'city', 'state', 'state_arr', 'phoneno', 'pincode', 'country_id', 'country_arr', 'first_name', 'last_name', 'profile_picture', 'pic_data'));
	}

	public function update($institutionId = 0)
	{
		$post = Input::All();

		$rules = [
			'institution_id' => 'required|not_in:0',
			//'name' => 'required|min:3|unique:users',
			'first_name' => 'required|min:3',
			'last_name' => 'required',
			'email' => 'required|email|max:255|unique:users',
			'enrollno' => 'required',
			'address1' => 'required',
			'city' => 'required',
			'pincode' => 'required|regex:/\b\d{6}\b/',
			//'phoneno' => 'regex: /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/|required',
			'phoneno' => array('required', 'numeric', 'regex: /^\d{10}$/'),
			'gender' => 'required',
			'state' => 'required',
			'country_id' => 'required'];

		if ($post['id'] > 0) {
			//$rules['name'] = 'required|min:3|unique:users,name,' . $post['id'];
			$rules['email'] = 'required|email|max:255|unique:users,email,' . $post['id'];

			if ($post['password'] != NULL) {
				$rules['password'] = 'confirmed|min:6';
			}
		} else {
			$rules['role_id'] = 'required|not_in:0';
			$rules['password'] = 'required|confirmed|min:6';
		}

		$validator = Validator::make($post, $rules);

		if ($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		} else {
			$params = Input::All();
			//var_dump($params);
			$this->user->updateUser($params);
			if($params['status']=='Active'){
					$create = array(
                           'name'=>$params['first_name'],
                            'email'=>$params['email'],
                            );
                    $data = array(         
                            'email' =>$params['email'],
                           'name' =>$params['first_name'],
 
                    );
                    if(getenv('mail_send')=='yes')
{
  Mail::send('emails.user_active', $data, function($message) use ($create){
                        $message->to($create['email'],$create['name'])->subject('User Activated');
                    });}
                    
                   
			}

			return redirect('/user');
		}
		/*
        $params = Input::All();
        //var_dump($params);
        $this->user->updateUser($params);

        return redirect('/user');*/
	}

	public function delete($userid)
	{
			$assign = AssignmentUser::where('user_id', $userid)->count();
			//dd($assign);
			if ($assign == null ) {
				User::find($userid)->delete();
				\Session::flash('flash_message', 'delete!');

				return redirect('/user');

			} else {
				\Session::flash('flash_message_failed', 'Can not Delete this User.');
				return Redirect::back();

			}
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

	public function roledelete($id)
	{
		/*if($id > 0)
		{
			$this->user->deleteRole($id);
		}*/
		$users = User::where('role_id', $id)->count();
		//dd($users);
		if ($users == null ) {
			Role::find($id)->delete();
			\Session::flash('flash_message', 'delete!');

			return redirect('/user/role');

		} else {
			\Session::flash('flash_message_failed', 'Can not Delete this role.');
			return Redirect::back();

		}
	//	return redirect('/user/role');
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
			return Response::json(array('file_name' => "data/tmp/$filename"));
		} else {
			return Response::json(array('file_name' => false));
		}
	}

	public function bulkUserUpload(Request $request) {

		$institutionId = $request->input('institutionId');
		$userType = $request->input('userType');

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
		return  $some=$this->fileupload($destPath,$destFileName, $institutionId, $userType);
		// return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
	}
	public function fileupload($destPath,$destFileName, $institutionId, $userType){
		//dd($userType);
		$role_id = 0;
		if($userType == 'student' || $userType == 'Student')
		{
			$role_id = $this->user->getRoleIdByRole($userType);
		}
		//dd($role_id );
		$uploadSuccess = false;
		$orignalHeaders = ['institutionid','enrollment_no','email','password','first_name','last_name','phone','status','address','city','state','gender','country','pin','role'];
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
		$output = Excel::load($destPath . '/' . $destFileName, function($results) use ($role_id, $institutionId) {
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
						User::createBulkUser($role_id, $row, $institutionId);
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

			return $errorArray = array('status' => 'error', 'msg' => 'Please download error log', 'error_log' => 'data/tmp/errorlog_' . $destFileName);


			//return json_encode($errorArray);

		} else {

			Session::flash('success', 'File uploaded successfully.');
			return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
			// return json_encode($sucessarray);
		}
		//}


	}

	function getProfilePicURL($profile_picture = '')
	{
		if($profile_picture != NULL)
		{
			if(getenv('s3storage'))
			{
				$profile_pic = getS3ViewUrl($profile_picture, 'user_profile_pic_128');
			}
			else
			{
				$profile_pic = asset('/data/uploaded_images/128x128/'.$profile_picture);
			}
		}
		else
		{
			$profile_pic = asset('/images/profile_pic.jpg');
		}
		return $profile_pic;
	}

	/**
	 * Processing user profile pic.
	 *
	 * @return json.
	 * @author Sireesha
	 */
	public function uploadImage(Request $request, imageRequest $imageRequest) {
		$fileName = '';
		$file = $request->file('image');
		$extension = $file->getClientOriginalExtension();
		$dimensions = getimagesize($file);
		$fileName = time() . '.' . $extension;
		$destinationPath = public_path('/data/uploaded_images/orignal/');

		if (!file_exists('data/uploaded_images/orignal/')) {
			mkdir('data/uploaded_images/orignal/', 0777, true);
		}
		$file->move($destinationPath, $fileName);

		$resizePath = public_path() . '/data/uploaded_images/400x400/';
		if ($dimensions[0] > 400 || $dimensions[1] > 400) {
			resizeImage($destinationPath, $fileName, $resizePath, 400, 400, $ratio = true);
		} else {
			copy($destinationPath . $fileName, $resizePath . $fileName);
		}

		// Move the file to S3
		$orignalFilePath = $destinationPath.$fileName;
		$resizedFilePath = $resizePath.$fileName;

		$resized_pic_url = asset('/data/uploaded_images/400x400/'.$fileName);
		if(getenv('s3storage'))
		{
			$s3 = new \App\Models\S3();
			$s3->uploadByPath( $orignalFilePath, 'user_profile_pic_orignal');
			$s3->uploadByPath( $resizedFilePath, 'user_profile_pic_400');
			$orignal_pic_url = $s3->getFileUrl($fileName, 'user_profile_pic_orignal');
			$resized_pic_url = $s3->getFileUrl($fileName, 'user_profile_pic_400');

			unlink($orignalFilePath);
			unlink($resizedFilePath);
		}

		// unlink($filePath);
		return array('filename' => $fileName, 'file_path'=>$resized_pic_url);
	}

	/**
	 * croping and resizing user selected area of pic and updating profile info if editing user.
	 *
	 * @return json.
	 * @author Sireesha
	 */
	public function saveCrop(Request $request) {
		$inputs = $request->input();
		$savePath = 'data/uploaded_images/croped/';
		if (!file_exists('data/uploaded_images/croped/')) {
			mkdir('data/uploaded_images/croped/', 0777, true);
		}
		$resizeArray = array('192x192' => '192x192', '128x128' => '128x128', '48x48' => '48x48', '80x80' => '80x80', '103x103' => '103x103');

		cropImage($inputs, $savePath, $resizeArray);

		$coords = implode(',', array_except($inputs['coords'], array('x2', 'y2')));
		$inputs['coords'] = $coords;
		if (!empty($inputs['user_id'])) {
			$user = User::find($inputs['user_id']);
			$user->profile_picture = $inputs['image_name'];
			$user->pic_coords = $inputs['coords'];
			$user->save();
		}
		$resizeArray = array('192x192' => 'user_profile_pic_192', '128x128' => 'user_profile_pic_128', '48x48' => 'user_profile_pic_48', '80x80' => 'user_profile_pic_80', '103x103' => 'user_profile_pic_103');

		if(getenv('s3storage'))
		{
			if (!empty($resizeArray)) {
				$s3 = new \App\Models\S3();
				foreach ($resizeArray as $folder => $s3Path) {
					$resizedPath = public_path() . '/data/uploaded_images/' . $folder . '/'.$inputs['image_name'];
					$s3->uploadByPath( $resizedPath, $s3Path);
					unlink($resizedPath);
				}
				$image192x192fromS3 = $s3->getFileUrl($inputs['image_name'], 'user_profile_pic_192');
				$inputs['image_path_s3'] = $image192x192fromS3;
			}
		}
		else
		{
			if (!empty($resizeArray)) {
				foreach ($resizeArray as $folder => $s3Path) {
					$resizedPath = public_path() . '/data/uploaded_images/' . $folder . '/'.$inputs['image_name'];
				}
				$image192x192fromS3 = asset('/data/uploaded_images/192x192/'.$inputs['image_name']);
				$inputs['image_path_s3'] = $image192x192fromS3;
			}
		}
		return new JsonResponse($inputs);
	}
	public function downloadExcel($type)
	{
		$post = Input::all();
		$data = Institution::join('users', 'institution.id', '=', 'users.institution_id')
			->join('roles', 'roles.id', '=', 'users.role_id')
			->join('countries', 'countries.id', '=', 'users.country_id')
		    ->join('states','states.id', '=', 'users.state');
		if($post['institution_id']!=0){
			$data->where('users.institution_id',$post['institution_id']);
		}
		if($post['role_id']!=0){
			$data->where('users.role_id',$post['role_id']);
		}
		$data=$data->select('email','institution.name as Instname','roles.name as rolesname',
			'country_name', 'first_name','last_name','status','gender', 'enrollno','users.created_at',
			'users.address1','users.city','state_name','users.phoneno','users.pincode')
			->get()->toArray();
		return Excel::create('user.list', function ($excel) use ($data)
		{
			$excel->sheet('mySheet', function ($sheet) use ($data) {
				$sheet->fromArray($data);
			});
		})->download($type);
	}
	public function download()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$roles_arr = $this->user->getRoles();
		return view('admin::user.download', compact('inst_arr','roles_arr'));
	}
}
