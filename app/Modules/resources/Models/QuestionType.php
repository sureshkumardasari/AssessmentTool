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

class QuestionType extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'question_type';
	protected $primaryKey = 'id';

	public function getQuestionTypes($institution_id = 0)
	{
		//$users = User::get();
		$obj = new QuestionType();
		if($institution_id > 0)
		{
			$qtypes = $obj->where("institution_id", $institution_id)->lists('qst_type_text', 'id');
		}
		else
		{
			$qtypes = $obj->lists('qst_type_text', 'id');
		}
		
		return $qtypes;
	}

	public function getQuestionType($lesson_id = 0)
	{
		//$users = User::get();
		$obj = new QuestionType();
		if($lesson_id > 0)
		{
			$qtypes = $obj->where("lesson_id", $lesson_id)->lists('qst_type_text', 'id');
		}
		else
		{
			$qtypes = $obj->lists('qst_type_text', 'id');
		}
		
		return $qtypes;
	}

	
}
