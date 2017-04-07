<?php namespace App\Modules\Resources\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Html\HtmlFacade;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\Assessment;
use DB;
use Mail;
use App\Modules\Admin\Models\Role;
class AssignmentController extends BaseController {

	

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');

		$obj = new Assignment();
		$this->assignment = $obj;

		$obj = new Institution();
		$this->institution = $obj;

		$obj = new User();
		$this->user = $obj;

		$obj = new Assessment();
		$this->assessment = $obj;

		$obj = new AssignmentUser();
		$this->assignmentuser = $obj;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function InstutionList($id=0)
	{

		$assignments = DB::table('assignment')
				->join('assessment', 'assessment.id', '=', 'assignment.assessment_id');
		if($id!=0){
			$assignments->where('assignment.institution_id',$id);
		}

		$result=$assignments->select('assignment.id','assignment.name','assessment.name as assessment_name','assignment.startdatetime','assignment.gradestatus')->get();
		//dd($assignments);
		return $result;
	}
	public function assignment($parent_id = 0)
	{
		if (getRole() == "student") {
     return view('permission');
    }
		if(getRole()!= "administrator") {
			$uid = \Auth::user()->institution_id;
			//dd($uid);
			$assignments = DB::table('assignment')
					->join('assessment', 'assessment.id', '=', 'assignment.assessment_id')
					->where('assignment.institution_id', '=', $uid)
					->select('assignment.id', 'assignment.name', 'assessment.name as assessment_name', 'assignment.startdatetime', 'assignment.enddatetime', 'assignment.status','assignment.neverexpires')->get();
			//dd($assignments);
		}
		else{
			$assignments = DB::table('assignment')
					->join('assessment', 'assessment.id', '=', 'assignment.assessment_id')
					->select('assignment.id', 'assignment.name', 'assessment.name as assessment_name', 'assignment.startdatetime', 'assignment.enddatetime', 'assignment.status','assignment.neverexpires')->get();
			//dd($assignments);
		}
        return view('resources::assignment.list',compact('assignments'));
	}

	public function assignmentadd()
	{
		
		//dd("123");
		//$inst_arr = $this->institution->getInstitutions();

		$id = 0;
		$assignment = new assignment();

		$assessments_arr =[];// Assessment::lists('name','id');
		$users=Auth::user();
		$teacher_and_admin_role_id=Role::where('name','!=','administrator')->where('name','!=','student')->lists('id');

		$assessment_id = 0;
		$institution_id = 0;
		$proctor_id = 0;
	//	$users=Auth::user();
		$inst_id=$users->institution_id;
		$grader_id=0;
		  if(getRole()!='administrator'){
			  $institution_arr = Institution::where('id',$users->institution_id)->lists('name','id');
			  $proctor_arr  = $this->user->getUsersOptionList($institution_id,$teacher_and_admin_role_id);// for proctor 
			$grader=$this->user->getUsersOptionList($inst_id,$teacher_and_admin_role_id);//displaying teachers
			$assessments_arr = Assessment::where('institution_id',$users->institution_id)->lists('name','id');
		  }
		else{
			$institution_arr = $this->institution->getInstitutions();
			$proctor_arr= [];
			$grader= [];

			
		}
		

		//var_dump($proctor_arr); die();

		$assignmentUsersJson	= "[{}]";
		        \Session::flash('flash_message','Information saved successfully.');

		return view('resources::assignment.edit',compact('assignment','grader_id','grader','assessments_arr','assessment_id','proctor_arr','proctor_id','institution_arr','institution_id','assignmentUsersJson'));
	}

	public function GraderList($id)
	{
		$teacher_and_admin_role_id=Role::where('name','!=','administrator')->where('name','!=','student')->lists('id');
		$grader=$this->user->getGrader($id,$teacher_and_admin_role_id);
		// dd($grader);
		return $grader;

	}

	public function GetAssessment($id)
	{
		$assessment=$this->assessment->getAssessment($id);
		return $assessment;
	}

	public function assignmentedit($id = 0)
	{		
		//$inst_arr = $this->assignment->getAssignments();

		if(isset($id) && $id > 0)
		{
			$assignment = $this->assignment->find($id);


			$assignmentUsersArr = 	$this->assignmentuser->getAssignUsersInfo($id);	
			//print_r($assignmentUsersArr);
			$assignmentUsersJson = json_encode($assignmentUsersArr);
		}
		else
		{
			$assignment = Input::All();		
			$assignmentUsersJson	= "[{}]";
		}
		$assessments_arr = Assessment::lists('name','id');
		$teacher_role_id=Role::where('name','teacher')->first()->id;

		$institution_arr = $this->institution->getInstitutions();			
		$grader= $this->user->getUsersOptionList($assignment->institution_id,$teacher_role_id);
		$proctor_arr  = $this->user->getUsersOptionList($assignment->institution_id,$teacher_role_id);// for proctor displaying teachers
				      //  \Session::flash('flash_message','Information saved successfully.');

		return view('resources::assignment.edit',compact('assignment','grader','assessments_arr','proctor_arr','institution_arr','assignmentUsersJson'));
	}

