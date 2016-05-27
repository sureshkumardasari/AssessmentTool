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
		$assignments = $this->assignment->getassignment();
        return view('resources::assignment.list',compact('assignments'));
	}

	public function assignmentadd()
	{		
		
		//dd("123");
		//$inst_arr = $this->institution->getInstitutions();

		$id = 0;
		$assignment = new assignment();

		$assessments_arr = array(1=>"Assessment-1",2=>"Assessment-2");


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
		
		$assessments_arr = array(1=>"Assessment-1",2=>"Assessment-2");
		
		
		$institution_arr = $this->institution->getInstitutions();			

		$proctor_arr  = $this->user->getUsersOptionList($assignment->institution_id,3);// for proctor displaying teachers
		
		return view('resources::assignment.edit',compact('assignment','assessments_arr','proctor_arr','institution_arr','assignmentUsersJson'));
	}

	public function assignmentupdate($id = 0)
	{
		$params = Input::All();

		//var_dump($params); die();
		
		$this->assignment->updateassignment($params);

		return redirect('/resources/assignment');
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
}
