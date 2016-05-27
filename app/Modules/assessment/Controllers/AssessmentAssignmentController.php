<?php namespace App\Modules\Assessment\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Html\HtmlFacade;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use View;
use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\Assessment;

class AssessmentAssignmentController extends BaseController {

	

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

    public function myassignment($user_id = 0)
    {
        $myassignment = $this->assignment->getTests();
        //var_dump($myassignment);
        return view('assessment::myassignment', compact('myassignment'));
    }
    
    public function getTestInstructions($id) {

        $from = Input::get('from', '');

    	$ids = explode('-', $id);

    	$aId 	= $ids[0];		// assessment id
    	$aAId 	= $ids[1];	// assessment assignment id

    	$assessment = Assessment::find($aId);
        $assignment = $this->assignment->find($aAId);
    	
        $launchType = $assignment->launchtype;
        // get test type
        $assessmentType = '';//$this->getTestType($aId);

        $flag = \Input::get('flag', '');  
        $instructions['header'] = $assessment->header;
        $instructions['title_page'] = $assessment->titlepage;
        $instructions['begin_instructions'] = $assessment->begin_instruction;
        $instructions['footer'] = $assessment->footer;      

    	return view('assessment::test_instructions', compact('instructions', 'id', 'flag', 'assessmentType','launchType', 'from'));
    }
    public function testDetail($id) {
        
    	$ids = explode('-', $id);
        $aId    = $ids[0];      // assessment id
        $aAId   = $ids[1];  // assessment assignment id

        
        $templateHtml ='';
        /*$_templateHtml = Template::find($subsection->TemplateId);
        if ($_templateHtml) {
            $templateHtml = $_templateHtml->Template;            
        }*/
        
        $secs = 180;
        $assessmentType = '';

    	$path = public_path('data/assessment_pdf_images/assessment_'.$aId);

    	$filesCount = 0;//$this->getFilesCount($id);

    	$bulletType = '';
    	// get question based on subsection id
    	//$qbank = new SubsectionQuestion();
    	$questions = []; //$qbank->getQuestions($aAId, $sSId);
        //getting Assessment
        $assessment = Assessment::find($aId);

        $retaking = false;
        View::share('retaking', $retaking);

    	$ansPanel = view('assessment::partial._answer_panel', compact('questions', 'bulletType'));
    	return view('assessment::test_detail', compact( 'secs', 'id', 'filesCount', 'path', 'ansPanel', 'aId', 'assessmentType','templateHtml', 'assessment'));
    }

    private function getFilesCount($id) {
    	$ids = explode('-', $id);

    	$aId 	= $ids[0];		// assessment id
    	$aAId 	= $ids[1];	// assessment assignment id
    	$sSId 	= $ids[2];	// subsection id


        $s3 = new \App\Models\S3;
        $files = $s3->getFiles('assessment_'.$aId.'/subsection_'.$sSId, 'assessment_pdf_images');

        if( empty($files) ) {
            return 0;
        };

    	return count($files);
    }
}
