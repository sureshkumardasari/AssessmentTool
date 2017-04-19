<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Resources\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssessmentQuestion extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'assessment_question';

	public function getQuestionsByAssessment($aId = 0, $aAId = 0)
	{
		$que_user_answer = DB::table('question_user_answer as qua')
									->where('qua.assignment_id','=', $aAId)
									->where('qua.assessment_id','=', $aId)
									->where('qua.user_id','=', Auth::user()->id)
									->get();

		$user_answers = [];
		foreach ($que_user_answer as $key => $row) {
			$user_answers[$row->question_id][] = ['QuestionAnswerId'=> $row->question_answer_id, 'QuestionAnswerText'=>$row->question_answer_text];
		}

		$que_user_answer_retake = DB::table('question_user_answer_retake as qua')
									->where('qua.assignment_id','=', $aAId)
									->where('qua.assessment_id','=', $aId)
									->where('qua.user_id','=', Auth::user()->id)
									->get();
		$user_answers_retake = [];
		foreach ($que_user_answer_retake as $key => $row) {
			$user_answers_retake[$row->question_id][] = ['QuestionAnswerId'=> $row->question_answer_id, 'QuestionAnswerText'=>$row->question_answer_text];
		}


		$results = DB::table('assessment_question as aq')
						->join("assessment as a", 'a.id', '=', 'aq.assessment_id')
						->join("questions as q", 'aq.question_id', '=', 'q.id')
						->join("question_type as qt", 'q.question_type_id', '=', 'qt.id')
						->leftjoin("question_answers as qa", 'qa.question_id', '=', 'q.id')
						->where("aq.assessment_id","=", $aId)
						->select("q.id","q.title","qt.qst_type_text as question_type","qa.id as answer_id")
						//->orderby('aq.id', 'ASC')
						//->orderby('qa.order_id', 'ASC')
						//->orderby('qt.id', 'ASC')
						->get();
		//dd($results);
		$questions = [];				
		foreach ($results as $key => $row) {
			$questions[$row->id]['Id'] = $row->id;
			$questions[$row->id]['Title'] = $row->title;
			$questions[$row->id]['question_type'] = $row->question_type;
			$questions[$row->id]['answers'][]['Id'] = $row->answer_id;
			//$questions[$row->id]['user_answers'][]['QuestionAnswerId'] = 0;
			//$questions[$row->id]['user_answers_retake'][]['QuestionAnswerId'] = 0;
			$questions[$row->id]['user_answers'] = (isset($user_answers[$row->id])) ? $user_answers[$row->id] : [];
			$questions[$row->id]['user_answers_retake'] = (isset($user_answers_retake[$row->id])) ? $user_answers_retake[$row->id] : [];
		}
		//dd($questions);
		return $questions;						
	}

}
