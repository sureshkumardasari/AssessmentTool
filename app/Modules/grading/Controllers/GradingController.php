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
		//dd($user_id);

		$questionss_list = $this->grade->loadAssignmentQuestion($assignment_id, $assessment_id, $user_id);

   		//dd($questionss_list);
        foreach($questionss_list as $list){
            $question_type=$list['question_type'];
            break;
        }

		$ass_usrs = $this->grade->getUsersById($assignment_id);
		//dd($ass_usrs);

		$user_list = Assignment::join('assignment_user', 'assignment.id', '=', 'assignment_user.assignment_id')
			->where('assignment_user.assignment_id', '=', $assignment_id)
			->select('user_id')
			->get();
		$user_id = [];
		//$first_user=0;
//		foreach($user_list as $selected_user){
//			$first_user=$selected_user['user_id'];
//			break;
//		}
		//dd($first_user);
		foreach ($user_list as $user) {
			$user_id[] = $user['user_id'];

			$user_list_detail = User::wherein('id', $user_id)
				->get();

//  	$question_list=Question::join('question_user_answer','questions.id','=','question_user_answer.question_id')
//							->join('question_answers','questions.id','=','question_answers.question_id')
//							->where('question_user_answer.assignment_id','=',$assignment_id)
//							->where('question_user_answer.user_id','=',$id)
////							->select('questions.title as title')
//							->groupBy('title')
//							->get();
//	dd($question_list);
//	$assignmentid = 1;
//	$userid =1;
//	dd($user_id);


//	$question_list=Question::join('question_user_answer','questions.id','=','question_user_answer.question_id')
//		->join('question_answers','questions.id','=','question_answers.question_id')
//		->where('question_user_answer.assignment_id','=',$assignment_id)
//		->where('question_user_answer.user_id','=',$id)
// 		->groupBy('title')
//		->get();
//
////	dd($question_list);


//			$actual_question = DB::table('question_user_answer')->where('assessment_id', '=', $assessment_id)->where('assignment_id',$assignment_id)->where('user_id', $id)->get();
//	//dd($actual_question);
//			$actual_question_list = array();
//			foreach ($actual_question as $userid) {
////		print_r($userid->assignment_id);
//				array_push($actual_question_list, $userid);
//				$q_list = [];
//				foreach ($actual_question_list as $list) {
////			dd($list->question_id);
//					array_push($q_list, $list->question_id);
//				}
//
//				$main_result = Question::join('question_answers', 'questions.id', '=', 'question_answers.question_id')
////		->where('question_user_answer.assignment_id','=',$assignment_id)
//						->wherein('questions.id', $q_list)
//						->get();
////	dd($main_result);
//
////				$sec_result = Question::join('question_user_answer', 'questions.id', '=', 'question_user_answer.question_id')
//////		->where('question_user_answer.assignment_id','=',$assignment_id)
////						->wherein('questions.id', $q_list)
////						->get();
////	dd($sec_result);
//
//				$result = [];
//
////				$question_list = Question::join('question_user_answer', 'questions.id', '=', 'question_user_answer.question_id')
////						->join('question_answers', 'questions.id', '=', 'question_answers.question_id')
////						->where('question_user_answer.assignment_id', '=', $assignment_id)
////						->where('question_user_answer.user_id', '=', $id)
////						->wherein('questions.id', $q_list)
////						->select('questions.title', 'questions.qst_text', 'question_user_answer.answer_option', 'question_answers.is_correct as main_correct', 'question_user_answer.is_correct as sec_correct', 'question_answers.ans_text')
////						->groupBy('title')
////						->get();
////	dd($question_list);
//
////	dd($question_list);
////	dd($q_list);
////	$usid= DB::table('assignment_user')->select('assignment_id')->wherein('user_id',$c)->get();
////	dd($usid);
//
////				$question = DB::table('question_answers')->select('question_id')->wherein('question_id', $q_list)->groupBy('question_id')->get();
////
////				$question_details = DB::table('questions')->wherein('id', $q_list)->groupBy('id')->get();
////	dd($question_details);
//
//
////	$question1 = DB::table('assessment_question')->select('question_id')->count();
////	$attended=DB::table('question_user_answer')->select('question_answer_id')->get();
////	$users = DB::table('question_user_answer')->select('is_correct')->get();
//////	echo 'total quection'."=".$question1.'<br>';
////	$count= 0;
////	$count1=0;
////	foreach($attended as $id)
////	{
////		$att=$id->question_answer_id;
////		if($att > 0)
////		{
////			$count++;
////		}
////		if($att == 0)
////		{
////			$count1++;
////		}
////	}
//////	echo 'attend the quection'.'='.$count.'<br>';
////	$a=0;
////	$b=0;
////	$c=0;
////	foreach($users as $user) {
////
////		$correct=$user->is_correct;
////		//dd($correct);
////		if($correct == "Yes") {
////
////			$a++;
////
////		}
////		elseif($correct == "No"){
////			$b++;
////		}
////		elseif($correct == "Open"){
////			$c++;
////		}
////
////	}
//////	echo 'write quection'.'='.$a.'<br>','wrong quection'.'='.$b.'<br>','not attended quection'.'='.$c;
////
//				//dd($actual_question);
//			}

		}
		$first_student_answers = $this->studentAnswers($assessment_id,$assignment_id,$id);
        //QuestionUserAnswer::where('assessment_id',$assessment_id)->where('assignment_id',$assignment_id)->where('user_id',$id)->select('question_answer_id','question_id')->get();;
		//dd($first_student_answers);
		//dd($questionss_list);
		return view('grading::student_inner_grade', compact('ass_usrs', 'question_list', 'user_list', 'user_list_detail', 'actual_question', 'main_result', 'questionss_list','assignmentUsersArr','assessment_id','assignment_id','id','first_student_answers','question_type'));

	}

	public function studentGradingInner($assignment_id){
	return 'studentGradingInner';
		// print_r($assignment_id);
		$ass_qst = $this->assignmentqst->getQuestionsByAssessment($assignment_id);
		// dd($ass_qst);
		return view('grading::student_question_list', compact('ass_qst'));
	}

	// Save ansers for students by Grade By Question method.....
	public function saveAnswerByQuestionGrade($answer_id=0,$question_id=0){
		$post=Input::all();
		//dd($post);

		if($post['user_id']!=0) {
			$users_already_answered=QuestionUserAnswer::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
				->where('question_id',$question_id)->where('user_id',$post['user_id'])->get();
			if (count($users_already_answered)==0) {
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
			$assignment_users = AssignmentUser::where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])->lists('user_id');
			foreach($assignment_users as $user){
				if(in_array($user,$users_already_answered)){
					QuestionUserAnswer::where('user_id',$user)->where('question_id',$question_id)
						->where('assessment_id',$post['assessment_id'])->where('assignment_id',$post['assignment_id'])
						->update(['question_answer_id'=>$answer_id,'is_correct'=>isset($post['is_correct']) ? $post['is_correct'] : 'Open','question_answer_text'=>$post['selected_answer_text']]);
				}
				else {
					$uAnswer = new QuestionUserAnswer();
					$uAnswer->question_id = $question_id;
					$uAnswer->user_id = $user;
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
		return $post['user_id'];
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
        $b=null;
        foreach($ans as $a){
            $b[$a['question_id']]=Array();
        }
        foreach($ans as $a){

            array_push($b[$a['question_id']],$a['question_answer_id']);
        }
return $b;

	}
}
