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
           

        $AssignmentQstUsrAnws =  $this->calculateQuestionPoints( $params );
        
        $sQuAnws->saveUserPoints($AssignmentQstUsrAnws,$params['user_id'],$params['assignment_id']);
        if( !isset($params['essay_grade'])) {


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
                        ->select("q.id","q.title","qt.id as qtype_id","qt.qst_type_text as question_type","qa.id as answer_id","qa.ans_text as ans_text","qa.is_correct","a.guessing_panality","a.mcsingleanswerpoint","a.essayanswerpoint")
                        ->orderby('qt.id','ASC')
                        ->orderby('aq.id', 'ASC')
                        ->orderby('qa.order_id', 'ASC')
                        ->get();
        // dd($results);
        $questions = [];                
        foreach ($results as $key => $row) {
            $questions[$row->id]['Id'] = $row->id;
            $questions[$row->id]['qtype_id'] = $row->qtype_id;
            $questions[$row->id]['Title'] = $row->title;
            $questions[$row->id]['ans_text'] = $row->ans_text;
            $questions[$row->id]['question_type'] = $row->question_type;
            $questions[$row->id]['guessing_panality'] = $row->guessing_panality;
            $questions[$row->id]['mcsingleanswerpoint'] = $row->mcsingleanswerpoint;
            $questions[$row->id]['essayanswerpoint'] = $row->essayanswerpoint;
            $questions[$row->id]['answers'][] = ['Id' => $row->answer_id,'ans_text'=>$row->ans_text , 'is_correct' => $row->is_correct];

            if($row->is_correct == 'YES')
            $questions[$row->id]['correctanswers'][] = $row->answer_id;
        }
        //dd($questions);
        return $questions;  
    }

    public function calculateQuestionPoints( $params ){
        $sQuAnws     = (isset($params['retake']) && $params['retake'] == '1') ? new QuestionUserAnswerRetake() : new QuestionUserAnswer();
        $userAnswers = $sQuAnws->getUserAssignmentAnswers( $params['user_id'], $params['assignment_id'] );
        $assignmentq =   $this->loadAssignmentQuestion( $params['assignment_id'], $params['assessment_id'] );
        
        $questionAnwerPoint = [];
        foreach ( $assignmentq as $key => $question) {
            if(isset($question['question_type'])){
                if( ( $question['question_type'] == 'Multiple Choice - Multi Answer')  || 
                        ( $question['question_type'] == 'Multiple Choice - Single Answer') ||
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

                        $userAnswerStatus = 'correct';

                        // If any of the correct answers was not selected by the user
                        foreach( $question['correctanswers'] as $i => $correctAnswerId ) {
                          if ( !in_array($correctAnswerId, $userAnswerIds) ) {
                            $userAnswerStatus = 'wrong';
                          }
                        }

                        // If the number of answers selected by user was greater than or 
                        // less than the correct answer count? Wrong Answer!
                        if ( count($question['correctanswers']) !== count($userAnswerIds) ) {
                          $userAnswerStatus = 'wrong';
                        }

                        if ( $userAnswerStatus == 'correct' ) {
                            $points = 1;
                        } else if ( $userAnswerStatus == 'wrong' ) {
                            if (  $question['guessing_panality'] == 0.25) {
                                $points = '-' . $question['guessing_panality'];
                            }
                        }
                    }
                    $questionAnwerPoint[$key]['question_id'] = $question['Id'];
                    $questionAnwerPoint[$key]['points']      = $points;
                    $questionAnwerPoint[$key]['is_correct']  = swapValue($userAnswerStatus);
                }      
                elseif( $question['question_type'] == 'Essay' ){
                     $questionAnwerPoint[$key]['question_id'] = $question['Id'];
                     $questionAnwerPoint[$key]['points']               = 0;
                     $questionAnwerPoint[$key]['is_correct']            = 'Open';
                     $questionAnwerPoint[$key]['essay'] =              "";
                }
            }
        }
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
            }
        }

        $count=0;
        $rawScore = 0;
        $gradeAllQuestion = false;
        $correctCount = 0;
        $totalCount = 0;

        $sqstAns = $AssignQstAns->whereIn('question_id',$questionId)->where('assignment_id',$assignment_id)->where('user_id',$user_id)->whereNotNull('points')->groupBy('question_id','points')->select(DB::Raw('min(id)'),'points', DB::Raw('is_correct as IsCorrect'))->get();

        $count = $AssignQstAns->whereIn('question_id',$questionId)->where('assignment_id',$assignment_id)->where('user_id',$user_id)->whereNotNull('points')->distinct()->count('question_id'); 

        if(count($questionId) == $count){
            $gradeAllQuestion = true;

            foreach ($sqstAns as $key => $value) {
                $rawScore += $value->points;

                if ( strtolower($value->IsCorrect) == 'yes' ) {
                    $correctCount++;
                }

                $totalCount++;
            }
        }
        

        return array(
            'rawscore'     => $rawScore,
            'is_gradedAll' => $gradeAllQuestion
        );
    }

    // function to return the total point of all the question that is belong to that section

    public function totolQuestionPoints($assessment_id, $assignment_id){
        
        $totalPoints = 0;

        $assignmentq =   $this->loadAssignmentQuestion( $assignment_id, $assessment_id );
        
        foreach($assignmentq as $question){
            $questionType = $question['question_type'];
            if($questionType == "Essay"){
                $totalPoints= $totalPoints+$question['essayanswerpoint'];
            }
            if($questionType=="Multiple Choice - Multi Answer"||$questionType=="Multiple Choice - Single Answer" || $questionType=="Selection"){
                $totalPoints= $totalPoints+$question['mcsingleanswerpoint'];
            }            
        }
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


    /**

    */
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
                $ass=$query->where('asn.institution_id',Auth::user()->institution_id)
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
            $query->select(DB::raw('u.id, first_name, last_name'));
            
        $asign_users = $query->get();
        return $asign_users;
    }
    public function getUsersById($student_id=0){

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
        return $asign_users;
    }


}
