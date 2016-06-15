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
		$d=DB::table('assessment_question')->where('assessment_id','=',$id)->get();
		//dd($assessment);

		$c=array();
		foreach($d as $assessment)
			array_push($c,$assessment->question_id);
		//dd($c);
		$assessments = DB::table('assessment_question')
				//->join('assessment', 'assessment.id', '=', 'assessment_question.assessment_id')
				->join('questions', 'questions.id', '=', 'assessment_question.question_id')
				->leftjoin('passage','passage.id','=','assessment_question.passage_id')
				->where('assessment_question.assessment_id','=',$id)

				->select('questions.title as qstn_title','passage.title as psg_title')
				->get();
		//dd($assessments);
		return $assessments;

	}


	public function deleteAssessment($id = 0){
		$assessment = Assessment::find($id);
		$assessment->delete();
	}

}
