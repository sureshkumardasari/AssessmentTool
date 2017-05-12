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
        //dd($questionPoints);
        foreach ($questionPoints as $questionPoint) {
            
            $userAnswers = QuestionUserAnswer::where('question_id', '=', $questionPoint['question_id'])
                                    ->where('user_id', '=', $userId)
                                    ->where('assignment_id', '=', $assignmentId)
                                    ->get();
            $answers=[];
            $arr=[1=>'A',2=>'B',3=>'C',4=>'D',5=>'E'];                      
            $obj = DB::table('question_answers')->where("question_id", $questionPoint['question_id']);    
            $answers = $obj->where("is_correct", 'YES')->select('question_id','order_id')->get();
            $correct_answers = [];
            foreach ($answers as $key => $answer) {
                $correct_answers[$answer->question_id][] = $answer->order_id;
            }
            //dd($correct_answers);
            foreach($correct_answers as $key => $correct_answer ){
                    if($key == $questionPoint['question_id'])
                    {
                    foreach($correct_answer as $key1 => $value ){
                        
                            $right_ans[] = $arr[$value];
                    }

                    
                   }
                }

                //dd($userAnswers);
            // Create an entry for the answer if the answer wasn't available
            if ( count($userAnswers) === 0 ) {

                $uAnswer = new QuestionUserAnswer();

                $uAnswer->question_id = $questionPoint['question_id'];
                $uAnswer->user_id = $userId;
                $uAnswer->assignment_id = $assignmentId;
                $uAnswer->question_answer_id = 0;
                $uAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
                $uAnswer->is_correct = isset( $questionPoint['is_correct'] ) ? $questionPoint['is_correct'] : 'Open';

                $uAnswer->save();
            } else {
                // Iterate the answers and keep updating the points for each answer
                //dd($userAnswers);
                foreach ($userAnswers as $userAnswer) {
                if($userAnswer->answer_option == "")
                {
                    $uAnswer = new QuestionUserAnswer();

                $uAnswer->question_id = $questionPoint['question_id'];
                $uAnswer->user_id = $userId;
                $uAnswer->assignment_id = $assignmentId;
                $uAnswer->question_answer_id = 0;
                $uAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
                $uAnswer->is_correct = isset( $questionPoint['is_correct'] ) ? $questionPoint['is_correct'] : 'Open';

                $uAnswer->save();
                }
                elseif($userAnswer->answer_option != "")
                {

                
                   $userAnswer->points = ( trim($questionPoint['points']) === '-'  ? 0 : $questionPoint['points'] );
                     //dd($userAnswer->answer_option);
                    //dd($userAnswer->points);
                    //for other types
                    if(($questionPoint['type'] == 'singleanswer') || ($questionPoint['type'] == 'fib') ||($questionPoint['type'] == 'essay'))
                    {    
                        $userAnswer->is_correct = $questionPoint['is_correct'];
                    }           
                    else
                    {
                        //for multi answer
                        
                        
                            //dd('hai');
                            if(in_array($userAnswer->answer_option, $right_ans))
                            {
                               $userAnswer->is_correct = 'Yes';
                            }
                            else
                            {
                                $userAnswer->is_correct = 'No';
                            }  
                        
                       
                    }
                    
                    $userAnswer->save();
                }
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

            $userAnswers = QuestionUserAnswer::whereIn('assignment_id', $assignment_id)->where('user_id', '=', $userId)->get();
        }else {
            $userAnswers = QuestionUserAnswer::where('assignment_id', '=', $assignment_id)->where('user_id', '=', $userId)->get();
        }

        // Prepare the dataset for the answers
        $answers = [];
        foreach ($userAnswers as $usAns) {
            $answers[ $usAns->question_id ][] = $usAns;
        }

        return $answers;
    }
}
