<?php

/**
 * QuestionUserAnswer Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Assessment\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class QuestionUserAnswer extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'question_user_answer';
}
