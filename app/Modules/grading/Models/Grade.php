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

        $assessment = Assessment::with('category', 'testTypes')->find(  $params['AssessmentId']  );
        //get the point of each question that is assigned to the user 
        //s
        $sctId = $params['SectionId'];
        $subsection = $obj->with('children')->find($sctId);
        if( count($subsection->children) ){

            foreach( $subsection->children as $sectionId){
                $params['SectionId'] = $sectionId->Id;
                $subsectionQstUsrAnws =  $obj->calculateQuestionPoints( $params );
                $sQuAnws->saveUserPoints($subsectionQstUsrAnws,$params['UserId'],$params['AssignmentId']);
            }
        } else {

            $subsectionQstUsrAnws =  $obj->calculateQuestionPoints( $params );
            $sQuAnws->saveUserPoints($subsectionQstUsrAnws,$params['UserId'],$params['AssignmentId']);
        }

        if( multiKeyExists($subsectionQstUsrAnws,'essay')){
            $assessmentAssignmentUser->updateUserGradeStatus(array(
                'assignmentId' => $params['AssignmentId'],
                'userId' => $params['UserId'],
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
                $gradeAllQuestion = $obj->gradedQuestion($params['SectionId'],$params['AssignmentId'] ,$params['UserId'], (isset($params['retake']) ? :''));
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
                        'assmentId' => $params['AssessmentId'],
                        'rawScore'  => $rawScore
                    ]);

                    $scaledScore = $gradeData['scaleScore'];
                    $grade       =  $gradeData['grade'];
                    $scoreType   =  $gradeData['scoreType'];
                    $percentile  =  $gradeData['percentile'];

                    $uSrArslt = (isset($params['retake']) && $params['retake'] == '1') ? new UserAssignmentResultRetake() : new UserAssignmentResult();
                    $uSrArslt->insertUserAssessmentAssignmentRslt(
                        array(
                            'assmentId'     => $params['AssessmentId'],
                            'assignmentId'  => $params['AssignmentId'],
                            'userId'        => $params['UserId'],
                            'sectionId'     => $params['SectionId'],
                            'ScaledScore'   => $scaledScore,
                            'ScoreType'     => $scoreType,
                            'Grade'         => $grade,
                            'rawScore'      => $rawScore,
                            'percentage'    => $percentage,
                            'Percentile'    => $percentile,
                        )
                    ); 
                    $update = $assessmentAssignmentUser->updateAssignmentRecords(
                                    array(
                                        'assmentId'     => $params['AssessmentId'],
                                        'assignmentId'  => $params['AssignmentId'],
                                        'userId'        => $params['UserId'],
                                        'ScaledScore'   => $scaledScore,
                                        'Grade'         => $grade,
                                        'rawScore'      => $rawScore,
                                        'percentage'    => $percentage,
                                        'UserStatus'    => 'Complete'
                                    )
                    );

                    if (isset($params['retake']) && $params['retake'] == '1') {
                        $this->_compareScale($params);
                    }
                
                $status =$this->getGradeStatus($params['AssignmentId']);
                $assmntAssignment->updateGradeStatus($params['AssignmentId'],$status);
            }
        } 

    }
}
