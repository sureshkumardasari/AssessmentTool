<?php

/**
 * QuestionUserAnswer Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Grading\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Assessment\Models\QuestionUserAnswer;
use App\Modules\Assessment\Models\QuestionUserAnswerRetake;
use App\Modules\Assessment\Models\UserAssignmentResult;
use App\Modules\Assessment\Models\UserAssignmentResultRetake;
use \PHPExcel,
    //\PHPExcel_Style_Fill,
    \PHPExcel_IOFactory,
    \PHPExcel_Style_NumberFormat;
    use App\Modules\Admin\Models\User;
class Grade extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'question_user_answer';

	public function gradeSystemStudents( $params ){
        //dd(";ikjhljh");

        $netSectionPercentage  = 0;
       // $obj                     = new Subsection();       
        $sQuAnws                 = (isset($params['retake']) && $params['retake'] == '1') ? new QuestionUserAnswerRetake() : new QuestionUserAnswer();
        $grade                   = new Grade();
        $assmntAssignment        = new Assignment();
        $assessmentAssignmentUser = new AssignmentUser();        
        
        $totalScaledScore    = 0;
        $totalRawScore       = 0;
        $totalPercentage     = 0;

        $assessment = Assessment::find(  $params['assessment_id']  );
        //get the point of each question that is assigned to the user 
           
        //dd($params);
        if( !isset($params['essay_grade'])) 
        {
            $AssignmentQstUsrAnws =  $this->calculateQuestionPoints( $params );
        
        $sQuAnws->saveUserPoints($AssignmentQstUsrAnws,$params['user_id'],$params['assignment_id']);
        

            if (multiKeyExists($AssignmentQstUsrAnws, 'essay')) {
                $assessmentAssignmentUser->updateUserGradeStatus(array(
                    'assignment_id' => $params['assignment_id'],
                    'user_id' => $params['user_id'],
                    'status' => "inprogress"
                ));

            } else {


                $gradeAllQuestion = $this->gradedQuestion($params['assessment_id'], $params['assignment_id'], $params['user_id'], (isset($params['retake']) ? $params['retake'] : ''));
                $netScore = $this->totolQuestionPoints($params['assessment_id'], $params['assignment_id']);
                $rawscore = $gradeAllQuestion['rawscore'];

                // To make it not throw divide by zero exception
                if ($netScore != 0) {
                    $percentage = ($rawscore / $netScore) * 100;
                } else {
                    $percentage = 0;
                }

                $netSectionPercentage += $percentage;

                // Default values
                $scaledscore = 0;
                $grade = null;
                $percentile = 0;
                $scoretype = 'scaledscore';


                // if type is formative
                // if assessment is formative
                // get the scalescore ,score type ,and grade
                // formative


                $scaledscore = 0;
                $grade = '';
                $scoretype = 0;
                $percentile = 0;

                $uSrArslt = (isset($params['retake']) && $params['retake'] == '1') ? new UserAssignmentResultRetake() : new UserAssignmentResult();
                $uSrArslt->insertuserassessmentassignmentrslt(
                    array(
                        'assessment_id' => $params['assessment_id'],
                        'assignment_id' => $params['assignment_id'],
                        'user_id' => $params['user_id'],
                        'scaledscore' => $scaledscore,
                        'scoretype' => $scoretype,
                        'grade' => $grade,
                        'rawscore' => $rawscore,
                        'percentage' => $percentage,
                        'percentile' => $percentile,
                    )
                );
                $update = $assessmentAssignmentUser->updateassignmentrecords(
                    array(
                        'assessment_id' => $params['assessment_id'],
                        'assignment_id' => $params['assignment_id'],
                        'user_id' => $params['user_id'],
                        'scaledscore' => $scaledscore,
                        'grade' => $grade,
                        'rawscore' => $rawscore,
                        'percentage' => $percentage,
                        'status' => 'completed'
                    )
                );

                if (isset($params['retake']) && $params['retake'] == '1') {
                    $this->_compareScale($params);
                }

                $status = $this->getGradeStatus($params['assignment_id']);
                $assmntAssignment->updateGradeStatus($params['assignment_id'], $status);

            }
        }
        else{
            $gradeAllQuestion = $this->gradedQuestion($params['assessment_id'], $params['assignment_id'], $params['user_id'], (isset($params['retake']) ? $params['retake'] : ''));
          // dd($gradeAllQuestion);
            $netScore = $this->totolQuestionPoints($params['assessment_id'], $params['assignment_id']);
            $rawscore = $gradeAllQuestion['rawscore'];

            // To make it not throw divide by zero exception
            if ($netScore != 0) {
                $percentage = ($rawscore / $netScore) * 100;
            } else {
                $percentage = 0;
            }

            $netSectionPercentage += $percentage;

            // Default values
            $scaledscore = 0;
            $grade = null;
            $percentile = 0;
            $scoretype = 'scaledscore';


            // if type is formative
            // if assessment is formative
            // get the scalescore ,score type ,and grade
            // formative


            $scaledscore = 0;
            $grade = '';
            $scoretype = 0;
            $percentile = 0;

            $uSrArslt = (isset($params['retake']) && $params['retake'] == '1') ? new UserAssignmentResultRetake() : new UserAssignmentResult();
            $uSrArslt->insertuserassessmentassignmentrslt(
                array(
                    'assessment_id' => $params['assessment_id'],
                    'assignment_id' => $params['assignment_id'],
                    'user_id' => $params['user_id'],
                    'scaledscore' => $scaledscore,
                    'scoretype' => $scoretype,
                    'grade' => $grade,
                    'rawscore' => $rawscore,
                    'percentage' => $percentage,
                    'percentile' => $percentile,
                )
            );
            $update = $assessmentAssignmentUser->updateassignmentrecords(
                array(
                    'assessment_id' => $params['assessment_id'],
                    'assignment_id' => $params['assignment_id'],
                    'user_id' => $params['user_id'],
                    'scaledscore' => $scaledscore,
                    'grade' => $grade,
                    'rawscore' => $rawscore,
                    'percentage' => $percentage,
                    'status' => 'completed'
                )
            );

            if (isset($params['retake']) && $params['retake'] == '1') {
                $this->_compareScale($params);
            }

            $status = $this->getGradeStatus($params['assignment_id']);
            $assmntAssignment->updateGradeStatus($params['assignment_id'], $status);
        }

    }
    // Compare old and new score if new score is greater than old score then replace user answers and final result 
    // otherwise leave as it is
    private function _compareScale($params) {

        $old = UserAssignmentResult::where('assignment_id', $params['assignment_id'])
                                            ->where('assessment_id', $params['assessment_id'])
                                            ->where('user_id', $params['user_id'])
                                            ->first();

        $new = UserAssignmentResultRetake::where('assignment_id', $params['assignment_id'])
                                                ->where('assessment_id', $params['assessment_id'])
                                                ->where('user_id', $params['user_id'])
                                                ->first();
        if (!empty($old) && !empty($new)) {
            $oldScore = floatval($old->rawscore);
            $newScore = floatval($new->rawscore);

            if ($newScore > $oldScore) {
                DB::unprepared('                        
                        delete from user_assignment_result where assessment_id = '. $params['assessment_id'] .' and assignment_id = '. $params['assignment_id'] .' and user_id = '. $params['user_id'] .';
                        
                        insert into user_assignment_result (assignment_id, assessment_id, user_id, score, created_at, updated_at, percentage, rawscore, grade, scoretype, percentile) select assignment_id, assessment_id, user_id,  score, created_at, updated_at, percentage, rawscore, grade, scoretype, percentile from user_assignment_result_retake where assessment_id = '. $params['assessment_id'] .' and assignment_id = '. $params['assignment_id'] .' and user_id = '. $params['user_id'] .';                        

                        delete from question_user_answer where assignment_id = '. $params['assignment_id'] .' and user_id = '. $params['user_id'] .';
                        insert into question_user_answer (question_id, question_answer_id, user_id, created_at, updated_at, question_answer_text, option, assignment_id, points, flag, is_correct, original_answer_value) select question_id, question_answer_id, user_id, created_at, updated_at, question_answer_text, option, assignment_id, points, flag, is_correct, original_answer_value from question_user_answer_retake where assignment_id = '. $params['assignment_id'] .' and user_id = '. $params['user_id'] .';
                    ');
            }

            DB::unprepared('                        
                        delete from user_assignment_result_retake where assessment_id = '. $params['assessment_id'] .' and assignment_id = '. $params['assignment_id'] .' and user_id = '. $params['user_id'] .';
                        delete from question_user_answer_retake where assignment_id = '. $params['assignment_id'] .' and user_id = '. $params['user_id'] .';
                    ');
        }
    }

    //get  
    public function loadAssignmentUsers(){
        
    }

    //get Assessment Questions
    public function loadQuestion($assignment_id = 0, $assessment_id = 0, $qid = 0){
            $results = DB::table('assessment_question as aq')
                        ->join("assessment as a", 'a.id', '=', 'aq.assessment_id')
                        ->join("questions as q", 'aq.question_id', '=', 'q.id')
                        ->join("question_type as qt", 'q.question_type_id', '=', 'qt.id')
                        ->leftjoin("question_answers as qa", 'qa.question_id', '=', 'q.id')
                        ->where("aq.assessment_id","=", $assessment_id)
                        ->where("q.id","=", $qid)
                        ->select("q.id","q.title","qt.qst_type_text as question_type","qa.id as answer_id","q.qst_text","qa.ans_text as answer_text", "qa.is_correct","a.guessing_panality","a.mcsingleanswerpoint","a.essayanswerpoint")
                        ->orderby('aq.id', 'ASC')
                        ->orderby('qa.order_id', 'ASC')
                        ->get();

            $questions = [];                
            foreach ($results as $key => $row) {
                $questions['Id'] = $row->id;
                $questions['Title'] = $row->title;
                $questions['question_type'] = $row->question_type;
                $questions['guessing_panality'] = $row->guessing_panality;
                $questions['mcsingleanswerpoint'] = $row->mcsingleanswerpoint;
                $questions['essayanswerpoint'] = $row->essayanswerpoint;
                $questions['qst_text'] = $row->qst_text;

                $questions['answers'][] = ['Id' => $row->answer_id, 'is_correct' => $row->is_correct, 'ans_text' => $row->answer_text];

                if($row->is_correct == 'YES')
                $questions[$row->id]['correctanswers'][] = $row->answer_id;
            }
            // dd($questions);
            return $questions;             
    }

    public function loadAssignmentQuestion($assignment_id = 0, $assessment_id = 0, $user_id = 0)
    {
        $results = DB::table('assessment_question as aq')
                        ->join("assessment as a", 'a.id', '=', 'aq.assessment_id')
                        ->join("questions as q", 'aq.question_id', '=', 'q.id')
                        ->join("question_type as qt", 'q.question_type_id', '=', 'qt.id')
                        ->leftjoin("question_answers as qa", 'qa.question_id', '=', 'q.id')
                       // ->leftjoin('question_user_answers as qua','q.id','=','qua.question_id')
                        ->where("aq.assessment_id","=", $assessment_id)
                        ->select("q.id","q.title","q.status","q.qst_text","qt.id as qtype_id","qt.qst_type_text as question_type","qa.id as answer_id","qa.ans_text as ans_text","qa.is_correct","a.guessing_panality","a.mcsingleanswerpoint","a.essayanswerpoint")
                        ->orderby('qt.id','ASC')
                        ->orderby('aq.id', 'ASC')
                        ->orderby('qa.order_id', 'ASC')
                        ->get();
        //dd($results);
        $questions = [];  
        $pre_qid = '';
        foreach ($results as $key => $row) {
            $questions[$row->id]['Id'] = $row->id;
            $questions[$row->id]['qtype_id'] = $row->qtype_id;
            $questions[$row->id]['Title'] = $row->title;
            $questions[$row->id]['Status'] = $row->status;
            $questions[$row->id]['qst_text'] = $row->qst_text;
            $questions[$row->id]['ans_text'] = $row->ans_text;
            $questions[$row->id]['question_type'] = $row->question_type;
            $questions[$row->id]['guessing_panality'] = $row->guessing_panality;
            $questions[$row->id]['mcsingleanswerpoint'] = $row->mcsingleanswerpoint;
            $questions[$row->id]['essayanswerpoint'] = $row->essayanswerpoint;
            $questions[$row->id]['answers'][] = ['Id' => $row->answer_id,'ans_text'=>$row->ans_text , 'is_correct' => $row->is_correct];


            
            if($row->question_type == 'Multiple Choice - Multi Answer')
            {
                if($pre_qid != $row->id ){
                    $obj = DB::table('question_answers');
                    $answers=[];
                        if($row->id > 0){
                            $obj->where("question_id", $row->id);
                        }
                        $answers = $obj->where("is_correct", 'YES')->select('question_id','order_id')->get();
                    $correct_answer = [];
                    foreach ($answers as $key => $answer) {
                        $correct_answer[$answer->question_id][] = $answer->order_id;
                    }
                    $questions[$row->id]['correctanswers'] = $correct_answer;
                }
            }
            else
            {
                if($row->is_correct == 'YES')
                $questions[$row->id]['correctanswers'][] = $row->answer_id;
            }
            
            $pre_qid = $row->id;
        }
        //dd($questions);
        return $questions;  
    }

    public function calculateQuestionPoints( $params ){
        //dd($params);
        $sQuAnws     = (isset($params['retake']) && $params['retake'] == '1') ? new QuestionUserAnswerRetake() : new QuestionUserAnswer();
        $userAnswers = $sQuAnws->getUserAssignmentAnswers( $params['user_id'], $params['assignment_id'] );
        $assignmentq =   $this->loadAssignmentQuestion( $params['assignment_id'], $params['assessment_id'] );
        //dd($assignmentq);
        $questionAnwerPoint = [];
        foreach ( $assignmentq as $key => $question) {
            //dd($question['correctanswers']);
            if(isset($question['question_type'])){
                if( ( $question['question_type'] == 'Multiple Choice - Single Answer') ||
                        ( $question['question_type'] == 'Selection')
                ) {
                    $userAnswerStatus = 'no-response';
                    $points = 0;
                    $userAnswerIds = [];

                    if( !empty( $userAnswers[ $question['Id'] ]) ) {

                        // Prepare the User Answer Ids in an array
                        foreach( $userAnswers[ $question['Id'] ] as $userAnswer ) {
                          $userAnswerIds[] = $userAnswer->question_answer_id;
                        }
                        //dd($question['correctanswers']);
                        $userAnswerStatus = 'correct';

                        // If any of the correct answers was not selected by the user
                        foreach( $question['correctanswers'] as $i => $correctAnswerId ) {
                            //dd($correctAnswerId[$i]);
                          if ( !in_array($correctAnswerId, $userAnswerIds) ) {
                            $userAnswerStatus = 'wrong';
                          }
                        }

                        // If the number of answers selected by user was greater than or 
                        // less than the correct answer count? Wrong Answer!
                        //dd( count($userAnswerIds ));
                        if ( count($question['correctanswers']) !== count($userAnswerIds) ) {
                          $userAnswerStatus = 'wrong';
                        }

                        if ( $userAnswerStatus == 'correct' ) 
                        {
                            $points = $question['mcsingleanswerpoint'];
                        } 
                        else if ( $userAnswerStatus == 'wrong' ) 
                        {
                            if (  $question['guessing_panality'] == 0.25) {
                                $points = '-' . $question['guessing_panality'];
                            }
                        }
                    }
                    $questionAnwerPoint[$key]['question_id'] = $question['Id'];
                    $questionAnwerPoint[$key]['points']      = $points;
                    $questionAnwerPoint[$key]['is_correct']  = swapValue($userAnswerStatus);
                    $questionAnwerPoint[$key]['type']  = "singleanswer";

                }

                elseif (( $question['question_type'] == 'Multiple Choice - Multi Answer')) {
                    
                    $uAnswers = QuestionUserAnswer::where('question_id', '=', $question['Id'])
                                    ->where('user_id', '=', $params['user_id'])
                                    ->where('assignment_id', '=', $params['assignment_id'])
                                    ->get();
                    $answers=[];
                    $arr=[1=>'A',2=>'B',3=>'C',4=>'D',5=>'E'];                      
                    $obj = DB::table('question_answers')->where("question_id", $question['Id']);    
                    $answers = $obj->where("is_correct", 'YES')->select('question_id','order_id')->get();
                    $correct_answers = [];
                    foreach ($answers as $key3 => $answer) {
                        $correct_answers[$answer->question_id][] = $answer->order_id;
                    }
                    //dd($correct_answer);
                    foreach($correct_answers as $key0 => $correct_answer ){
                            if($key0 == $question['Id'])
                            {
                            foreach($correct_answer as $key1 => $value ){
                                
                                    $right_ans[] = $arr[$value];
                            }

                            
                           }
                        }

                        $iscorrect = [];
                        foreach ($uAnswers as $uAnswer) {
                            
                               if(in_array($uAnswer->answer_option, $right_ans)){
                                   $is_correct[$uAnswer->answer_option] = 'Yes';
                                   $points = $question['mcsingleanswerpoint'];
                                }else
                                {
                                    $is_correct[$uAnswer->answer_option] = 'No';
                                    if (  $question['guessing_panality'] == 0.25) {
                                            $points = '-' . $question['guessing_panality'];
                                        }
                                }  
                        }


                        //dd($is_correct);



                    /*$userAnswerStatus = 'no-response';
                    $points = 0;
                    $userAnswerIds = [];

                    if( !empty( $userAnswers[ $question['Id'] ]) ) {

                        // Prepare the User Answer Ids in an array
                        foreach( $userAnswers[ $question['Id'] ] as $userAnswer ) {
                          $userAnswerIds[] = $userAnswer->question_answer_id;
                        }
                        //dd($question['correctanswers']);
                        $userAnswerStatus = [];
                        
                        // If any of the correct answers was not selected by the user
                        foreach( $question['correctanswers'] as $i => $correctAnswerId ) {
                            //dd($i);

                            
                            foreach ($correctAnswerId as $key1 => $value) {
                                //dd($userAnswerIds);
                            
                                  if ( !in_array($value, $userAnswerIds) ) 
                                  {
                                    $userAnswerStatus[] = 'wrong';
                                    if (  $question['guessing_panality'] == 0.25) {
                                            $points = '-' . $question['guessing_panality'];
                                        }
                                  }
                                  else{
                                    $userAnswerStatus[] = 'correct';
                                    $points = $question['mcsingleanswerpoint'];
                                }
                                //dd($userAnswerStatus);
                               

                              }  
                       

                        }
                        // If the number of answers selected by user was greater than or 
                        // less than the correct answer count? Wrong Answer! 
                    }

                    if ( in_array('correct', $userAnswerStatus)) 
                    {
                        $points = $question['mcsingleanswerpoint'];
                    } 
                    else 
                    {
                       $points = '-' . $question['guessing_panality'];
                    }*/

                    //dd($userAnswerStatus);
                    $questionAnwerPoint[$key]['question_id'] = $question['Id'];
                    $questionAnwerPoint[$key]['points']      = $points;
                    $questionAnwerPoint[$key]['is_correct']  = $is_correct;
                    $questionAnwerPoint[$key]['type']  = "multianswer";
                }

                
                elseif( ($question['question_type'] == "Fill in the blank" )){



                    $fibs=QuestionUserAnswer::join('questions','questions.id','=','question_user_answer.question_id')
                                                       ->join('question_type','question_type.id','=','questions.question_type_id') 
                                                        ->where('user_id',$params['user_id'])
                                                        ->where('question_type.id',4)
                                                        ->where('assessment_id',$params['assessment_id'])
                                                        ->where('assignment_id',$params['assignment_id'])
                                                        ->get();
                    $youranswers = []; 
                    $points = 0;                                   
                    foreach ($fibs as $key2 => $fib) {
                        $youranswers[$fib->question_id] = strtolower($fib->question_answer_text);
                    }
                     $correctanswer = strtolower($question['answers'][0]['ans_text']);
                     //dd($youranswers[$question['Id']]);

                     if(trim($youranswers[$question['Id']]) == trim($correctanswer))
                     {
                        $iscorrect = "Yes";
                        $points = $question['mcsingleanswerpoint'];
                     }
                    else
                    {
                        $iscorrect = "No";
                        if (  $question['guessing_panality'] == 0.25) {
                                $points = '-' . $question['guessing_panality'];
                            }
                    }

                     $questionAnwerPoint[$key]['question_id'] = $question['Id'];
                     $questionAnwerPoint[$key]['points']  = $points;
                     $questionAnwerPoint[$key]['is_correct'] = $iscorrect;
                     $questionAnwerPoint[$key]['type']  = "fib";
                     //dd("fill in the blanks");
                }

                elseif( ($question['question_type'] == 'Essay') || ($question['question_type'] == "Fill in the blank" )){
                    $essay_points=QuestionUserAnswer::where('user_id',$params['user_id'])->where('assessment_id',$params['assessment_id'])->where('assignment_id',$params['assignment_id'])->first()->points;
                     $questionAnwerPoint[$key]['question_id'] = $question['Id'];
                     $questionAnwerPoint[$key]['points']  = ($essay_points == null)? 0:$essay_points;
                     $questionAnwerPoint[$key]['is_correct'] = 'Open';
                     $questionAnwerPoint[$key]['type']  = "essay";
                     $questionAnwerPoint[$key]['essay'] = "";
                }
            }
        }
        //dd($questionAnwerPoint);
        return $questionAnwerPoint;
        
   }
   // check if all question has been graded agiant the section ,user and assessmetn

    public function gradedQuestion($assessment_id,$assignment_id, $user_id, $retake = ''){

        $assignment = Assignment::find( $assignment_id );

        $AssignQstAns = ($retake == '1') ? new QuestionUserAnswerRetake() : new QuestionUserAnswer();
        
        $questionId = [];
        
        $assignmentq =   $this->loadAssignmentQuestion( $assignment_id, $assessment_id );
        if ( !empty( $assignmentq )) {
            foreach ( $assignmentq as $key => $value ) {
                $questionId[] = $value['Id'];
                $guessing_panality=$value['guessing_panality'];
                //dd($guessing_panality);
            }
        }

        $count=0;
        $rawScore = 0;
        $gradeAllQuestion = false;
        $correctCount = 0;
        $wrongCount=0;
        $totalCount = 0;

        $sqstAns = $AssignQstAns->whereIn('question_id',$questionId)->where('assignment_id',$assignment_id)->where('user_id',$user_id)->whereNotNull('points')->groupBy('question_id','points')->select(DB::Raw('min(id)'),'points', DB::Raw('is_correct as IsCorrect'))->get();

        $count = $AssignQstAns->whereIn('question_id',$questionId)->where('assignment_id',$assignment_id)->where('user_id',$user_id)->whereNotNull('points')->distinct()->count('question_id'); 
        //dd($sqstAns);
        if(count($questionId) == $count){
            $gradeAllQuestion = true;

            foreach ($sqstAns as $key => $value) {
                $rawScore += $value->points; 
                $totalCount++;
                if($guessing_panality>0 && ( strtolower($value->IsCorrect) != 'yes' ))
                    {
                     $wrongCount += $guessing_panality;
                    }
            }
        }
        $rawScore=$rawScore-$wrongCount;
        
        //dd($rawScore);
        return array(
            'rawscore'     => $rawScore,
            'is_gradedAll' => $gradeAllQuestion
        );
    }

    // function to return the total point of all the question that is belong to that section

    public function totolQuestionPoints($assessment_id, $assignment_id){
        
        $totalPoints = 0;

        $assignmentq =   $this->loadAssignmentQuestion( $assignment_id, $assessment_id );
        //dd($assignmentq);
        foreach($assignmentq as $question){
            $questionType = $question['question_type'];
            if($questionType == "Essay"){
                $totalPoints= $totalPoints+$question['essayanswerpoint'];
            }
            if($questionType=="Multiple Choice - Single Answer" || $questionType=="Selection" || $questionType == "Fill in the blank"){
                $totalPoints= $totalPoints+$question['mcsingleanswerpoint'];
            }
            if($questionType =="Multiple Choice - Multi Answer"){
                $totalPoints= $totalPoints+$question['mcsingleanswerpoint'];
            }           
        }
        //dd($totalPoints);
        return $totalPoints;
    }
    /**
     * Returns the overall assignment grading status to be put in AssessmentAssignment table
     * @param  [int] assignmentId
     */
    public function getGradeStatus($assignmentId){

        $accessAssessmentUser = new AssignmentUser();

        $totalUser = $accessAssessmentUser->where('assignment_id', $assignmentId)->count();
        $gradedUser = $accessAssessmentUser->where('assignment_id', $assignmentId)->where('gradestatus','completed')->count();

        $status = 'notstarted';

        if ( $totalUser == $gradedUser ) {
            $status  =  "completed";
        } else if ( $gradedUser !=0 && $gradedUser < $totalUser ) {
            $status  = "inprogress";
        }

        return $status;
    }



    public function getGradeAssignment($instute_id=0){

        $query = DB::table('assignment as asn')
            ->join('assessment as ass', function($join){
                $join->on('ass.id', '=', 'asn.assessment_id');
            });
            $sessRole = getRole() ;
            if($sessRole != 'administrator' && $sessRole != 'admin')
            {
              $ass=$query  ->where('grader_id',Auth::user()->id)
                ->select(DB::raw('ass.name as assessment_name, asn.name as assignment_name, asn.id as assignmentId,ass.id assessmentId'));
            }
            else if($sessRole == 'admin') {
                $ass=$query->where('grader_id',Auth::user()->id)->where('asn.institution_id',Auth::user()->institution_id)
                ->select(DB::raw('ass.name as assessment_name, asn.name as assignment_name, asn.id as assignmentId,ass.id assessmentId'));
            }
        else{
            $ass=$query->select(DB::raw('ass.name as assessment_name, asn.name as assignment_name, asn.id as assignmentId,ass.id assessmentId'));
        }
        $assignments = $ass->get();
        return $assignments;


    }

    public function getUsersByAssignment($assignment_id=0){

        $query = DB::table('assignment_user as asn')
            ->join('users as u', function($join){
                $join->on('u.id', '=', 'asn.user_id');
            });
        if($assignment_id > 0)
        {
            $query->where("asn.assignment_id", $assignment_id);
        }
            $query->select(DB::raw('u.id, first_name, last_name,asn.assessment_id,asn.assignment_id'));
            
        $asign_users = $query->get();
        return $asign_users; 
    }
    public function getUsersById($student_id=0){
        //dd($student_id);
           $query = DB::table('assignment_user as asn')
               ->join('users as u', function($join){
                $join->on('u.id', '=', 'asn.user_id');
            });
         
        if($student_id > 0)
        {
            $query->where("u.id", $student_id);
        }
        $query->select(DB::raw('u.id, first_name, last_name'));

        $asign_users = $query->distinct()->get();
        //dd($asign_users);
        return $asign_users;
    }

    public function getAssignmentGradeStatus(){

        $user=Auth::user();
        $role=getRole();
        $obj=Assignment::join('assignment_user','assignment.id','=','assignment_user.assignment_id');
        
//dd($graded_students);

        if($role=="administrator"){
         $total_students=$obj->selectRaw('assignment.id,count(assignment_user.user_id) as total_students')->groupBy('assignment.id')->lists('total_students','id');
            $graded_students=$obj->where('assignment_user.gradestatus',"completed")
           ->selectRaw('assignment.id,count(assignment_user.user_id) as completed_students')->groupBy('assignment.id')->lists('completed_students','id');
        }
        else if($role=="admin" || $role == "teacher"){

        $total_students=$obj->selectRaw('assignment.id,count(assignment_user.user_id) as total_students')
        ->where('assignment.grader_id',$user->id)
        ->groupBy('assignment.id')->lists('total_students','id');

        $graded_students=$obj->where('assignment_user.gradestatus',"completed")
        ->where('assignment.grader_id',$user->id)
        ->selectRaw('assignment.id,count(assignment_user.user_id) as completed_students')->groupBy('assignment.id')
        ->lists('completed_students','id');
       // ->get();
        }

$status[0]=$total_students;
$status[1]=$graded_students;
//dd($status);
return $status;
    }


