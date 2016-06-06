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

        $netSectionPercentage  = 0;
        $obj                     = new Subsection();       
        $sQuAnws                 = (isset($params['retake']) && $params['retake'] == '1') ? new QuestionUserAnswerRetake() : new QuestionUserAnswer();
        $grade                   = new Grade();
        $assmntAssignment        = new Assignment();
        $assessmentAssignmentUser = new AssignmentUser();        
        
        $totalScaledScore    = 0;
        $totalRawScore       = 0;
        $totalPercentage     = 0;

        $assessment = Assessment::with('category', 'testTypes')->find(  $params['assessment_id']  );
        //get the point of each question that is assigned to the user 
        //s
        $sctId = $params['SectionId'];
        $subsection = $obj->with('children')->find($sctId);
        

        $subsectionQstUsrAnws =  $this->calculateQuestionPoints( $params );
        $sQuAnws->saveUserPoints($subsectionQstUsrAnws,$params['user_id'],$params['assignment_id']);

        if( multiKeyExists($subsectionQstUsrAnws,'essay')){
            $assessmentAssignmentUser->updateUserGradeStatus(array(
                'assignment_id' => $params['assignment_id'],
                'user_id' => $params['user_id'],
                'status' => "In Progress"
            ));
        
        } else {

            $childIds = [];
            $subsection = $obj->with('children')->find($sctId);
            if( count($subsection->children) ){

                foreach ( $subsection->children as $section){
                    $childIds[] = $section->Id; 
                }

            } else {
                $childIds[] = $subsection->Id; 
            }

            foreach( $childIds as $childId ){
                $params['SectionId'] = $childId;
                $subScTmpl = new SubsectionScoreTemplate();
                $gradeAllQuestion = $obj->gradedQuestion($params['SectionId'],$params['assignment_id'] ,$params['user_id'], (isset($params['retake']) ? :''));
                $netScore = $obj->totolQuestionPoints($params['SectionId']);
                $rawScore   = $gradeAllQuestion['rawScore'];

                // To make it not throw divide by zero exception
                if ( $netScore != 0 ) {
                    $percentage = ($rawScore / $netScore) * 100;   
                } else {
                    $percentage = 0;
                }
                
                $netSectionPercentage += $percentage;

                // Default values
                $scaledScore   = 0;
                $grade         = null;
                $percentile    = 0;
                $scoreType     = 'ScaledScore';

                
                    // if type is formative
                     // if assessment is formative
                    // get the scalescore ,score type ,and grade 
                        // Formative
                    $gradeData = $subScTmpl->getFormativeScaleScore([
                        'sectionId' => $params['SectionId'],
                        'assmentId' => $params['assessment_id'],
                        'rawScore'  => $rawScore
                    ]);

                    $scaledScore = $gradeData['scaleScore'];
                    $grade       =  $gradeData['grade'];
                    $scoreType   =  $gradeData['scoreType'];
                    $percentile  =  $gradeData['percentile'];

                    $uSrArslt = (isset($params['retake']) && $params['retake'] == '1') ? new UserAssignmentResultRetake() : new UserAssignmentResult();
                    $uSrArslt->insertUserAssessmentAssignmentRslt(
                        array(
                            'assessment_id'     => $params['assessment_id'],
                            'assignment_id'  => $params['assignment_id'],
                            'user_id'        => $params['user_id'],
                            'sectionId'     => $params['SectionId'],
                            'scaledscore'   => $scaledScore,
                            'scoretype'     => $scoreType,
                            'grade'         => $grade,
                            'rawscore'      => $rawScore,
                            'percentage'    => $percentage,
                            'percentile'    => $percentile,
                        )
                    ); 
                    $update = $assessmentAssignmentUser->updateAssignmentRecords(
                                    array(
                                        'assessment_id'     => $params['assessment_id'],
                                        'assignment_id'  => $params['assignment_id'],
                                        'user_id'        => $params['user_id'],
                                        'scaledscore'   => $scaledScore,
                                        'grade'         => $grade,
                                        'rawscore'      => $rawScore,
                                        'percentage'    => $percentage,
                                        'status'    => 'Complete'
                                    )
                    );

                    if (isset($params['retake']) && $params['retake'] == '1') {
                        $this->_compareScale($params);
                    }
                
                $status =$this->getGradeStatus($params['assignment_id']);
                $assmntAssignment->updateGradeStatus($params['assignment_id'],$status);
            }
        } 

    }

    public function calculateQuestionPoints( $params ){
        $sQuAnws     = (isset($params['retake']) && $params['retake'] == '1') ? new QuestionUserAnswerRetake() : new QuestionUserAnswer();
        $userAnswers = $sQuAnws->getUserAssignmentAnswers( $params['user_id'], $params['assignment_id'] );
        $section =   $this->loadSectionQuestion( $params['SectionId'] );
        $questionAnwerPoint = [];
        foreach ( $section->questions as $key => $question) {
            if(isset($question->qbank->QuestionType->Option)){
                if( ( $question->qbank->QuestionType->Option == 'Multiple Choice - Multi Answer')  ||
                        ( $question->qbank->QuestionType->Option == 'Multiple Choice - Single Answer') ||
                        ( $question->qbank->QuestionType->Option == 'Selection')
                ) {

                    $userAnswerStatus = 'no-response';
                    $points = 0;
                    $userAnswerIds = [];

                    if( !empty( $userAnswers[ $question->Id ]) ) {

                        // Prepare the User Answer Ids in an array
                        foreach( $userAnswers[ $question->Id ] as $userAnswer ) {
                          $userAnswerIds[] = $userAnswer->QuestionAnswerId;
                        }

                        $userAnswerStatus = 'correct';

                        // If any of the correct answers was not selected by the user
                        foreach( $question->qbank->correctAnswers as $correctAnswer ) {
                          if ( !in_array($correctAnswer->Id, $userAnswerIds) ) {
                            $userAnswerStatus = 'wrong';
                          }
                        }

                        // If the number of answers selected by user was greater than or 
                        // less than the correct answer count? Wrong Answer!
                        if ( count($question->qbank->correctAnswers) !== count($userAnswerIds) ) {
                          $userAnswerStatus = 'wrong';
                        }

                        if ( $userAnswerStatus == 'correct' ) {

                          if ( !empty( $section->ParentId ) ) {
                              $points = $section->parentRecord->PointForEachMcAndSelection;
                          } else {
                              $points = $section->PointForEachMcAndSelection;
                          }

                        } else if ( $userAnswerStatus == 'wrong' ) {
                            if ( !empty( $section->ParentId ) ) {
                                $points = '-' . $section->parentRecord->McAndSelectionGuessingPenalty;
                            } else {
                                $points = '-' . $section->McAndSelectionGuessingPenalty;
                            }
                        }
                    }
                    $questionAnwerPoint[$key]['question_id'] = $question->Id;
                    $questionAnwerPoint[$key]['points']               = $points;
                    $questionAnwerPoint[$key]['is_correct']            = swapValue($userAnswerStatus);
                }

                elseif( $question->qbank->QuestionType->Option == 'Student-Produced Response' ){
                  $userAnswerStatus = 'no-response';
                  $points = 0;

                  if ( !empty($userAnswers[ $question->Id ]) ) {
                      foreach ( $question->qbank->constraints as $constraint ) {

                          if ( ( $constraint->type->Option == 'Specific Value Constraint' ) || ($constraint->type->Option == 'Specific Value')) {
                              if ( $userAnswers[ $question->Id ][0]->QuestionAnswerText == $constraint->SpecificValue ) {
                                  $userAnswerStatus = 'correct';
                                  $points = !empty($section->ParentId) ? $section->parentRecord->PointForEachSPR : $section->PointForEachSPR;
                                  break;
                              } else {
                                  $userAnswerStatus = 'wrong';
                                  $points = '-' . (!empty($section->ParentId) ? $section->parentRecord->SPRGuessingPenalty : $section->SPRGuessingPenalty);
                              }
                          } else if ( $constraint->type->Option == 'Range of Values' ) {
                              if ( ( $userAnswers[ $question->Id ][0]->QuestionAnswerText >= $constraint->From ) && ( $userAnswers[ $question->Id ][0]->QuestionAnswerText <= $constraint->To )) {
                                  $userAnswerStatus = 'correct';
                                  $points = !empty($section->ParentId) ? $section->parentRecord->PointForEachSPR : $section->PointForEachSPR;
                                  break;
                              } else {
                                  $userAnswerStatus = 'wrong';
                                  $points = '-' . (!empty($section->ParentId) ? $section->parentRecord->SPRGuessingPenalty : $section->SPRGuessingPenalty);
                              }
                          } else if ( $constraint->type->Option == 'Must Equal Decimal' ) {
                            if ( $userAnswers[ $question->Id ][0]->QuestionAnswerText == $constraint->AfterDecimal ) {
                                $userAnswerStatus = 'correct';
                                $points = (!empty($section->ParentId) ? $section->parentRecord->PointForEachSPR : $section->PointForEachSPR);
                                break;
                            } else {
                                $userAnswerStatus = 'wrong';
                                $points = '-' . (!empty($section->ParentId) ? $section->parentRecord->SPRGuessingPenalty : $section->SPRGuessingPenalty);
                            }
                          } else if ( $constraint->type->Option == 'Can be One of Many Values' ) {

                            $userValue = $userAnswers[$question->Id][0]->QuestionAnswerText;

                            $possibleValues = $constraint->ManyValue;
                            $possibleValues = trim( $possibleValues );
                            $possibleValues = explode(',', $possibleValues);

                            if ( in_array($userValue, $possibleValues) ) {
                                $userAnswerStatus = 'correct';
                                break;
                                $points = !empty($section->ParentId) ? $section->parentRecord->PointForEachSPR : $section->PointForEachSPR;
                            } else {
                                $userAnswerStatus = 'wrong';
                                $points = '-' . (!empty($section->ParentId) ? $section->parentRecord->SPRGuessingPenalty : $section->SPRGuessingPenalty);
                            }
                          }
                      }
                  }
                     $questionAnwerPoint[$key]['question_id'] = $question->Id;
                     $questionAnwerPoint[$key]['points']               = $points;
                     $questionAnwerPoint[$key]['is_correct']            = swapValue($userAnswerStatus);
                  }

                elseif( $question->qbank->QuestionType->Option == 'Essay' ){
                     $questionAnwerPoint[$key]['question_id'] = $question->Id;
                     $questionAnwerPoint[$key]['points']               = null;
                     $questionAnwerPoint[$key]['is_correct']            = 'Open';
                     $questionAnwerPoint[$key]['essay'] =              "";
                }
            }
        }
        return $questionAnwerPoint;
        
   }
   // check if all question has been graded agiant the section ,user and assessmetn

    public function gradedQuestion($sectionId,$assessmentId,$userId, $retake = ''){

        $assignment = Assignment::find( $assessmentId );
        $assessment = $assignment->assessment;
        $testType = (isset($assessment->testTypes) && isset($assessment->testTypes->Display)) ? $assessment->testTypes->Display : '';

        $subSectionQstAns = ($retake == '1') ? new QuestionUserAnswerRetake() : new QuestionUserAnswer();
        $subsectionId = [];
        $sub = $this->with('questions')->where('Id',$sectionId)->first();
        if ( !empty( $sub )) {
            foreach ( $sub->questions as $key => $value ) {
                $subsectionId[] = $value->Id;
            }
        }
        $count=0;
        $rawScore = 0;
        $gradeAllQuestion = false;
        $correctCount = 0;
        $totalCount = 0;

        $sqstAns = $subSectionQstAns->whereIn('question_id',$subsectionId)->where('assignment_id',$assessmentId)->where('user_id',$userId)->whereNotNull('points')->groupBy('question_id','points')->select(DB::Raw('min("Id")'),'points', DB::Raw('string_agg("is_correct", \',\') as "IsCorrect"'))->get();
        $count = $subSectionQstAns->whereIn('question_id',$subsectionId)->where('assignment_id',$assessmentId)->where('user_id',$userId)->whereNotNull('points')->distinct()->count('question_id');  
        if(count($subsectionId) == $count){
            $gradeAllQuestion = true;

            foreach ($sqstAns as $key => $value) {
                $rawScore += $value->Points;

                if ( strtolower($value->IsCorrect) == 'yes' ) {
                    $correctCount++;
                }

                $totalCount++;
            }
        }
        

        return array(
            'rawScore'     => $rawScore,
            'is_gradedAll' => $gradeAllQuestion
        );
    }

    // function to return the total point of all the question that is belong to that section

    public function totolQuestionPoints($sectionId){
        $question = [];
        $sectionQuestion = $this->with('question')->where('Id',$sectionId)->first();   
        $parentSubsection  = $this->where('Id',$sectionQuestion->ParentId)->first();
        $totalPoints=0;
        $mulitpleChoicePoint = $sectionQuestion->PointForEachMcAndSelection;
        if( !empty( $sectionQuestion->ParentId )){
            $mulitpleChoicePoint = $parentSubsection->PointForEachMcAndSelection;
        }
        $essayPoint = $sectionQuestion->PointForEachOER;
        if( !empty( $sectionQuestion->ParentId )){
            $essayPoint = $parentSubsection->PointForEachOER;
        }
        $studentResponsePoint = $sectionQuestion->PointForEachSPR;
        if( !empty( $sectionQuestion->ParentId )){
            $studentResponsePoint = $parentSubsection->PointForEachSPR;
        }
        foreach($sectionQuestion->question as $quest){
            $questionType = $quest->questionType->Option;
            if($questionType == "Essay"){
                $totalPoints= $totalPoints+$essayPoint;
            }
            if($questionType=="Multiple Choice- Multi Answer"||$questionType=="Multiple Choice- Single Answer" || $questionType=="Selection"){
                $totalPoints= $totalPoints+$mulitpleChoicePoint;
            }

            if($questionType=="Student-Produced Response"){
                $totalPoints= $totalPoints+$studentResponsePoint;
            }
        }

        return $totalPoints;
        // return $question;

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
}
