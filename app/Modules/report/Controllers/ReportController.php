<?php namespace App\Modules\Report\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Assessment\Models\QuestionUserAnswer;
use App\Modules\Assessment\Models\UserAssignmentResult;
use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Resources\Models\AssignmentUser;
use App\User;
use Illuminate\Http\Request;
use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Admin\Models\Role;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function view()
	{
		return view('report::report.report');
	}
	public function scores()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$assessment=Assessment::get();
		return view('report::report.assessment',compact('inst_arr','assessment'));
	}
	public function assignment()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		return view('report::report.assignment',compact('inst_arr'));
	}
	public function student()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		return view('report::report.student',compact('inst_arr'));
	}
	public function answer()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		return view('report::report.answer',compact('inst_arr'));
	}
	public function report_inst($id){
		$assignment=Assignment::where('institution_id',$id)
				->select('name','id')
				->get();
		return $assignment;
	}
	public function report_assignment($inst_id,$assi_id){

		$userids=QuestionUserAnswer::select('user_id')->where('assignment_id','=',$assi_id)->get();
		$c=array();
		foreach($userids as $userid)
			array_push($c,$userid->user_id);
		$students=DB::table('assignment_user')
				->join('users','users.id','=','assignment_user.user_id')
				->join('assessment_question','assessment_question.assessment_id','=','assignment_user.assessment_id')
				->select(DB::raw('count(*) as question_id, users.name as user_name'))
				->where('assignment_user.assignment_id','=',$assi_id)
				->groupby('users.name')
				->get();

		/*$correct=DB::table('question_user_answer')
				->join('users','users.id','=','question_user_answer.user_id')
				->join('assessment_question','assessment_question.assessment_id','=','question_user_answer.assessment_id')
				->select(DB::raw('count(assignment_id) as is_correct,count(*) as question_id,users.name as user_name'))
				->where('assignment_id','=',$assi_id)
				->wherein('users.id',$c)
				->where('is_correct','like','yes')
				->groupby('users.name')
				->get();
		dd($correct);*/

		/*	dd($students);*/
		return view('report::report.assignmentview',compact('students'));
	}

	public function assess_inst($id){
		$assessnment=Assessment::where('institution_id',$id)
				->select('name','id')
				->get();
		return $assessnment;
	}
	public function report_assessment($inst_id,$assi_id){
		$students=DB::table('assignment_user')
				->join('users','users.id','=','assignment_user.user_id')
				->join('assessment_question','assessment_question.assessment_id','=','assignment_user.assessment_id')
				->select(DB::raw('count(*) as question_id, users.name as user_name'))
				->where('assignment_user.assignment_id','=',$assi_id)
				->groupby('users.name')
				->get();
		return view('report::report.assignmentview',compact('students'));
	}
}