// Creates an Excel Template for the User importing the Scores/Grades of the Students for Assignments.
    public function bulkGradeTemplate($filename, $userType, $instituteId = null, $assignment_id=0, $assignment_text=null, $addSubjects = false, $findInstituteId = false) {

        $objPHPExcel = new PHPExcel();
        $institues = [];//new Institution();
        $madeDataValidationColumn = array();
        $students_arr=AssignmentUser::where('assignment_id',$assignment_id)->lists('user_id');
        $students_list=User::whereIn('id',$students_arr)->lists('name','id');
        $countries= [];//$this->getcountries();
        $states=[];//$this->getstates();
    //Create Validation for School and State
        $objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
        $indexSchool = 1;
        $indexState = 1;
        $exportFields = array(
           // 'InstitutionID' => $madeDataValidationColumn,
            'Assignment' => array('options'=>[$assignment_text]),      
            'Student' => array('options'=>$students_list),
            'Score' => array(),
            'Percentage' => array(),
            'Raw Score' => array(),
            'Grade' => array(),
            'Score Type' => array(),      
            'Percentile' => array(),
        );

        $firstRow = false;
        $celli = 'A';
        $rowsToFill = 100;
        foreach ($exportFields as $field => $options) {
            $objPHPExcel->getActiveSheet()->setCellValue($celli . '1', $field);
            $objPHPExcel->getActiveSheet()->getStyle($celli . '1:' . $celli . $rowsToFill)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            if (is_array($options) && isset($options['options'])) {
                if (isset($options['multiselect']) && $options['multiselect'] == true) {
                    for ($j = 0; $j < count($options['options']); $j++) {
                        $objPHPExcel->getActiveSheet()->setCellValue($celli . '1', $field . '-' . $options['options'][$j]);

                        for ($i = 2; $i <= $rowsToFill; $i++) {
                            $objValidation = $objPHPExcel->getActiveSheet()->getCell($celli . $i)->getDataValidation();
                            $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                            $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                            $objValidation->setAllowBlank(false);
                            $objValidation->setShowInputMessage(true);
                            $objValidation->setShowErrorMessage(true);
                            $objValidation->setShowDropDown(true);
                            $objValidation->setErrorTitle('Input error');
                            $objValidation->setError('Value is not in list.');
                            $objValidation->setPromptTitle('Pick ' . $field);
                            $objValidation->setPrompt('Please pick a value from the drop-down list.');
                            $objValidation->setFormula1('"X"');
                        }
                        if ($j != count($options['options']) - 1)
                            $celli++;
                    }
                }else {

                    for ($i = 2; $i <= $rowsToFill; $i++) {
                        $objValidation = $objPHPExcel->getActiveSheet()->getCell($celli . $i)->getDataValidation();
                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setShowDropDown(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('Value is not in list.');
                        $objValidation->setPromptTitle('Pick ' . $field);
                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
                        $objValidation->setFormula1('"' . implode(',', $options['options']) . '"');

                        if (isset($options['validation'])) {
                            if (($options['validation'] == 'state') && $indexState > 1) {
                                $objValidation->setFormula1('options!$A$1:$A$' . ($indexState - 1));
                            }
                            if (($options['validation'] == 'school') && $indexSchool > 1) {
                                $objValidation->setFormula1('options!$B$1:$B$' . ($indexSchool - 1));
                            }
                        }
                    }
                }
            }

            $celli++;
        }
        // if($findInstituteId && $instituteId!=null){
        //     $objPHPExcel->getActiveSheet()->setCellValueExplicit('A2', $assignment_text, \PHPExcel_Cell_DataType::TYPE_STRING);
        //      // $objPHPExcel->getActiveSheet()->setCellValueExplicit('B2', $assignment_text, \PHPExcel_Cell_DataType::TYPE_STRING);
        // }
        $highestColumn = User::createColumnsArray($objPHPExcel->getActiveSheet()->getHighestColumn());
        foreach ($highestColumn as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        if (!is_dir(public_path() . '/data/tmp')) {
            mkdir(public_path() . '/data/tmp', 0777);
            chmod(public_path() . '/data/tmp', 0777);
        }

        $save = $objWriter->save(public_path() . '/data/tmp/' . $filename);
        return $save;
   
    }


}
