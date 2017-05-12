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
use Illuminate\Http\JsonResponse;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use Session;

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

		$this->duplicate_entries=[];
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{

	}
	public function student($student_id=0,$assessment_id=0,$assignment_id=0)
    {
    	

    	$student_arr=DB::table('assignment_user')->where('user_id','=',$student_id)->where('assessment_id','=',$assessment_id)->where('assignment_id','=',$assignment_id)->select('takendate','gradeddate')->get();
    	
        return $student_arr;
    }

	public function assignment(){
		
		$inst_arr = $this->institution->getInstitutions();
		$assignments = $this->grade->getGradeAssignment();
		$grading_status= $this->grade->getAssignmentGradeStatus();
		//dd($grading_status);
		//$grading_status=AssignmentUser::select()->where('gradestatus','completed')->lists('assignment_id');
		// dd($assignments);
		return view('grading::list',compact('assignments', 'inst_arr','grading_status'));
	}

	public function studentGradeListing($assignment_id,$assessment_id){
		 //print_r($assignment_id=0);
		//$assignment_id=$assignment_id;
		if(Auth::user()->role_id == 3){
		$ass_usrs = $this->grade->getUsersByAssignment($assignment_id);

		// dd($ass_usrs);
		return view('grading::student_grade', compact('ass_usrs','assignment_id','assessment_id'));
	}
else
    {
   return view('permission');
    }
	}
	public function studentGradeListingAjax($student_id=0){
        if(Auth::user()->role_id == 3){
		$ass_usrs = $this->grade->getUsersById($student_id);
		//dd($ass_usrs);
		return $ass_usrs;

	}
else
    {
   return view('permission');
    }
	}

	public function questionGradeListing($assignment_id,$assessment_id){
	/*	$ids = explode("-", $iid);
		$assignment_id = $ids[0];
		$assessment_id = $ids[1];*/

		// print_r($assignment_id);
		if(Auth::user()->role_id == 3){
		$ass_qst = $this->grade->loadAssignmentQuestion($assignment_id, $assessment_id);
		//$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		//dd($ass_qst);
		return view('grading::question_grade_list', compact('ass_qst', 'assignment_id', 'assessment_id'));
	}
	else
    {
   return view('permission');
    }
	}

	public function questionGrade($iid){
		if(Auth::user()->role_id == 3){
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
else
    {
   return view('permission');
    }
	}
	public function studentQuestionList($id,$assignment_id,$assessment_id)
	{
		//dd($assessment_id);
         if(Auth::user()->role_id == 3){
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
		//dd($first_student_answers);
		$student_id=$id;
		$takendate=DB::table('assignment_user')->where('user_id','=',$id)->where('assessment_id','=',$assessment_id)->where('assignment_id','=',$assignment_id)->select('takendate')->get();
		$gradeddate=DB::table('assignment_user')->where('user_id','=',$id)->where('assessment_id','=',$assessment_id)->where('assignment_id','=',$assignment_id)->select('gradeddate')->get();
		//dd($takendate);

		
		return view('grading::student_inner_grade', compact( 'user_list','user_list_detail', 'questionss_list','qst','qst_select','assessment_id','assignment_id','student_id','first_student_answers','question_type','institution_name','details','takendate','gradeddate'));
	}
else
    {
   return view('permission');
    }
	}
	public function studentGradingInner($assignment_id){
       if(Auth::user()->role_id == 3){
		return 'studentGradingInner';

		// print_r($assignment_id);
		$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		// dd($ass_qst);
		return view('grading::student_question_list', compact('ass_qst'));
	}
else
    {
   return view('permission');
    }
	}
	// Save answers for students by Grade By Question method.....
	public function saveAnswerByQuestionGrade($question_id=0)
	{
		if(Auth::user()->role_id == 3){
		$post = Input::all();
		//dd($post);
		if (($post['question_type'] != "Essay") && ($post['question_type'] != "Fill in the blank")) {
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
					}
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
					//$uAnswer->question_answer_text = $post['selected_answer_text'];
					$uAnswer->points = $post['selected_answer_score'];
					//$uAnswer->points = ( trim($post['points']) === '-'  ? 0 : $post['points'] );
					$uAnswer->is_correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';

					$uAnswer->save();
				} else {
					$correct = isset($post['is_correct']) ? $post['is_correct'] : 'Open';
					$points=$post['selected_answer_score'];
					//dd($post['selected_answer_score']);
					QuestionUserAnswer::where('user_id', $post['user_id'])->where('question_id', $question_id)
						->where('assessment_id', $post['assessment_id'])->where('assignment_id', $post['assignment_id'])
						->update(['is_correct' => $correct, 'points'=>$points ]);
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
						//$uAnswer->question_answer_text = $post['selected_answer_text'];
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

		$ids=explode(',',$ids);
		//dd($ids);
		$question_type=$ids[0];
		$assessment_id=(int)$ids[1];
		$assignment_id=(int)$ids[2];
		$question_id=(int)$ids[3];
		//dd($question_type);
		if(!(($question_type=="Essay") || ($question_type == "Fill in the blank"))){
			//dd("not");
			$next_user_answers = QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)
				->where('user_id',(int)$user_id)->where('question_id',$question_id)->lists('question_answer_id');
		}
		else if($question_type == "Fill in the blank"){
			//dd("jyguh");
				$next_user_answers = QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)
					->where('user_id',(int)$user_id)->where('question_id',$question_id)->lists('question_answer_text','points');
		}
		else {
		//	dd("jyguhddd");
			$next_user_answers = QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)
					->where('user_id',(int)$user_id)->where('question_id',$question_id)->lists('question_answer_text','points');
		}
		//dd($next_user_answers);
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
					if(isset($post['user_selected_correct_answers'][$question_id])){
						$user_selected_correct_answers=$post['user_selected_correct_answers'][$question_id];
					}
					else{
						$user_selected_correct_answers=[];
					}
					foreach($answers as $answer){
						$uAnswer=new QuestionUserAnswer();
						$uAnswer->assessment_id = $assessment_id;
						$uAnswer->assignment_id = $assignment_id;
						$uAnswer->question_id = $question_id;
						$uAnswer->question_answer_id = $answer;
						$uAnswer->user_id = $post['user_id'];
						if(in_array($answer,$user_selected_correct_answers)){
							$uAnswer->is_correct = "Yes";
							$uAnswer->points = 1;
						}
						else{
							$uAnswer->is_correct = "No";
							$uAnswer->points = 0;
						}
						$uAnswer->save();
					}
				}
			}
			else if($post['question_type']=="Multiple Choice - Single Answer"){
				foreach($post['question_selected_answers'] as $question_id=>$answer) {
					if (in_array((int)$question_id,$user_already_entered_answers)){
						QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('question_id',$question_id)->where('user_id',$post['user_id'])
								->update(['question_answer_id'=>(int)$answer]);//['question_answer_id'=>$answer]);
					}
					if(isset($post['user_selected_correct_answers'][$question_id])){
						$user_selected_correct_answers=$post['user_selected_correct_answers'][$question_id];
					}
					else{
						$user_selected_correct_answers=[];
					}
					//dd($user_selected_correct_answers);
					if(in_array($answer,$user_selected_correct_answers)){
						$grade_s = "Do u want to grade student?";
						$is_correct = "Yes";
						$points = 1;
					}
					else{
						$is_correct = "No";
						$points = 0;
					}
					$uAnswer=new QuestionUserAnswer();
					if (in_array((int)$question_id,$user_already_entered_answers)){
						$uAnswer->where('assessment_id',(int)$assessment_id)->where('assignment_id',(int)$assignment_id)->where('question_id',(int)$question_id)->where('user_id',(int)$post['user_id'])
								->update(['question_answer_id'=>(int)$answer,'is_correct'=>$is_correct,'points'=>$points]);
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
				if($type=="Essay"|| $type=="Fill in the blank"){
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

				if($key=="Essay" || $key == "Fill in the blank"){
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
		$essay_answer = 0;
       // dd($post);
		//dd($assessment_id);
		//dd($assessment_id);
		//dd($assignment_id);
		//dd($user_id);
		//dd($post);
		$questions_list=QuestionUserAnswer::where('assessment_id',(int)$assessment_id)->where('assignment_id',(int)$assignment_id)->where('user_id',(int)$user_id)->lists('question_id');
		//dd($questions_list);
		$qua=new QuestionUserAnswer();
		foreach($post['essay_answer_scores'] as $key=>$essay_answer){
			//dd($essay_answer);
			if(in_array($key,$questions_list)){
				$answer=QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('user_id',$user_id)->where('question_id',$key)
					->update(['points'=>$essay_answer/*,'question_answer_text'=>$essay_answer*/]);

			}
			else{
				$qua->assessment_id=$assessment_id;
				$qua->assignment_id=$assignment_id;
				$qua->user_id=$user_id;
				$qua->question_id=$key;
				//$qua->question_answer_text=$essay_answer;
				$qua->points=$essay_answer;
				$qua->save();
			}

		}

		//dd($post);
		//dd($assessment_id);
		return "over";

	}

	public function submit_fib_score($assessment_id,$assignment_id,$user_id=0){
		$post=Input::all();
		$fib_answer = 0;
		//dd($post);
		$questions_list=QuestionUserAnswer::where('assessment_id',(int)$assessment_id)->where('assignment_id',(int)$assignment_id)->where('user_id',(int)$user_id)->lists('question_id');
		$qua=new QuestionUserAnswer();
		foreach($post['fib_answer_scores'] as $key=>$fib_answer){ 
			if(in_array($key,$questions_list)){
				$answer=QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('user_id',$user_id)->where('question_id',$key)
						->update(['points'=>$fib_answer/*,'question_answer_text'=>$essay_answer*/]);

            }
			else{
				$qua->assessment_id=$assessment_id;
				$qua->assignment_id=$assignment_id;
				$qua->user_id=$user_id;
				$qua->question_id=$key;
				//$qua->question_answer_text=$essay_answer;
				$qua->points=$fib_answer;
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


	public function gradesBulkImport(){
		$assignments_arr=[];
		$institution_arr=[];
		if(getRole()=="administrator"){
			$institution_arr=Institution::lists('name','id');
		}
		if(getRole()=="teacher"){
			$institution_arr=Institution::where('id',Auth::user()->institution_id)->lists('name','id');
			$assignments_arr=Assignment::where('institution_id',Auth::user()->institution_id)->lists('name','id');
		}

		else{
			$institution_id=Auth::user()->institution_id;
			$assignments_arr=Assignment::where('institution_id',$institution_arr)->lists('name','id');
		}
		
		//dd($assignments);

		return view('grading::bulk_import',compact('assignments_arr','institution_arr'));
	}

	public function bulkGradeTemplate(){
		$post=Input::all();
		//dd($post);
		$userType = $post['userType'];
		$institution_id = $post['institution_id'];

		$assignment_id = $post['assignment_id'];
		$assignment_text = $post['assignment_text'];
		$institution_text =  $post['institution_text'];
		if($institution_id == ""){
			$institution_text = Institution::find(Auth::user()->institution_id);
			$institution_text= $institution_text->name;
		}

		$filename = $institution_text ."---" .$assignment_text . '_' . date('Y-m-d') . '.xls';

		$save = $this->grade->bulkGradeTemplate($filename, $userType,$institution_id, $assignment_id, $assignment_text, false, true);
		if ($save == null) {
			return Response::json(array('file_name' => "data/tmp/$filename"));
		} else {
			return Response::json(array('file_name' => false));
		}

	}

	public function bulkGradeUpload(Request $request){
		//dd($request->input());
		$institutionId = $request->input('institutionId');
		$assignmentId = $request->input('assignmentId');
		//$userType = $request->input('assignmentId');

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
		return  $some=$this->fileupload($destPath,$destFileName, $institutionId, $assignmentId);
		// return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');




	}


	//uploading and parsing the Excel to insert the records into the database

	public function fileupload($destPath,$destFileName, $institutionId, $assignmentId){
		//dd($userType);
		$role_id = 0;
		// if($userType == 'student' || $userType == 'Student')
		// {
		// 	$role_id = $this->user->getRoleIdByRole($userType);
		// }
		//dd($role_id );
		$duplicate_entries=[];
		$uploadSuccess = false;
		$orignalHeaders = ['assignment','student','score','percentage','raw_score','grade','score_type','percentile'];
		$getFirstRow = Excel::load($destPath . '/' . $destFileName)->first()->toArray();
		//dd($getFirstRow);

		$uploadedFileHeaders = [];
		if(!empty($getFirstRow[0])){
			$uploadedFileHeaders = array_keys(array_only($getFirstRow[0], $orignalHeaders));
		}
	//dd($uploadedFileHeaders);
		$headerDifference = array_diff($orignalHeaders, $uploadedFileHeaders);
		//dd($headerDifference);

		if(!empty($headerDifference)){
			$error = array('status' => 'error', 'msg' => 'Invalid file.');
			return json_encode($error);
		}
		//        echo '<pre>'; print_r($getFirstRow); die;
		// if ($uploadSuccess != false) {
		$errorArray = array();
		//                    try{
		$this->duplicate_entries=[];
		$output = Excel::load($destPath . '/' . $destFileName, function($results) use ($assignmentId, $institutionId) {
			
			$phpExcel = $results->setActiveSheetIndex(1);
			$fileType = $phpExcel->getCell('D1')->getValue();
			//dd($fileType);
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
					$status = $this->validateBulUpload($fileType, $row, $key + 2);
					if (count($status) > 0) {
						$this->errorArray = array_merge($this->errorArray, $status);
					} else {
						$inserted=$this->createBulkGrade($assignmentId, $row, $institutionId, $key + 2);
						if($inserted == "duplicate"){
							//dd($duplicate_entries);
							array_push($this->duplicate_entries,$key+2);
						}
					}
				}

			} else {

				$this->errorArray[] = array('Row #' => '', 'Error Description' => 'File is empty');
			}
			if ($emptyFile) {
				$this->errorArray[] = array('Row #' => '', 'Error Description' => 'File is empty');
			}
			
		});
		//dd($output);
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
			$count=count($this->duplicate_entries);
			$duplicate_msg=null;
			if($count>0){
				$duplicate_msg="There are ".$count." duplicate entries found and are neglected";
			}

			Session::flash('success', 'File uploaded successfully.');
			return $sucessarray = array('status' => 'success','duplicate_msg' => $duplicate_msg, 'msg' => 'Uploaded Successfully');
			// return json_encode($sucessarray);
		}
		//}


	}

	public static function validateBulUpload($fileType, $data, $index) {
	    $error = array();

	    $dataArr = $data->toArray();
	    $validationRule = [
	        //'institutionid' => 'required|numeric|exists:institution,id',
	        'assignment' => 'required',
	        'student' =>'required',
	        'score' =>'required',
	        'percentage' =>'required|min:0|max:100',
	        'raw_score' =>'required',
	        'grade' => 'required',
	        'score_type' =>'required',
	        'percentile' =>'required|min:0|max:100',

	    ];	    

	    $messages = [
	        'assignment' => 'Assigment is Required \'',
	        'student' => 'Student is required \'',	        
	        'score' => 'Score field is required.',
	        'percentage.min' => 'Percentage should not be negative',
	        'percentage.max' => 'Percentage should not be greater than 100',
	        'raw_score' => 'Raw Score is Required',
	        'grade' => 'Grade field is required',
	        'score_type' => 'Score Type field is Required',
	        'percentile' => 'Percentile filed is required'
	    ];

	    $validator = Validator::make($dataArr, $validationRule, $messages);

	    if ($validator->fails()) {
	        $messages = $validator->messages();
	        foreach ($messages->all() as $row) {
	            $error[] = array('Row #' => $index, 'Error Description' => $row);
	        }
	    }

	    return $error;
	}
	public static function createBulkGrade($assignmentId, $row, $institutionId, $index)
	{
		//dd($row);
		//$duplicate_entries=[];
		$now=date('Y-m-d h:i:s');
		$assessment_id = Assignment::find($assignmentId);
		$user_id = User::select('id')->where('name',$row->student)->first();
		//dd($user_id);
		$already_graded_students=DB::table('user_assignment_result')->where('assignment_id',$assignmentId)->where('user_id',$user_id->id)->get();
		if(count($already_graded_students)==0){
			$obj = DB::table('user_assignment_result')->insert([
			'added_by' => Auth::user()->id,
			'assessment_id' => $assessment_id->assessment_id,
	 		'assignment_id' => $assignmentId,
			'user_id' =>  $user_id->id,
			'score' => $row->score,
			'percentage' => $row->percentage,
			'rawscore' => $row->raw_score,
			'grade' => $row->grade,
			'scoretype' => $row->score_type,
			'percentile' => $row->percentile,
			'created_at' => $now,
			'updated_at' => $now
			]);
			if($obj){
				AssignmentUser::where('assignment_id',$assignmentId)->where('user_id',$user_id->id)->update(['gradestatus'=>'completed']);
			}
		}
		else{
			
			return "duplicate";
		}
		
		return "inserted";
		
	}
}