	public function assignmentview($id = 0)
	{		
		//$inst_arr = $this->assignment->getAssignments();

		if(isset($id) && $id > 0)
		{
			$assignment = $this->assignment->getDetails($id);
//var_dump($assignment); exit;
			$assignmentUsersArr = 	$this->assignmentuser->getAssignUsersInfo($id);	
			//print_r($assignmentUsersArr);
			//$assignmentUsersJson = json_encode($assignmentUsersArr);
		}
		else
		{
			$assignment = Input::All();		
			$assignmentUsersArr	= array();
		}
		if (!is_dir(public_path('data/assessment_pdf/'))) {
			@mkdir(public_path('data/assessment_pdf/'), 0777, true);
		}

		//$assessments_arr = Assessment::lists('name','id');
		
		
		//$institution_arr = $this->institution->getInstitutions();			

		//$proctor_arr  = $this->user->getUsersOptionList($assignment->institution_id,3);// for proctor displaying teachers
		
		return view('resources::assignment.view',compact('assignment','assignmentUsersArr'));
	}

	public function assignmentupdate($id = 0)
	{
		$params = Input::All();
   		$rules = [
			'name' => 'required|min:3',
			'assignment_text' =>'required',
			'startdatetime' =>'required',
			//'enddatetime' => 'required',
			'assessment_id' =>'required|not_in:0',
			'institution_id' =>'required|not_in:0',
			'student_ids' =>'required|array',
			'launchtype' =>'required',
			'grader_id'=>'required|not_in:0',
			'delivery_method' =>'required'];

		if(!isset($params['neverexpires']))
		{
			$rules['enddatetime'] = 'required';
		}
		if($params['launchtype']=="proctor"){
			$rules['launchtype'] = 'required|not_in:0';
		}
		$validator = Validator::make($params, $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		else {
			$user_list=[];
 			$num = Assignment::where('institution_id', $params['institution_id'])->where('name', $params['name'])->wherenotin('id',[$params['id']])->count();
					if($params['student_ids']){
				foreach ($params['student_ids'] as $key => $value) {
					array_push($user_list,$value);
				}
 				$assignment_name=$params['name'];
  			 	$user_list_details=User::wherein('id',$user_list)->select('users.name as user_name', 'users.email as email')->get()->toArray();
 				foreach ($user_list_details as $key => $value) {
					$user_list_details[$key]['assignment_name']=$params['name']; 
					$user_list_details[$key]['startdatetime']=$params['startdatetime']; 
					$user_list_details[$key]['enddatetime']=$params['enddatetime']; 
				}
				$create=[];
				foreach ($user_list_details as $key => $create) {
					$create=$user_list_details;
 				}
     				foreach ($user_list_details as $key => $value) { 
    					foreach ($create as $key => $values) {
    						 if(getenv('mail_send')=='yes')
{
   Mail::send('emails.assignment_users', $value, function($message) use ($values){
                        	$message->to($values['email'],$values['assignment_name'])->subject('Assignment Assigned users');
                    		});	}
     						
 					}
 				} 
			}
			if ($num > 0) {
				return Redirect::back()->withInput()->withErrors("Assignment already entered");
			} 
			$this->assignment->updateassignment($params);
					        \Session::flash('flash_message','Information saved successfully.');

			return redirect('/resources/assignment');
		}		
	}

	public function assignmentdelete($id = 0)
	{
		if($id > 0)
		{
			$assignment=$this->assignment->deleteassignment($id);
  			if($assignment->status=='upcoming'){
    	  	$assignment = Assignment::find($id);
			$assignment->delete();
			$assignment_users=AssignmentUser::where('assignment_user.assignment_id','=',$id)->select('id')->get();
			foreach ($assignment_users as $key => $value) {
			$assignment = AssignmentUser::find($value['id']);
			$assignment->delete();
			}
			\Session::flash('flash_message', 'Assignment successfully deleted!');
        	}elseif($assignment->status=='completed'){
	            \Session::flash('flash_message_failed', 'Assignment completed Cont able to Delete!');
	        }else{
	            \Session::flash('flash_message_failed', 'Assignment Inprocess Cont able to Delete!');
	        }
				
		}
		return redirect('/resources/assignment');
	}

	public function view($id = null) {       
       $name = $assignment_text = $assignment_lines = $status = '';
        if (empty($id)) {
            return view('resources::assignment.view',compact('id','name','assignment_text','assignment_lines','status'));
        } else {
            $assignment = Assignment::find($id);
            //        dd($assignment->subjects);
            return view('resources::assignment.view', compact('assignment'));
        }
    }
    public function getAssignUsersInfo($assignment_id = 0)
	{
		$params = Input::All();
		//dd($params);
		$assignment_id = (isset($params['assignment_id'])) ? $params['assignment_id'] : 0;
		//$institution_id = ($institution_id > 0) ? $institution_id :	Auth::user()->institution_id;
		if($assignment_id > 0)
		{
			$users=$this->assignmentuser->getAssignUsersInfo($assignment_id);	
		}
		else
		{
			$users = [];
		}	

		return json_encode($users);
	}
	public function getunAssignUsersInfo($assignment_id = 0, $institution_id = 0)
	{
		$params = Input::All();
		//dd($params);
		$assignment_id = (isset($params['assignment_id'])) ? $params['assignment_id'] : 0;
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : 0;
		if($assignment_id > 0)
		{
			$users=$this->assignmentuser->getunAssignUsersInfo($assignment_id, $institution_id);	
		}
		else
		{
			$users = [];
		}	

		return json_encode($users);
	}

	public function updateAssignmentUserStatus($auid = 0, $status = '')
	{
		//echo $auid;
		if($auid > 0 && $status != '')
		{
			$data = $this->assignmentuser->find($auid);
			$data->status = $status;
			$data->save();
		}
	}
}
