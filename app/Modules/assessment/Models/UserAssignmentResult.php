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
        $obj = $this->where('assessment_id',$filters['assessment_id'])->where('assignment_id',$filters['assignment_id'])->where('user_id',$filters['user_id'])->first();
        if(!count($obj)){ 
            $obj = new UserAssignmentResult();
            $obj->added_by = Auth::user()->id;
        }
        else{
            $obj->updated_by = Auth::user()->id;
        }

        $obj->assessment_id = $filters['assessment_id'];
        $obj->assignment_id = $filters['assignment_id'];
        $obj->user_id       = $filters['user_id'];
        $obj->rawscore     = $filters['rawscore'];
        $obj->percentage   = $filters['percentage'];
        $obj->grade   = (isset($filters['grade']) ? $filters['grade']:'');
        $obj->scoretype   = (isset($filters['scoretype']) && !empty( $filters['scoretype'] ) ?$filters['scoretype']:'scaledscore');
        $obj->percentile     =$filters['percentile'];
        return $obj->save();

    }
}
