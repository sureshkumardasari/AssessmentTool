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

class UserAssignmentResult extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_assignment_result';

	public function insertUserAssessmentAssignmentRslt($filters){
        $obj = $this->where('AssessmentId',$filters['assmentId'])->where('AssignmentId',$filters['assignmentId'])->where('UserId',$filters['userId'])->where('SectionId',$filters['sectionId'])->first();
        if(!count($obj)){ 
            $obj = new UserAssignmentResult();;
        }

        $obj->AssessmentId = $filters['assmentId'];
        $obj->AssignmentId = $filters['assignmentId'];
        $obj->UserId       = $filters['userId'];
        $obj->SectionId    =  $filters['sectionId'];
        $obj->Score        = $filters['ScaledScore'];
        $obj->RawScore     = $filters['rawScore'];
        $obj->Percentage   = $filters['percentage'];
        $obj->Grade   = (isset($filters['Grade']) ? $filters['Grade']:null);
        $obj->ScoreType   = (isset($filters['ScoreType']) && !empty( $filters['ScoreType'] ) ?$filters['ScoreType']:'ScaledScore');
        $obj->Percentile     =$filters['Percentile'];
        return $obj->save();

    }
}
