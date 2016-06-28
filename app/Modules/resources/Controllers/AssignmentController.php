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

		$obj = new AssignmentUser();
		$this->assignmentuser = $obj;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}

	public function assignment($parent_id = 0)
	{
		$assignments = DB::table('assignment')
			->join('assessment', 'assessment.id', '=', 'assignment.assessment_id')
			->select('assignment.id','assignment.name','assessment.name as assessment_name','assignment.startdatetime')->get();
		//dd($assignments);
        return view('resources::assignment.list',compact('assignments'));
	}

	public function assignmentadd()
	{
		
		//dd("123");
		//$inst_arr = $this->institution->getInstitutions();

		$id = 0;
		$assignment = new assignment();

		$assessments_arr = Assessment::lists('name','id'); 


		$assessment_id = 0;
		  
		$institution_arr = $this->institution->getInstitutions();	
		$institution_id = 0;

		$proctor_arr  = $this->user->getUsersOptionList($institution_id,3);// for proctor displaying teachers
		$proctor_id = 0;

		//var_dump($proctor_arr); die();

		$assignmentUsersJson	= "[{}]";

		return view('resources::assignment.edit',compact('assignment','assessments_arr','assessment_id','proctor_arr','proctor_id','institution_arr','institution_id','assignmentUsersJson'));
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
		
		
		$institution_arr = $this->institution->getInstitutions();			

		$proctor_arr  = $this->user->getUsersOptionList($assignment->institution_id,3);// for proctor displaying teachers
		
		return view('resources::assignment.edit',compact('assignment','assessments_arr','proctor_arr','institution_arr','assignmentUsersJson'));
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
			'delivery_method' =>'required'];

		if(!isset($params['neverexpires']))
		{
			$rules['enddatetime'] = 'required';
		}

		$validator = Validator::make($params, $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		else {
 			$num = Assignment::where('institution_id', $params['institution_id'])->where('name', $params['name'])->wherenotin('id',[$params['id']])->count();
			if ($num > 0) {
				return Redirect::back()->withInput()->withErrors("Assignment already entered");
			} 
			$this->assignment->updateassignment($params);
			return redirect('/resources/assignment');
		}		
	}

	public function assignmentdelete($id = 0)
	{
		if($id > 0)
		{
			$this->assignment->deleteassignment($id);
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
}
