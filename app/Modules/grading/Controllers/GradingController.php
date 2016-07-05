<?php namespace App\Modules\Grading\Controllers;

use App\Modules\Resources\Models\Question;
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
use DB;

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

	public function studentGradeListing($assignment_id,$assessment_id){
		// print_r($assignment_id);
		//$assignment_id=$assignment_id;
		$ass_usrs = $this->grade->getUsersByAssignment($assignment_id);

		// dd($ass_usrs);
		return view('grading::student_grade', compact('ass_usrs','assignment_id','assessment_id'));
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

	public function studentQuestionList($id,$assignment_id,$assessment_id)
	{

		$assignmentUsersArr = 	$this->assignmentuser->getAssignUsersInfo($assignment_id);
		$user_id=$id;
		$institute=User::select('institution_id')->where('id',$id)->first();
		if(count($institute)>0){
			$institution_name=Institution::select('name')->where('id',$institute['institution_id'])->first();
		}
		else{
			$institution_name['name']="";
		}
		$details=AssignmentUser::where('user_id',$id)->where('assignment_id',$assignment_id)->select('takendate','gradeddate')->first();
		//dd($details);

		$questionss_list = $this->grade->loadAssignmentQuestion($assignment_id, $assessment_id, $user_id);
		foreach($questionss_list as $list){
			$question_type=$list['question_type'];
			break;
		}
		//$ass_usrs = $this->grade->getUsersById($assignment_id);
		$user_list = Assignment::join('assignment_user', 'assignment.id', '=', 'assignment_user.assignment_id')
				->where('assignment_user.assignment_id', '=', $assignment_id)
				->select('user_id')
				->get();
		$user_id = [];
		foreach ($user_list as $user) {
			$user_id[] = $user['user_id'];
			$user_list_detail = User::wherein('id', $user_id)
					->get();
		}
		$first_student_answers = $this->studentAnswers($assessment_id,$assignment_id,$id);
		return view('grading::student_inner_grade', compact( 'user_list_detail', 'questionss_list','assessment_id','assignment_id','id','first_student_answers','question_type','institution_name','details'));
	}

	public function studentGradingInner($assignment_id){
		return 'studentGradingInner';
		// print_r($assignment_id);
		$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		// dd($ass_qst);
		return view('grading::student_question_list', compact('ass_qst'));
	}

	// Save ansers for students by Grade By Question method.....
	public function saveAnswerByQuestionGrade($question_id=0){
		$post=Input::all();
		if(isset($post['selected_answer'])){
			//dd($post);
			if($post['question_type']=="Multiple Choice - Multi Answer"){

				if($post['user_id']!=0) {

					$users_already_answered=QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
							->where('question_id',$question_id)->where('user_id',$post['user_id'])->count();
					if ($users_already_answered!=0) {
						QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
								->where('question_id',$question_id)->where('user_id',$post['user_id'])->delete();
					}
//else{
					foreach($post['selected_answer'] as $answer){
						$uAnswer = new QuestionUserAnswer();
						$uAnswer->question_id = $question_id;
						$uAnswer->user_id = $post['user_id'];
						$uAnswer->assessment_id = $post['assessment_id'];
						$uAnswer->assignment_id = $post['assignment_id'];
						$uAnswer->question_answer_id = $answer; //var_dump((intval($answer)));
						//foreach ($post['selected_answer_text'] as $key => $text) {
						//dd($post['selected_answer_text']);
						$uAnswer->question_answer_text = $post['selected_answer_text'][intval($answer)];
						//}
						//foreach ($post['is_correct'] as $key => $value) {
						$uAnswer->is_correct = isset ($post['is_correct'][intval($answer)]) ? $post['is_correct'][intval($answer)]: 'Open';
						//}
						//$uAnswer->question_answer_text = $post['selected_answer_text'];
						//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
						//$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';

						$uAnswer->save();
					}
					//}
					// else{
					// 	QuestionUserAnswer::where('user_id',$post['user_id'])->where('question_id',$question_id)
					// 		->where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
					// 		->update(['question_answer_id'=>$post['selected_answer'],'is_correct'=>isset($post['is_correct']) ? $post['is_correct'] : 'Open','question_answer_text'=>$post['selected_answer_text']]);
					// }
					if(!isset($post['nextuserid'])){
						return "All students graded";
					}
					return $post['user_id'];
				}
				else {
					$users_already_answered=QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
							->where('question_id',$question_id)->lists('user_id');
					$assignment_users = AssignmentUser::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])->lists('user_id');
					foreach($assignment_users as $user){
						if(in_array($user,$users_already_answered)){
							QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])->where('question_id',$question_id)->where('user_id',$user)->delete();
						}
						foreach($post['selected_answer'] as $answer){
							// QuestionUserAnswer::where('user_id',$user)->where('question_id',$question_id)
							// 	->where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
							// 	->update(['question_answer_id'=>$post['selected_answer'],'is_correct'=>isset($post['is_correct']) ? $post['is_correct'] : 'Open','question_answer_text'=>$post['selected_answer_text']]);
							//}
							//else {
							$uAnswer = new QuestionUserAnswer();
							$uAnswer->question_id = $question_id;
							$uAnswer->user_id = $user;
							$uAnswer->assessment_id = $post['assessment_id'];
							$uAnswer->assignment_id = $post['assignment_id'];
							//$uAnswer->question_answer_id = $answer;

							$uAnswer->question_answer_id = $answer;
							//foreach ($post['selected_answer_text'] as $key => $text) {
							$uAnswer->question_answer_text = $post['selected_answer_text'][$answer];
							//}
							//foreach ($post['is_correct'] as $key => $value) {
							$uAnswer->is_correct = isset ($post['is_correct'][$answer]) ? $post['is_correct'][$answer]: 'Open';
							//}
							//$uAnswer->question_answer_text = $post['selected_answer_text'];
							//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
							//$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';
							$uAnswer->save();
						}
					}
					return "All students graded";
				}
			}
			else{


				if($post['user_id']!=0) {
					$users_already_answered=QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
							->where('question_id',$question_id)->where('user_id',$post['user_id'])->get();
					if (count($users_already_answered)==0) {
						$uAnswer = new QuestionUserAnswer();
						$uAnswer->question_id = $question_id;
						$uAnswer->user_id = $post['user_id'];
						$uAnswer->assessment_id = $post['assessment_id'];
						$uAnswer->assignment_id = $post['assignment_id'];
						$uAnswer->question_answer_id = $post['selected_answer'];
						$uAnswer->question_answer_text = $post['selected_answer_text'];
						//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
						$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';

						$uAnswer->save();
					}
					else{
						QuestionUserAnswer::where('user_id',$post['user_id'])->where('question_id',$question_id)
								->where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
								->update(['question_answer_id'=>$post['selected_answer'],'is_correct'=>isset($post['is_correct']) ? $post['is_correct'] : 'Open','question_answer_text'=>$post['selected_answer_text']]);
					}
					if(!isset($post['nextuserid'])){
						return "All students graded";
					}
				}
				else {
					$users_already_answered=QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
							->where('question_id',$question_id)->lists('user_id');
					$assignment_users = AssignmentUser::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])->lists('user_id');
					foreach($assignment_users as $user){
						if(in_array($user,$users_already_answered)){
							QuestionUserAnswer::where('user_id',$user)->where('question_id',$question_id)
									->where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
									->update(['question_answer_id'=>$post['selected_answer'],'is_correct'=>isset($post['is_correct']) ? $post['is_correct'] : 'Open','question_answer_text'=>$post['selected_answer_text']]);
						}
						else {
							$uAnswer = new QuestionUserAnswer();
							$uAnswer->question_id = $question_id;
							$uAnswer->user_id = $user;
							$uAnswer->assessment_id = $post['assessment_id'];
							$uAnswer->assignment_id = $post['assignment_id'];
							$uAnswer->question_answer_id = $post['selected_answer'];
							$uAnswer->question_answer_text = $post['selected_answer_text'];
							//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
							$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';
							$uAnswer->save();
						}
					}
					return "All students graded";
				}
				return $post['user_id'];
			}

		}
		else{
			return "No data given";
		}
	}


	public function nextStudentAnswersForQuestionGrade($user_id=0,$question_id=0){

		$next_user_answers = QuestionUserAnswer::where('user_id',$user_id)->where('question_id',$question_id)->lists('question_answer_id');
		return $next_user_answers;
	}

	//save answers for students by Grade By Student Method .....
	public function save_student_answers_by_gradeByStudentMethod($assessment_id=0,$assignment_id=0){
		$post=Input::all();

		//dd($post);
		$user_already_entered_answers=QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('user_id',$post['user_id'])->lists('question_id');
//dd($user_already_entered_answers);
		if(isset($post['question_selected_answers'])){
			if($post['question_type']=="Multiple Choice - Multi Answer"){

				foreach($post['question_selected_answers'] as $question_id=>$answers){
					// dd($answers);
					if (in_array((int)$question_id,$user_already_entered_answers)){
						QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('question_id',$question_id)->where('user_id',$post['user_id'])
								->delete();//['question_answer_id'=>$answer]);
					}
					if(count($answers)==0){
						continue;
					}
					foreach($answers as $answer){
						$uAnswer=new QuestionUserAnswer();
						$uAnswer->assessment_id = $assessment_id;
						$uAnswer->assignment_id = $assignment_id;
						$uAnswer->question_id = $question_id;
						$uAnswer->question_answer_id = $answer;
						$uAnswer->user_id = $post['user_id'];
						$uAnswer->save();
					}
				}
			}
			else if($post['question_type']=="Multiple Choice - Single Answer"){

				foreach($post['question_selected_answers'] as $question_id=>$answer) {
					$uAnswer=new QuestionUserAnswer();
					if (in_array($question_id,$user_already_entered_answers)){
						$uAnswer->where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('question_id',$question_id)->where('user_id',$post['user_id'])
								->update(['question_answer_id'=>$answer[0]]);
					}
					else{
						$uAnswer->assessment_id = $assessment_id;
						$uAnswer->assignment_id = $assignment_id;
						$uAnswer->question_id = $question_id;
						$uAnswer->question_answer_id = $answer[0];
						$uAnswer->user_id = $post['user_id'];
						$uAnswer->save();

					}
				}
			}

			if(isset($post['next_student'])){
				return $this->studentAnswers($assessment_id,$assignment_id,$post['next_student']);
				//return QuestionUserAnswer::where('user_id',$post['next_student'])->where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->select('question_answer_id','question_id')->get();
			}
			return "Completed";
		}
		else return "please answer atlest one question";
	}
	public function studentAnswers($assessment_id=0,$assignment_id=0,$user_id=0){

		$ans= QuestionUserAnswer::where('user_id',$user_id)->where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->select('question_answer_id','question_id')->get();
		// dd($ans)
		$b=Array();
		$b['student_answers']=Array();
		$b['student_details']=AssignmentUser::where('assignment_id',$assignment_id)->where('user_id',$user_id)->select('takendate','gradeddate')->first();
		foreach($ans as $a){
			$b['student_answers'][$a['question_id']]=Array();
		}
		foreach($ans as $a){

			array_push($b['student_answers'][$a['question_id']],$a['question_answer_id']);
		}
		return $b;

	}
}
