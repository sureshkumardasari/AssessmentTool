<?php namespace App\Modules\Grading\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Html\HtmlFacade;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use View;
use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Assessment\Models\QuestionUserAnswer;
use App\Modules\Assessment\Models\QuestionUserAnswerRetake;
use App\Modules\Grading\Models\Grade;

class GradingController extends BaseController {

	

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

		$obj = new Grade();
		$this->grade = $obj;

		$obj = new AssessmentQuestion();
		$this->assignmentqst = $obj;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}    

	public function assignment(){
		$inst_arr = $this->institution->getInstitutions();	
		$assignments = $this->grade->getGradeAssignment();
		// dd($assignments);
        return view('grading::list',compact('assignments', 'inst_arr'));
	}

	public function studentGradeListing($assignment_id){
		// print_r($assignment_id);
		$ass_usrs = $this->grade->getUsersByAssignment($assignment_id);
		// dd($ass_usrs);
		return view('grading::student_grade', compact('ass_usrs'));
	}

	public function studentGradeListingAjax($student_id){

 		$ass_usrs = $this->grade->getUsersById($student_id);
		return $ass_usrs;
	}


	public function questionGradeListing($iid){
		$ids = explode("-", $iid);
		$assignment_id = $ids[0];
		$assessment_id = $ids[1];

		// print_r($assignment_id);
		$ass_qst = $this->grade->loadAssignmentQuestion($assignment_id, $assessment_id);
		//$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		//dd($ass_qst);
		return view('grading::question_grade_list', compact('ass_qst', 'assignment_id', 'assessment_id'));
	}

	public function questionGrade($iid){
		$ids = explode("-", $iid);
		$assignment_id = $ids[0];
		$assessment_id = $ids[1];
		$qst_id = $ids[2];

		//get assignment users
		$assignmentUsersArr = 	$this->assignmentuser->getAssignUsersInfo($assignment_id);	
		
		//get assessment questions
		$ass_qst = $this->grade->loadQuestion($assignment_id, $assessment_id, $qst_id);
		$qtitle = $ass_qst['Title'];
		$qtxt = $ass_qst['qst_text'];
		$ans = $ass_qst['answers'];
		//$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		// dd($ans);
		return view('grading::question_grade', compact('ass_qst', 'assignmentUsersArr', 'assignment_id', 'assessment_id', 'qst_id'));
	}

	public function studentQuestionList($assignment_id){
 		// print_r($assignment_id);
//		$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		// dd($ass_qst);
//		return view('grading::student_inner_grade', compact('ass_qst'));
		return view('grading::student_inner_grade');
	}

	public function studentGradingInner($assignment_id){
	return 'studentGradingInner';
		// print_r($assignment_id);
		$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		// dd($ass_qst);
		return view('grading::student_question_list', compact('ass_qst'));
	}


}
