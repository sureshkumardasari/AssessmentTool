<?php namespace App\Modules\Admin\Controllers;

use App\Modules\Resources\Models\Category;
use App\Modules\Resources\Models\Subject;
use Illuminate\Support\Facades\Auth;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;
use Illuminate\Http\JsonResponse;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\Modules\Admin\Models\User;
use Illuminate\Http\Request;
use App\Modules\Admin\Models\Institution;
use Session;
use Db;

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
		 $obj = new User();
 		 $this->user= $obj;
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
		$state_arr = $this->user->getstates();
		$country_arr = $this->institution->getcountries();
		$id = $parent_id = $country_id = 0;
		$name = $address1 = $address2 = $address3 = $city = $state = $phoneno = $pincode = '';
        \Session::flash('flash_message','Information saved successfully.');
		return view('admin::institution.edit',compact('id','parent_id','name','country_id','address1','address2','address3','city','state_arr','state','country_arr','phoneno','pincode','inst_arr'));
	}

	public function edit($id = 0)
	{	
		$state_arr = $this->user->getstates();
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
        \Session::flash('flash_message','Information saved successfully.');

		return view('admin::institution.edit',compact('id','parent_id','name','country_id','address1','address2','address3','city','state_arr','state','country_arr','phoneno','pincode'));
	}

	public function update($id = 0)
	{
		$post = Input::All();
		$messages=[
			'phoneno.required'=>'The Phone no field is required',
		];
		$rules = [
			/*'parent institution_id' => 'required|not_in:0',*/
			'name' => 'required|min:3|unique:institution',
			'address1' => 'required',
			'city' => 'required',
			'state' => 'required|not_in:0',
			'country_id' => 'required|not_in:0',
			'pincode' => 'required|regex:/\b\d{6}\b/',
			//'phoneno' => 'regex: /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/|required'];
			'phoneno'=>array('required','numeric','regex: /^\d{10}$/'),];

		if($post['id'] > 0)
		{
			$rules['name'] = 'required|min:3|unique:institution,name,' . $post['id'];

		}

		$validator=Validator::make($post,$rules,$messages);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		else
		{
			$params = Input::All();
			//var_dump($params);
			$this->institution->updateInstitution($params);
        \Session::flash('flash_message','Information saved successfully.');

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

	public function delete($id )
	{
		$users = User::where('institution_id', $id)->count();

		$cat=Category::where('institution_id',$id)->count();
		$sub=Subject::where('institution_id','category_id',$id)->count();
		if ($users == null && $cat == null && $sub == null) {
			Institution::find($id)->delete();
			\Session::flash('flash_message', 'delete!');
			return redirect('/user/institution');

		} else {
			\Session::flash('flash_message_failed', 'Can not Delete this institution.');
			return Redirect::back();

		}
	}

	public function InstitutionsBulkUpload()
	{
		return view('admin::institution.bulkupload');
	}
	public function bulkInstitutionTemplate(Request $request){

			$userType = $request->input('userType');
			$filename = 'institution_template_' . $userType . '_' . date('Y-m-d') . '.xls';
			$save = $this->institution->bulkInstitutionTemplate($filename, $userType);
			if ($save == null) {
				return Response::json(array('file_name' => "../data/tmp/$filename"));
			} else {
				return Response::json(array('file_name' => false));
			}

	}

    public function bulkInstitutionUpload(Request $request) {


        $userType = $request->input('userType');
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
        return  $some=$this->fileupload($destPath,$destFileName,$userType);
        // return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
    }
    public function fileupload($destPath,$destFileName,$userType){
        //dd($userType);
        //dd($role_id );
        $uploadSuccess = false;
        $orignalHeaders = ['institution_id','institution_name','address1','address2','address3','city','state','phone','pin','country'];
        $getFirstRow = Excel::load($destPath . '/' . $destFileName)->first()->toArray();
        //dd($getFirstRow);
        $uploadedFileHeaders = [];
        if(!empty($getFirstRow[0])){
            $uploadedFileHeaders = array_keys(array_only($getFirstRow[0], $orignalHeaders));
        }
        $headerDifference = array_diff($orignalHeaders, $uploadedFileHeaders);
//dd($orignalHeaders);
		//dd($getFirstRow[0]);
        if(!empty($headerDifference)){
            $error = array('status' => 'error', 'msg' => 'Invalid file.');
            return json_encode($error);
        }
          // echo '<pre>'; print_r($getFirstRow); die;
        // if ($uploadSuccess != false) {
        $errorArray = array();
        //                    try{
        $output = Excel::load($destPath . '/' . $destFileName, function($results)  {
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
                    $status = Institution::validateBulUpload($fileType, $row, $key + 2);
                    if (count($status) > 0) {
                        $this->errorArray = array_merge($this->errorArray, $status);
                    } else {
                        Institution::createBulkInstitutions($row);
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

            return $errorArray = array('status' => 'error', 'msg' => 'Please download error log', 'error_log' => '../data/tmp/errorlog_' . $destFileName);


            //return json_encode($errorArray);

        } else {

            Session::flash('success', 'File uploaded successfully.');
            return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
            // return json_encode($sucessarray);
        }
        //}


    }

}
/*if ($users == 0 && $tasks == 0 && $module == 0) {
	Project::find($id)->delete();
	\Session::flash('flash_message', 'Deleted.');
	return redirect('project_view');

} else {
	\Session::flash('flash_message_failed', 'Can not Delete this Project.');
	return Redirect::back();

}*/
