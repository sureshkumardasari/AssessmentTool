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

class QuestionUserAnswerRetake extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'question_user_answer_retake';

	public function saveUserPoints( $questionPoints,$userId, $assignmentId ){
        // return $questionPoints ;             // Step 1: Save the question points
        foreach ($questionPoints as $questionPoint) {
            
            $userAnswers = QuestionUserAnswerRetake::where('question_id', '=', $questionPoint['question_id'])
                                    ->where('user_id', '=', $userId)
                                    ->where('assignment_id', '=', $assignmentId)
                                    ->get();

            // Create an entry for the answer if the answer wasn't available
            if ( count($userAnswers) === 0 ) {

                $uAnswer = new QuestionUserAnswerRetake();

                $uAnswer->question_id = $questionPoint['question_id'];
                $uAnswer->user_id = $userId;
                $uAnswer->assignment_id = $assignmentId;
                $uAnswer->question_answer_id = 0;
                $uAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
                $uAnswer->is_correct = isset( $questionPoint['is_correct'] ) ? $questionPoint['is_correct'] : 'Open';

                $uAnswer->save();
            } else {
                // Iterate the answers and keep updating the points for each answer
                foreach ($userAnswers as $userAnswer) {
                    $userAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
                    $userAnswer->is_correct = $questionPoint['is_correct'];
                    $userAnswer->save();
                }
            }
        }
    }
    /**
     * Returns the array of answers which user has given against the passed assignment
     * @param  Integer $userId       Id of the user whose answers are required
     * @param  Integer $assignmentId Id of the assignment whose answers are required
     * @return Array                 An array of answer objects
     */
    public function getUserAssignmentAnswers($userId, $assignment_id)
    {
        if(is_array($assignment_id)){

            $userAnswers = QuestionUserAnswerRetake::whereIn('assignment_id', $assignment_id)->where('user_id', '=', $userId)->get();
        }else {
            $userAnswers = QuestionUserAnswerRetake::where('assignment_id', '=', $assignment_id)->where('user_id', '=', $userId)->get();
        }

        // Prepare the dataset for the answers
        $answers = [];
        foreach ($userAnswers as $usAns) {
            $answers[ $usAns->question_id ][] = $usAns;
        }

        return $answers;
    }
}
