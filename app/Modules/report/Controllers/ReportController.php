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
use Thujohn\Pdf\PdfServiceProvider;
use Response;

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
				->leftjoin ('users','assignment_user.user_id','=','users.id')
				->leftjoin ('question_user_answer','assignment_user.user_id','=','question_user_answer.user_id','and','assignment_user.assignment_id','=','question_user_answer.assignment_id')
				->where('assignment_user.assignment_id','=',$assi_id)
				->select( DB::raw('
					assignment_user.user_id,
					users.name,
					(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
					(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count
					'))
				-> groupby('assignment_user.user_id')
				->get();
		return view('report::report.assignmentview',compact('students'));
	}

	public function assess_inst($id){
		$assessment=Assessment::where('institution_id',$id)
				->select('name','id')
				->get();
		//dd($assessnment);
		return $assessment;
	}
	public function report_assessment($inst_id,$assi_id){
		$students=DB::table('assignment_user')
				->leftjoin ('users','assignment_user.user_id','=','users.id')
				->leftjoin ('question_user_answer','assignment_user.user_id','=','question_user_answer.user_id','and','assignment_user.assignment_id','=','question_user_answer.assignment_id')
				->where('assignment_user.assignment_id','=',$assi_id)
				->select( DB::raw('
					assignment_user.user_id,
					users.name,
					(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
					(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count
					'))
				-> groupby('assignment_user.user_id')
				->get();
		//dd($students);
		return view('report::report.assignmentview',compact('students'));
	}
	public function student_inst($id){
		$students=User::join('role_user','role_user.user_id','=','users.id')
				->where('role_user.role_id','=',2)
				->where('users.institution_id','=',$id)
				->select('users.name','users.id')
				->get();
		return $students;
	}
	public function inst_student($inst_id,$student_id){
		$assignments=DB::table('assignment_user')
				->join('assignment','assignment.id','=','assignment_user.assignment_id')
				->join('assessment','assessment.id','=','assignment_user.assessment_id')
				->leftjoin ('question_user_answer','assignment_user.user_id','=','question_user_answer.user_id','and','assignment_user.assignment_id','=','question_user_answer.assignment_id')
				//->join('assessment_question','assessment_question.assessment_id','=','assignment_user.assessment_id')
				->where('assignment_user.user_id','=',$student_id)
				->select(DB::raw('assessment.name as assessment_name, assignment.name as assignment_name,(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
					(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count'))
				->groupby('assignment_name')
				->get();
		//dd($assignments);
		return view('report::report.student_answer_view',compact('assignments'));
	}
	public function inst_question($id)
	{
        $d=Assignment::select('id')->where('institution_id','=',$id)->get();
        $c=array();
        foreach($d as $asssignmentid)
            array_push($c,$asssignmentid->id);
        $questions=DB::table('question_user_answer')
			->leftjoin ('assignment_user','question_user_answer.user_id','=','assignment_user.user_id','and','assignment_user.assignment_id','=','question_user_answer.assignment_id')
            ->select(DB::raw('(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
			(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count'))

				->wherein('question_user_answer.assignment_id',$c)
            ->get();
       //dd($questions);
        return view('report::report.question_answer_view',compact('questions'));
    }

		public function getDownload()
		{
			//PDF file is stored under project/public/download/info.pdf
			$file= public_path(). "/download/info";
			//dd($file);

			$headers = array(
					'Content-Type: application/pdf',
			);

			return Response::download($file, 'filename.pdf', $headers);
		}

}
