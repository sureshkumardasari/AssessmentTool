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

	public function saveUserPoints( $questionPoints,$userId, $assignmentId ){
        // return $questionPoints ;             // Step 1: Save the question points
        foreach ($questionPoints as $questionPoint) {
            
            $userAnswers = QuestionUserAnswer::where('question_id', '=', $questionPoint['subsectionQuestionId'])
                                    ->where('user_id', '=', $userId)
                                    ->where('assignment_id', '=', $assignmentId)
                                    ->get();

            // Create an entry for the answer if the answer wasn't available
            if ( count($userAnswers) === 0 ) {

                $uAnswer = new QuestionUserAnswer();

                $uAnswer->question_id = $questionPoint['subsectionQuestionId'];
                $uAnswer->user_id = $userId;
                $uAnswer->assignment_id = $assignmentId;
                $uAnswer->question_answer_id = 0;
                $uAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
                $uAnswer->is_correct = isset( $questionPoint['isCorrect'] ) ? $questionPoint['isCorrect'] : 'Open';

                $uAnswer->save();
            } else {
                // Iterate the answers and keep updating the points for each answer
                foreach ($userAnswers as $userAnswer) {
                    $userAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
                    $userAnswer->is_correct = $questionPoint['isCorrect'];
                    $userAnswer->save();
                }
            }

        }
    }
}
