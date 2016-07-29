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

class Assessment extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'assessment';
	public function getassessmentFilterList($institution = 0, $category = 0, $subject = 0,$lessons=0)
	{
		$obj = DB::table('assessment'); ;
		if($institution > 0){
			$obj->where("institute_id", $institution);
		}
		if($category > 0){
			$obj->where("category_id", $category);
		}
		if($subject > 0){
			$obj->where("subject_id", $subject);
		}
		if($lessons > 0){
			$obj->where("lesson_id", $lessons);
		}
		$questions = $obj->get();
		return $questions;
	}
	public function getDetails($id = 0)
	{
		/*$d=DB::table('assessment_question')->where('assessment_id','=',$id)->get();
		//dd($assessment);

		$c=array();
		foreach($d as $assessment)
			array_push($c,$assessment->question_id);*/
		//dd($c);
		$assessments = DB::table('assessment_question')
				//->join('assessment', 'assessment.id', '=', 'assessment_question.assessment_id')
				->join('questions', 'questions.id', '=', 'assessment_question.question_id')
				->join('question_answers','question_answers.question_id','=','assessment_question.question_id')
				->leftjoin('passage','passage.id','=','assessment_question.passage_id')
				->where('assessment_question.assessment_id','=',$id)

				->select('questions.id as qstn_id','questions.title as qstn_title','question_answers.id as answer_id','question_answers.is_correct','question_answers.ans_text','questions.qst_text','passage.id as psg_id','passage.title as psg_title','passage.passage_text as psg_txt','passage.passage_lines as psg_lines')
				//->orderby('psg_id','qstn_id')
				->get();
		//dd($assessments);
		$questions = [];
		foreach ($assessments as $key => $question) {
			$questions[$question->psg_id]['psg_title'] = $question->psg_title;
			$questions[$question->psg_id]['psg_txt'] = $question->psg_txt;
			$questions[$question->psg_id]['questions'][$question->qstn_id]['qstn_id'] = $question->qstn_id;
			$questions[$question->psg_id]['questions'][$question->qstn_id]['title'] = $question->qstn_title;
			$questions[$question->psg_id]['questions'][$question->qstn_id]['qst_text'] = $question->qst_text;
			$questions[$question->psg_id]['questions'][$question->qstn_id]['answers'][] = ['Id' => $question->answer_id, 'ans_text' => $question->ans_text, 'is_correct' => $question->is_correct];
		}

		//dd($questions);
		return $questions;

	}
	public function getAssessment($institution_id = 0)
	{
		$assessment=Assessment::select('name','id')->where('institution_id',$institution_id)->get();
		return $assessment;
	}

	public function deleteAssessment($id = 0){
		$assessment = Assessment::find($id);
		$assessment->delete();
	}

}
