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
//use App\Modules\Assessment\QuestionUserAnswer;

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

	public function saveAnswerByQuestionGrade($answer_id=0,$question_id=0){
		$post=Input::all();
		//dd($post);

		if($post['user_id']!=0) {
			$users_already_answered=QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
				->where('question_id',$question_id)->where('user_id',$post['user_id'])->get();
			//dd($users_already_answered);
			if (count($users_already_answered)==0) {

			//if ( count($userAnswers) === 0 ) {
			$uAnswer = new QuestionUserAnswer();

			$uAnswer->question_id = $question_id;
			$uAnswer->user_id = $post['user_id'];
			$uAnswer->assessment_id = $post['assessment_id'];
			$uAnswer->assignment_id = $post['assignment_id'];
			$uAnswer->question_answer_id = $answer_id;
				$uAnswer->question_answer_text = $post['selected_answer_text'];
			//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
			$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';

			$uAnswer->save();
//			} else {
//				// Iterate the answers and keep updating the points for each answer
//				foreach ($userAnswers as $userAnswer) {
//					$userAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
//					$userAnswer->is_correct = $questionPoint['is_correct'];
//					$userAnswer->save();
//				}
		}
			else{
				QuestionUserAnswer::where('user_id',$post['user_id'])->where('question_id',$question_id)
					->where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
					->update(['question_answer_id'=>$answer_id,'is_correct'=>isset($post['is_correct']) ? $post['is_correct'] : 'Open','question_answer_text'=>$post['selected_answer_text']]);
			}
			if(!isset($post['nextuserid'])){
				return "All students graded";
			}
		}
		else {
			$users_already_answered=QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
				->where('question_id',$question_id)->lists('user_id');
dd($users_already_answered);
			$assignment_users = AssignmentUser::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])->lists('user_id');
			//dd($assignment_users);
			foreach($assignment_users as $user){
				if(in_array($user,$users_already_answered)){
					QuestionUserAnswer::where('user_id',$post['user_id'])->where('question_id',$question_id)
						->where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
						->update(['question_answer_id'=>$answer_id,'is_correct'=>isset($post['is_correct']) ? $post['is_correct'] : 'Open','question_answer_text'=>$post['selected_answer_text']]);
				}
				else {
					$uAnswer = new QuestionUserAnswer();
					$uAnswer->question_id = $question_id;
					$uAnswer->user_id = $post['user_id'];
					$uAnswer->assessment_id = $post['assessment_id'];
					$uAnswer->assignment_id = $post['assignment_id'];
					$uAnswer->question_answer_id = $answer_id;
					$uAnswer->question_answer_text = $post['selected_answer_text'];
					//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
					$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';
					$uAnswer->save();
				}
			}
			return "All students graded";
		}
//		if(isset($post['nextuserid'])){
//			if($post['nextuserid']!="undefined"){
//			$next_user_answers = QuestionUserAnswer::select('question_answer_id')->where('user_id',$post['nextuserid'])->get();
//				dd($next_user_answers);
//			return $next_user_answers;
//			}
//		}

		return $post['user_id'];
	}

	public function nextStudentAnswersForQuestionGrade($user_id=0,$question_id=0){


		$next_user_answers = QuestionUserAnswer::where('user_id',$user_id)->where('question_id',$question_id)->lists('question_answer_id');
		//dd($next_user_answers);
		return $next_user_answers;
	}


}
