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

	public function getQuestionsByAssessment($aId = 0)
	{
		$results = DB::table('assessment_question as aq')
						->join("assessment as a", 'a.id', '=', 'aq.assessment_id')
						->join("questions as q", 'aq.question_id', '=', 'q.id')
						->join("question_type as qt", 'q.question_type_id', '=', 'qt.id')
						->join("question_answers as qa", 'qa.question_id', '=', 'q.id')
						->where("aq.assessment_id","=", $aId)
						->select("q.id","q.title","qt.qst_type_text as question_type","qa.id as answer_id")
						->orderby('aq.id', 'ASC')
						->orderby('qa.order_id', 'ASC')
						->get();
		//dd($results);
		$questions = [];				
		foreach ($results as $key => $row) {
			$questions[$row->id]['Id'] = $row->id;
			$questions[$row->id]['Title'] = $row->title;
			$questions[$row->id]['question_type'] = $row->question_type;
			$questions[$row->id]['answers'][]['Id'] = $row->answer_id;
			$questions[$row->id]['user_answers'][]['QuestionAnswerId'] = 0;
			$questions[$row->id]['user_answers_retake'][]['QuestionAnswerId'] = 0;
		}
		//dd($questions);
		return $questions;						
	}

}
