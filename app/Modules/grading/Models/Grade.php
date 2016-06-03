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

class Grade extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'question_user_answer';

	public function gradeSystemStudents( $params ){

        $netSectionPercentage  = 0;
        $obj                        = new Subsection();
        $sQuAnws                    = (isset($params['retake']) && $params['retake'] == '1') ? new SubsectionQuestionUserAnswerRetake() : new SubsectionQuestionUserAnswer();
        $grade                      = new Grade();
        $assmntAssignment           = new AssessmentAssignment();
        $assessmentAssignmentUser   = new AssessmentAssignmentUser();
        
        
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

                if ( $assessment->category->Option == 'Fixed Form' ) {
                    $testType = $assessment->testTypes->Display;

                    $subject = $obj->with('subject')->find($params['SectionId']);
                    
                    $subjectName  = $subject->subject->Display;
        
                    if($testType == "SAT 2005" && $subjectName =="Writing" ){
                        $writingTemplate = new SubsectionWritingScoreTemplate();
                        $sat2005ScaledScore = $writingTemplate->getSat2005ScaleScore([
                                'sectionId' => $params['SectionId'],
                                'assmentId' => $params['AssessmentId'],
                                'rawScore'  => $rawScore
                            ]);
            
                        if ( !empty($sat2005ScaledScore) ){
                            $scaledScore = $sat2005ScaledScore;
                        } else {
                            $scaledScore = 0;
                        }

                        // Getting the percentile for this scaled score
                        $scoreTemplate = SubsectionWritingScoreTemplate::where('AssessmentId', '=', $params['AssessmentId'])
                                                                       ->where('SubjectId', '=', 0)
                                                                       ->where('Type', '=', 'NonTemplate')
                                                                       ->first();

                        if ( $scoreTemplate ) {
                            $percentileRow = $scoreTemplate->children()->where('RawScore', '=', $scaledScore)
                                                                       ->where('Type', '=', 'NonTemplate')
                                                                       ->first();
                        }

                        if ( !empty( $percentileRow->Points) ) {
                            $percentile = $percentileRow->Points;
                        } 
                    } else {
                            $scaledScoreObject = $subScTmpl->getScaleScore([
                                'sectionId' => $params['SectionId'],
                                'assmentId' => $params['AssessmentId'],
                                'rawScore'  => $rawScore
                            ], true);

                            if ( !empty($scaledScoreObject) ) {
                                $scaledScore = $scaledScoreObject->ScaledScore;
                                $percentile = $scaledScoreObject->Percentile;
                            } 
                    }

                    // add an entry to user assessment assignment result.
                    
                    $uSrArslt = new UserAssessmentAssignmentResult();
                    $uSrArslt->insertUserAssessmentAssignmentRslt(
                            array(
                                'assmentId'     => $params['AssessmentId'],
                                'assignmentId'  => $params['AssignmentId'],
                                'userId'        => $params['UserId'],
                                'sectionId'     => $params['SectionId'],
                                'ScaledScore'   => $scaledScore,
                                'rawScore'      => $rawScore,
                                'percentage'    => $percentage,
                                'Percentile'    => $percentile,
                            )
                    ); 

                                    /// if children exist 
                    if( !empty($subject->ParentId)){
                        $gradedChildren = $uSrArslt->allChildrenSectionGrade($params,$subject->ParentId);
                        if( $gradedChildren['gradedAll'] ){
                            $param = array(
                                'sectionId' => $subject->ParentId,
                                'assmentId' => $params['AssessmentId'],
                                'assignmentId' => $params['AssignmentId'],
                                'userId'        => $params['UserId']

                            );

                            $score = $this->calculateParentSectionGrading($param,$gradedChildren['childIds']);

                            // return $score;
                            // insert Parent Section Data 
                            // return $score['Percentage'];
                            $uSrArslt->insertUserAssessmentAssignmentRslt(
                                array(
                                    'assmentId'     => $params['AssessmentId'],
                                    'assignmentId'  => $params['AssignmentId'],
                                    'userId'        => $params['UserId'],
                                    'sectionId'     => $param['sectionId'],
                                    'ScaledScore'   => $score['ScaleScore'],
                                    'rawScore'      => $score['RawScore'],
                                    'percentage'    => $score['Percentage'],
                                    'Percentile'    => $score['Percentile'],
                                )
                            );  
                        }
                    }
                    
                    $gradedAllSections = $uSrArslt->allSectionGraded([
                                            'assignmentId' => $params['AssignmentId'],
                                            'assmentId' => $params['AssessmentId'],
                                            'userId'  => $params['UserId']
                                          ]);
                    
                    if($gradedAllSections){
                        $userStatus  = "Complete";
                        $compositeScore = $uSrArslt->getCompositeScore([
                                            'assignmentId' => $params['AssignmentId'],
                                            'assmentId' => $params['AssessmentId'],
                                            'userId'  => $params['UserId']
                                          ]);
                        // assign composite score to filters array
                        $composite = array();
                        
                        $totalScaledScore  = $compositeScore['compositeScore'];
                        $totalRawScore     = $compositeScore['rawScore'];
                        $totalPercentage   = $compositeScore['percentage'];

                        //add the composite score entry to userAssessement Assignment Result 
                        $percentile = SubsectionScoreTemplate::getAssessmentPercentile( 
                            $params['AssessmentId'] , 
                            $totalScaledScore
                        );

                        $result = $uSrArslt->addCompositeResult(
                                    array(
                                        'assmentId'     => $params['AssessmentId'],
                                        'assignmentId'  => $params['AssignmentId'],
                                        'userId'        => $params['UserId'],
                                        'ScaledScore'   => $compositeScore['compositeScore'],
                                        'rawScore'      => $compositeScore['rawScore'],
                                        'percentage'    => $compositeScore['percentage'],
                                        'percentile'    => $percentile
                                    )); 
                                // return $subsection->with('questions','questions')->find($sectionId);

                    }

                    $assessementUser = array(
                            'assmentId'     => $params['AssessmentId'],
                            'assignmentId'  => $params['AssignmentId'],
                            'userId'        => $params['UserId'],
                            'ScaledScore'   => $totalScaledScore,
                            'rawScore'      => $totalRawScore,
                            'percentage'    => $totalPercentage
                        );

                    // if all section is graded then complete 
                    if(isset($userStatus)){
                        $assessementUser['UserStatus'] = $userStatus;
                    }
                    // if sucess also update the assessment assignment user table 
                    $update = $assessmentAssignmentUser->updateAssignmentRecords($assessementUser);

                }  else {
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

                    $uSrArslt = (isset($params['retake']) && $params['retake'] == '1') ? new UserAssessmentAssignmentResultRetake() : new UserAssessmentAssignmentResult();
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
                }
                $status =$this->getGradeStatus($params['AssignmentId']);
                $assmntAssignment->updateGradeStatus($params['AssignmentId'],$status);
            }
        } 

    }
}
