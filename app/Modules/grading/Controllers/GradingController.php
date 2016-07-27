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
		//dd($ass_qst);
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
		$qst=[];
		$qst_select=[];
		//dd($questionss_list);
		foreach($questionss_list as $question){
			if(!isset($qst[$question['qtype_id']])){
				$qst[$question['qtype_id']]=[];
			}
			array_push($qst[$question['qtype_id']],$question);
			if(!isset($qst_select[$question['qtype_id']])){
				$qst_select[$question['qtype_id']]=$question['question_type'];
			}
		}
		//dd($qst_select);
		//dd($qst);
		foreach($questionss_list as $list){
			$question_type=$list['question_type'];
			break;
		}
		//$ass_usrs = $this->grade->getUsersById($assignment_id);
		$user_list = Assignment::join('assignment_user', 'assignment.id', '=', 'assignment_user.assignment_id')
				->where('assignment_user.assignment_id', '=', $assignment_id)
				->lists('user_id');
				//->get();
		$user_id = [];
		$user_list=User::whereIn('id',$user_list)->lists('name','id');
//		foreach ($user_list as $user) {
//			$user_id[] = $user['user_id'];
//			$user_list_detail = User::wherein('id', $user_id)
//					->get();
//		}
		$first_student_answers = $this->studentAnswers($assessment_id,$assignment_id,$id);
		return view('grading::student_inner_grade', compact( 'user_list','user_list_detail', 'questionss_list','qst','qst_select','assessment_id','assignment_id','id','first_student_answers','question_type','institution_name','details'));
	}

	public function studentGradingInner($assignment_id){
		return 'studentGradingInner';
		// print_r($assignment_id);
		$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		// dd($ass_qst);
		return view('grading::student_question_list', compact('ass_qst'));
	}

	// Save answers for students by Grade By Question method.....
	public function saveAnswerByQuestionGrade($question_id=0)
	{
		$post = Input::all();
		//dd($post);
		if ($post['question_type'] != "Essay") {
			if (isset($post['selected_answer'])) {
				$grade = new Grade();
				//dd($post);
				if ($post['question_type'] == "Multiple Choice - Multi Answer") {

					if ($post['user_id'] != 0) {

						$users_already_answered = QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
							->where('question_id', $question_id)->where('user_id', $post['user_id'])->count();
						if ($users_already_answered != 0) {
							QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
								->where('question_id', $question_id)->where('user_id', $post['user_id'])->delete();
						}
	//else{
						foreach ($post['selected_answer'] as $answer) {
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
							$uAnswer->is_correct = isset ($post['is_correct'][intval($answer)]) ? $post['is_correct'][intval($answer)] : 'Open';
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
						//for updating the scores of the students after grading for only single user..
						$params = [
							'assessment_id' => $post['assessment_id'],
							'assignment_id' => $post['assignment_id'],
							'user_id' => $post['user_id'],
							'retake' => false,
							'essay_grade' => 'no'
						];
						try {
							$grade->gradeSystemStudents($params);
						} catch (Exception $e) {

						}//....................
						if (!isset($post['nextuserid'])) {
							return "All students graded";
						}
						return $post['user_id'];
					} else {
						$users_already_answered = QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
							->where('question_id', $question_id)->lists('user_id');
						$assignment_users = AssignmentUser::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])->lists('user_id');
						foreach ($assignment_users as $user) {
							if (in_array($user, $users_already_answered)) {
								QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])->where('question_id', $question_id)->where('user_id', $user)->delete();
							}
							foreach ($post['selected_answer'] as $answer) {
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
								$uAnswer->is_correct = isset ($post['is_correct'][$answer]) ? $post['is_correct'][$answer] : 'Open';
								//}
								//$uAnswer->question_answer_text = $post['selected_answer_text'];
								//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
								//$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';
								$uAnswer->save();
							}
						}
						//for updating the scores of students after grading for all the students...
						foreach ($assignment_users as $user) {

							$params = [
								'assessment_id' => $post['assessment_id'],
								'assignment_id' => $post['assignment_id'],
								'user_id' => $user,
								'retake' => false,
								'essay_grade' => 'no'
							];
							try {
								$grade->gradeSystemStudents($params);
							} catch (Exception $e) {

							}
						}//-------------------
						return "All students graded";
					}
				} else if ($post['question_type'] == "Multiple Choice - Single Answer") {


					if ($post['user_id'] != 0) {
						$users_already_answered = QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
							->where('question_id', $question_id)->where('user_id', $post['user_id'])->get();
						if (count($users_already_answered) == 0) {
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
						} else {
							QuestionUserAnswer::where('user_id', $post['user_id'])->where('question_id', $question_id)
								->where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
								->update(['question_answer_id' => $post['selected_answer'], 'is_correct' => isset($post['is_correct']) ? $post['is_correct'] : 'Open', 'question_answer_text' => $post['selected_answer_text']]);
						}

						//for updating the scores of the students after grading for only single user..
						$params = [
							'assessment_id' => $post['assessment_id'],
							'assignment_id' => $post['assignment_id'],
							'user_id' => $post['user_id'],
							'retake' => false,
							'essay_grade' => 'no'
						];
						try {
							$grade->gradeSystemStudents($params);
						} catch (Exception $e) {

						}//....................

						if (!isset($post['nextuserid'])) {
							return "All students graded";
						}
					} else {
						$users_already_answered = QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
							->where('question_id', $question_id)->lists('user_id');
						$assignment_users = AssignmentUser::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])->lists('user_id');
						foreach ($assignment_users as $user) {
							if (in_array($user, $users_already_answered)) {
								QuestionUserAnswer::where('user_id', $user)->where('question_id', $question_id)
									->where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
									->update(['question_answer_id' => $post['selected_answer'], 'is_correct' => isset($post['is_correct']) ? $post['is_correct'] : 'Open', 'question_answer_text' => $post['selected_answer_text']]);
							} else {
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
						//for updating the scores of students after grading for all the students...
						foreach ($assignment_users as $user) {

							$params = [
								'assessment_id' => $post['assessment_id'],
								'assignment_id' => $post['assignment_id'],
								'user_id' => $user,
								'retake' => false,
								'essay_grade' => 'no'
							];
							try {
								$grade->gradeSystemStudents($params);
							} catch (Exception $e) {

							}
						}//-------------------

						return "All students graded";
					}
					return $post['user_id'];
				}
			}
			else{
				return "No data given";
			}
		}
		else {
			$grade=new Grade();
			if ($post['user_id'] != 0) {
				$users_already_answered = QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
					->where('question_id', $question_id)->where('user_id', $post['user_id'])->get();
				if (count($users_already_answered) == 0) {
					$uAnswer = new QuestionUserAnswer();
					$uAnswer->question_id = $question_id;
					$uAnswer->user_id = $post['user_id'];
					$uAnswer->assessment_id = $post['assessment_id'];
					$uAnswer->assignment_id = $post['assignment_id'];
					//$uAnswer->question_answer_id = $post['selected_answer'];
					$uAnswer->question_answer_text = $post['selected_answer_text'];
					$uAnswer->points = $post['selected_answer_score'];
					//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
					$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';

					$uAnswer->save();
				} else {
					QuestionUserAnswer::where('user_id', $post['user_id'])->where('question_id', $question_id)
						->where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
						->update(['is_correct' => isset($post['is_correct']) ? $post['is_correct'] : 'Open', 'question_answer_text' => $post['selected_answer_text'],'points'=> $post['selected_answer_score']]);
				}

				//for updating the scores of the students after grading for only single user..

				$params = [
					'assessment_id' => $post['assessment_id'],
					'assignment_id' => $post['assignment_id'],
					'user_id' => $post['user_id'],
					'retake' => false,
					'essay_grade' => 'no'
				];
				try {
					$grade->gradeSystemStudents($params);
				} catch (Exception $e) {

				}//....................

				if (!isset($post['nextuserid'])) {
					return "All students graded";
				}
			} else {
				$users_already_answered = QuestionUserAnswer::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
					->where('question_id', $question_id)->lists('user_id');
				$assignment_users = AssignmentUser::where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])->lists('user_id');
				foreach ($assignment_users as $user) {
					if (in_array($user, $users_already_answered)) {
						QuestionUserAnswer::where('user_id', $user)->where('question_id', $question_id)
							->where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
							->update(['question_answer_text' => $post['selected_answer_text'],'points' => $post['selected_answer_score']]);
					} else {
						$uAnswer = new QuestionUserAnswer();
						$uAnswer->question_id = $question_id;
						$uAnswer->user_id = $user;
						$uAnswer->assessment_id = $post['assessment_id'];
						$uAnswer->assignment_id = $post['assignment_id'];
						//$uAnswer->question_answer_id = $post['selected_answer'];
						$uAnswer->question_answer_text = $post['selected_answer_text'];
						$uAnswer->points = $post['selected_answer_score'];
						//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
						$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';
						$uAnswer->save();
					}
				}
				//for updating the scores of students after grading for all the students...
			foreach ($assignment_users as $user) {

				$params = [
					'assessment_id' => $post['assessment_id'],
					'assignment_id' => $post['assignment_id'],
					'user_id' => $user,
					'retake' => false,
					'essay_grade' => 'no'
				];
				try {
					$grade->gradeSystemStudents($params);
				} catch (Exception $e) {

				}
			}

