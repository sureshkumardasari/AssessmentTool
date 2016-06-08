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
use App\Modules\Resources\Models\QuestionAnswer;

class Question extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'questions';
	protected $primaryKey = 'id';

	public function getQuestions($institution_id = 0, $subject_id = 0, $subject_id = 0)
	{
		//$users = User::get();
		$obj = new Question();
		if($institution_id > 0 || $subject_id > 0 || $subject_id > 0)
		{
			$questions = $obj->where("subject_id", $subject_id)->orWhere('institution_id', $institution_id)->orWhere('category_id', $category_id)->lists('title', 'id');
		}
		else
		{
			$questions = $obj->lists('title', 'id');
		}
		
		return $questions;
	}

	public function getassessmentQst($questions=0)
	{
 		$obj = DB::table('questions'); ;
		
		if($questions > 0){
 			$obj->wherein("id", $questions);
		}
 		$questions = $obj->get();
		return $questions;
	}public function getassessmentOldPassage($passage=0)
	{
 		$obj = DB::table('passage'); ;

		if($passage > 0){
 			$obj->wherein("id", $passage);
		}
		$passage = $obj->get();
		return $passage;
	}
	public function getassessmentRemoveOldPassage($passage=0)
	{
		$obj = DB::table('passage'); ;

		if($passage > 0){
			$obj->wherenotin("id", $passage);
		}
		$passage = $obj->get();
		return $passage;
	}
	public function getassessmentAppendQst($questions=0,$flag=0)
	{
		$obj = DB::table('questions'); ;

//		if($questions > 0){
//			$obj->wherenotin("id", $questions);
//		}
		if($flag==1){
 			$obj->wherein("id", $questions);
		}else{
 			$obj->wherenotin("id", $questions);
		}
		$questions = $obj->get();
		return $questions;
	}
	public function getPassageQst($passage_id=0,$flag=0,$question_Ids=0)
	{
 		$obj = DB::table('questions');
		if($question_Ids > 0){
			$obj->wherein("id", $question_Ids);
		}
		if($flag==1){
			$obj->wherenotin("passage_id", $passage_id);
		}else{
			$obj->wherein("passage_id", $passage_id);

		}
		$questions = $obj->get();
 		return $questions;
	}

	public function getassessmentFilter($institution = 0, $category = 0, $subject = 0,$lessons=0,$questions=0)
	{
 		$obj = DB::table('questions'); ;
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
		if($questions > 0){
 			$obj->wherenotin("id", $questions);
		}
 		$questions = $obj->get();
		return $questions;
	}
	public function getQuestionFilter($institution = 0, $category = 0, $subject = 0,$lessons=0)
	{
		$obj = DB::table('questions'); ;
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

	public function getQuestionTypes(){
		
	}

	public function getLessonInfo($id = 0)
	{
		$lesson = Lesson::find($id);
		return $lesson;
	}

	public function deleteLesson($id = 0)
	{
		$lesson = Lesson::find($id);
		$lesson->delete();
	}

	public function updateLesson($params = 0)
	{
		$obj = new Lesson();
		if($params['id'] > 0)
		{
			$obj = Lesson::find($params['id']);	
			$obj->updated_by = Auth::user()->id;			
		}
		else
		{
			$obj->added_by = Auth::user()->id;				
		}
		
		$obj->name = $params['name'];
		$obj->subject_id = $params['subject_id'];
		$obj->institution_id = $params['institution_id'];
		$obj->category_id = $params['category_id'];
		$obj->save();	
	}
	public function updateQuestion($params = 0)
	{
   		$obj = new Question();
		if($params['id'] > 0)
		{
			$obj = Question::find($params['id']);
			$obj->updated_by = Auth::user()->id;
		}
		else
		{
			$obj->added_by = Auth::user()->id;
		}
 		$obj->title = $params['question_title'];
		$obj->qst_text = $params['question_textarea'];
		$obj->question_type_id = $params['question_type'];
		$obj->subject_id = $params['subject_id'];
		$obj->category_id = $params['category_id'];
		$obj->lesson_id = $params['lessons_id'];
		$obj->passage_id = $params['passage'];
		$obj->institute_id = $params['institution_id'];
 		$obj->status =  $params['status'];
		$obj->difficulty_id ='';
 		if($obj->save()){
		$explanation = $params['explanation'];
		$is_correct = $params['is_correct'];
 		foreach ($params['answer_textarea'] as $key => $value) {

			$answer = new QuestionAnswer();
			if (isset($params['answerIds'][$key]) && !empty($params['answerIds'][$key])) {
				$answer = QuestionAnswer::find($params['answerIds'][$key]);
				if (empty($answer)) {
					$answer = new QuestionAnswer();
				}
			}
			$last_id=$obj->id;
			$answer->question_id = $last_id;
			$answer->ans_text = $value;
			$answer->explanation = $explanation[$key];
			$answer->order_id = ($key+1);
			$answer->is_correct = (($is_correct[$key] == "true") ? "YES" : "NO");
			$answer->save();

		}
	}

	}

	public function deleteQuestions($id = 0){
		$question = Question::find($id);
		$question->delete();
	}
}
