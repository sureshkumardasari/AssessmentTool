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
use Input;

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
		$ins= \Auth::user()->institution_id;
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$assessment=Assignment::join('assessment','assignment.assessment_id','=',DB::raw('assessment.id && assignment.institution_id ='.$ins))->select('assessment.name','assessment.id')->get();
		//dd($assessment);
//		if(getRole()!="administrator"){
//			$assessments=
//		}
		return view('report::report.assessment',compact('inst_arr','assessment'));
	}
	public function assignment()
	{
		$ins= \Auth::user()->institution_id;
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$assignment=Assignment::where('institution_id',$ins)->select('assignment.name','assignment.id')->get();

		return view('report::report.assignment',compact('inst_arr','assignment'));
	}
	public function student()
	{
		$users=Array();
		if(getRole()!="administrator") {
			$ins = \Auth::user()->institution_id;
			$ass = Assignment::where('institution_id', $ins)->select('id')->first();
			$ass_users = AssignmentUser::where('assignment_id', $ass->id)->lists('user_id');
			$users = User::whereNotIn('id', $ass_users)->select('id', 'name')->get();
			//AssignmentUser::where('assignment_id',$ass->id)->select(user_)
		}
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		//dd($users);
		//$students=
		return view('report::report.student',compact('inst_arr','users'));
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

	public function TestHistoryReport(){
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		return view('report::report.testhistoryreport',compact('inst_arr'));

	}

	public function TestHistory($id){
		$counts=Array();
		$rec=Array();
		//$assessment_arr=Array();
		$lists=Assignment::where('institution_id','=',$id)->lists('assessment_id','id');
		$assignments=array_keys($lists);
		$users=AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->whereIn('assignment_id',$assignments)->GroupBy('assignment_id')->get();
		$completed_users=AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->GroupBy('assignment_id')->where('status','completed')->get();
		foreach($users as $user){
			$All_users[$user->assignment_id]=$user->count;
		}
		foreach($completed_users as $completed_user){
			$complete_users[$completed_user->assignment_id]=$completed_user->count;
		}
		$assignments=Assignment::join('assessment','assignment.assessment_id','=',DB::raw('assessment.id && assignment.institution_id ='. $id))->select('assignment.name as assign_name','assignment.id as assign_id','assessment.name as assess_name')->get();
		$assessment_arr=array_unique($lists);
		foreach($assessment_arr as $arr){
			$counts[$arr]=AssessmentQuestion::where('assessment_id',$arr)->count('question_id');
		}
		$records=Assessment::whereIn('id',$assessment_arr)->select('id','guessing_panality','mcsingleanswerpoint','essayanswerpoint')->get();
		//dd($records);
		foreach($records as $record){
			$rec[$record['id']]=Array();
			array_push($rec[$record['id']],$record);
		}
		$a=Array();
		$marks=Array();
		foreach($lists as $key=>$list){
			$correct=db::table('question_user_answer')->where('assessment_id',$list)->where('assignment_id',$key)->where('is_correct','Yes')->count();
			$wrong=db::table('question_user_answer')->where('assessment_id',$list)->where('assignment_id',$key)->where('is_correct','No')->count();
			$lost_marks[$key]=(float)($wrong)*($rec[$list][0]->guessing_panality);
			$mark=((float)$correct*$rec[$list][0]->mcsingleanswerpoint)-(float)$lost_marks[$key];
			//dd($mark);
			$marks[$key]=isset($complete_users[$key])?($mark/($complete_users[$key]*$counts[$list]*$rec[$list][0]->mcsingleanswerpoint))*100:0;
		}
//		dd($rec);
//		dd($counts);
//		dd($complete_users);
//		dd($lost_marks);
//		dd($marks);
		return view('report::report.testhistory',compact('assignments','marks','All_users','complete_users'));

	}

}
