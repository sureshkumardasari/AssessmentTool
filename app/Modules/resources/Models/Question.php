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
}
