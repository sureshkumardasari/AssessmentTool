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

class QuestionAnswer extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'question_answers';
	protected $primaryKey = 'id';

	public function getQuestionAnswer($institution_id = 0)
	{
		//$users = User::get();
		$obj = new QuestionAnswer();
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

	
}