//			QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])->where('user_id',$post['user_id'])
//			->where('question_id',$question_id)->update(['question_answer_text'=>$post['selected_answer_text']]);
//dd($post);
			}
		}
		return "completed";

	}


	public function nextStudentAnswersForQuestionGrade($user_id=0,$ids=0){
		//dd((int)$user_id);
		$ids=explode(',',$ids);
		$question_type=$ids[0];
		$assessment_id=(int)$ids[1];
		$assignment_id=(int)$ids[2];
		$question_id=(int)$ids[3];
		if($question_type!="Essay"){
			//dd("not");
			$next_user_answers = QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)
				->where('user_id',(int)$user_id)->where('question_id',$question_id)->lists('question_answer_id');
		}
		else{
			//dd("jyguh");
				$next_user_answers = QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)
					->where('user_id',(int)$user_id)->where('question_id',$question_id)->lists('question_answer_text','points');
		}
		return $next_user_answers;
	}

	//save answers for students by Grade By Student Method .....
	public function save_student_answers_by_gradeByStudentMethod($assessment_id=0,$assignment_id=0){
		$post=Input::all();
		//dd($assessment_id);
		//dd($assignment_id);
		//dd($post);
		$user_already_entered_answers=QuestionUserAnswer::where('assessment_id',(int)$assessment_id)->where('assignment_id',(int)$assignment_id)->where('user_id',(int)$post['user_id'])->lists('question_id');
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
						if(in_array($answer,$post['user_selected_correct_answers'][$question_id])){
							$uAnswer->is_correct = "Yes";
						}
						else{
							$uAnswer->is_correct = "No";
						}
						$uAnswer->save();
					}
				}
			}
			else if($post['question_type']=="Multiple Choice - Single Answer"){
				foreach($post['question_selected_answers'] as $question_id=>$answer) {
					if(in_array($answer,$post['user_selected_correct_answers'][$question_id])){
						$is_correct = "Yes";
					}
					else{
						$is_correct = "No";
					}
					$uAnswer=new QuestionUserAnswer();
					if (in_array((int)$question_id,$user_already_entered_answers)){
						$uAnswer->where('assessment_id',(int)$assessment_id)->where('assignment_id',(int)$assignment_id)->where('question_id',(int)$question_id)->where('user_id',(int)$post['user_id'])
								->update(['question_answer_id'=>(int)$answer,'is_correct'=>$is_correct]);
					}
					else{
						$uAnswer->assessment_id = $assessment_id;
						$uAnswer->assignment_id = $assignment_id;
						$uAnswer->question_id = $question_id;
						$uAnswer->question_answer_id = (int)$answer;
						$uAnswer->user_id = $post['user_id'];
						$uAnswer->is_correct =$is_correct;
						$uAnswer->save();

					}
				}
			}
			// for updating the scores of the students after grading.....
			$grade  = new Grade();

			$params = [
				'assessment_id' => $assessment_id,
				'assignment_id' => $assignment_id,
				'user_id'       => $post['user_id'],
				'retake'       => false,
				'essay_grade' =>'no'
			];
			try {
				//$grade->gradeSystemStudents( $params );
			} catch (Exception $e) {

			}
			if(isset($post['next_student'])){
				return $this->studentAnswers($assessment_id,$assignment_id,$post['next_student']);
				//return QuestionUserAnswer::where('user_id',$post['next_student'])->where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->select('question_answer_id','question_id')->get();
			}
			return "Completed";
		}
		else return "please answer at least one question";
	}
	// for sending students already answered options
	public function studentAnswers($assessment_id=0,$assignment_id=0,$user_id=0){
		$question_type=DB::table('question_type')->lists('qst_type_text','id');
		$question=[];
		foreach($question_type as $id=>$type){
			$q=QuestionUserAnswer::join('questions','question_user_answer.question_id','=',DB::raw('questions.id && questions.question_type_id ='.$id.' && question_user_answer.user_id ='.(int)$user_id.' && question_user_answer.assignment_id = '.(int)$assignment_id.' && question_user_answer.assessment_id='.$assessment_id));
				if($type=="Essay"){
					$q->select('question_answer_text','question_id','points');
				}
			else{
				$q->select('question_answer_id','question_id');
			}

				$question[$type]=$q->get();
		}
		//dd($question);

//		$ans= QuestionUserAnswer::join('questions','questions.id','=',DB::raw('question_user_answer.question_id && question_user_answer.user_id ='.(int)$user_id.' question_user_answer.assignment_id = '.(int)$assignment_id))
//			->join('question_type','question_type.id','=','questions.question_type_id')
//			//->where('user_id',(int)$user_id)->where('assessment_id',(int)$assessment_id)
//			//->where('assignment_id',(int)$assignment_id)
//			->select('question_answer_id','question_id')
//			->orderby('question_type.id')
//			->get();
		// dd($ans)
		$b=Array();
		$b['student_answers']=Array();
		$keys=array_keys($question);
		foreach($keys as $key){
			$b['student_answers'][$key]=[];
		}
		$b['student_details']=AssignmentUser::where('assignment_id',(int)$assignment_id)->where('user_id',(int)$user_id)->select('takendate','gradeddate')->first();
//		foreach($ans as $a){
//			$b['student_answers'][$a['question_id']]=Array();
//		}
		foreach($question as $key=>$quest){
			foreach($quest as $a){
				//if($key!="Essay"){
					if(!isset($b['student_answers'][$key][$a['question_id']])){
						$b['student_answers'][$key][$a['question_id']]=[];
					}
				//}

				if($key=="Essay"){
					//$b['student_answers'][$key][$a['question_id']]=$a['question_answer_text'];
					$b['student_answers'][$key][$a['question_id']]['text']=$a['question_answer_text'];
					$b['student_answers'][$key][$a['question_id']]['score']=$a['points'];
				}
				else{
					array_push($b['student_answers'][$key][$a['question_id']],$a['question_answer_id']);
				}

			}

		}
		//dd($b);
		return $b;

	}

	public function submit_essay_score($assessment_id,$assignment_id,$user_id=0){
		$post=Input::all();
		//dd($assessment_id);
		//dd($assignment_id);
		//dd($user_id);
		//dd($post);
		$questions_list=QuestionUserAnswer::where('assessment_id',(int)$assessment_id)->where('assignment_id',(int)$assignment_id)->where('user_id',(int)$user_id)->lists('question_id');
		//dd($questions_list);
		$qua=new QuestionUserAnswer();
		foreach($post['essay_answers'] as $key=>$essay_answer){
			//dd($post['essay_answers']);
			if(in_array($key,$questions_list)){
				$answer=$qua->where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('user_id',$user_id)->where('question_id',$key)
					->update(['question_answer_text'=>$essay_answer]);
			}
			else{
				$qua->assessment_id=$assessment_id;
				$qua->assignment_id=$assignment_id;
				$qua->user_id=$user_id;
				$qua->question_id=$key;
				$qua->question_answer_text=$essay_answer;
				$qua->points=$post['essay_answer_scores'][$key];
				$qua->save();
			}

		}

		//dd($post);
		//dd($assessment_id);
		return "over";

	}

	public function manualGrade($assessment_id,$assignment_id,$user_id){
		$grade= new Grade();
		$params = [
			'assessment_id' => $assessment_id,
			'assignment_id' => $assignment_id,
			'user_id'       => $user_id,
			'retake'       => false,
			'essay_grade' =>'no'
		];
		try {
			$grade->gradeSystemStudents( $params );
		} catch (Exception $e) {

		}
		return "completed";

	}
}
