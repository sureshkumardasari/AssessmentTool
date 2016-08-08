<?php

namespace App\Modules\Dashboard\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

use App\Modules\dashboard\Models\DashboardWidgets;
use App\modules\resources\models\Assignment;
use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Resources\Models\Question;
use App\User;
use App\Classes\FusionCharts;
use Illuminate\Http\Request;
use stdClass;
use DB;



class MainDashboardController extends BaseController
{
	private $currentUserId;

	 public function __construct()
    {
        $this->currentUserId = Auth::user()->id;
    }

    public function home(Request $request){
    	$user = DB::table('users')
	                     ->where('id',$this->currentUserId)
	                     ->select('name','role_id')
	                     ->first();
        $role_details = DB::table('roles')
	                     ->where('id',$user->role_id)
	                     ->select('name')
	                     ->first();
        if ($this->currentUserId) { 
            $widgets = [];
            $filters = [];
            switch ($role_details->name) {
                case "administrator": 
                return $this->getAdministratorDetails();
                break;
                case "student":
                return $this->getStudentDetails();
                break;
                case "teacher":
                return $this->getTeacherAndAdminDetails();
                    break; 
                case "admin":
                return $this->getTeacherAndAdminDetails();
             	break;
        		}

        } else {
            $errorMessage = 'Sorry, there is no dashboard for this user.';
            $errorDetails = 'Please try again.';

            return view('dashboard::error', compact('errorMessage', 'errorDetails'));
        } 
     }
    public function getAdministratorDetails(){
    	//mahesh
    	$assignments_user = DB::table('assignment')
					->join('assessment', 'assessment.id', '=', 'assignment.assessment_id')
					->select('assignment.id','assignment.name','assessment.name as assessment_name','assignment.created_at as created_at','assignment.startdatetime as startdatetime','assignment.status')
					/*->groupBy('startdatetime')*/
					->orderBy('startdatetime', 'desc')
					->take(5)
					->get();
		$assessment=Assessment::take(5)->get();
		//close mahesh
		//soma sekhar
		$class_students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
				->join('users', 'users.id', '=', 'assignment_user.user_id')
				->where('gradestatus','=','completed')
				->select('users.name', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
				->orderby('assignment_user.gradeddate', 'desc')
				->take(2)
				->get();

		//return view('report::report.dashboard',compact('students'));
			//close soma sekhar
		//eswar
		$list_details=Question::join('question_type','questions.question_type_id','=','question_type.id')
                ->leftjoin('passage','questions.passage_id','=','passage.id')
                ->select('questions.id as qid','questions.title as question_title','passage.title as passage_title','question_type.qst_type_text as question_type')
                ->orderby('qid')
                ->take(5)
                ->get();
        $slist=User::join('roles','users.role_id','=','roles.id')
                ->where('roles.id','=','2')
                ->select('users.name')
                ->take(5)
                ->get();
        $tlist=User::join('roles','users.role_id','=','roles.id')
                ->where('roles.id','=','3')
                ->select('users.name as uname')
                ->take(5)
                ->get();
		//close eswar
        //siva krishna
                $uid= \Auth::user()->institution_id;
		//dd($uid);
		$counts=Array();
		$rec=Array();
		//$assessment_arr=Array();
		$lists=Assignment::lists('assessment_id','id');
		$assignments=array_keys($lists);
		$users=AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->whereIn('assignment_id',$assignments)->GroupBy('assignment_id')->get();
		$completed_users=AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->GroupBy('assignment_id')->where('status','completed')->get();
		foreach($users as $user){
			$All_users[$user->assignment_id]=$user->count;
		}
		foreach($completed_users as $completed_user){
			$complete_users[$completed_user->assignment_id]=$completed_user->count;
		}
		$assignments=Assignment::join('assessment','assignment.assessment_id','=', 'assessment.id')

			->select('assignment.name as assign_name','assignment.id as assign_id','assessment.name as assess_name')
			->orderby('assignment.id','desc')
			->take(2)
			->get();
		//dd($assignments);
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
        //close sive krishna
        //kaladhar
        $uid= \Auth::user()->id;
	  $role=\Auth::user()->role_id;
	  if(getRole()!="administrator" && "teacher") {
 	   $assign_id = AssignmentUser::select('assignment_id')->where('assignment_user.user_id', '=', $uid)->orderby('created_at', 'desc')->get();
	  // dd($assign_id);
	   $subjects=Assessment::join('assignment','assessment.id','=','assignment.assessment_id')
				->join('subject','assessment.subject_id','=','subject.id')
				->where('assignment.id','=',$assign_id)
				->select('subject.name as subject','assignment.name as assignmentname')
				->groupby('subject.id')
				->get();
	   $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
	     ->join('users', 'users.id', '=', 'assignment_user.user_id')
	     ->where('gradestatus','=','completed')
	     ->where('user_assignment_result.assignment_id', '=', $assign_id)
	     ->select('users.name as user', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
	     ->orderby('assignment_user.gradeddate', 'desc')
	     ->get();
	     $score=$students->sum('score');
	     $user=$students->count('user.name');
	     //dd($sun);

	   $student_whole=isset($students[0])?$students[0]:'';
	  }
	  else{
	  	//dd();
	  	//$assign_id = AssignmentUser::select('assignment_id')->where('assignment_user.user_id', '=', $uid)->orderby('created_at', 'desc')->get();

	   $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
	     ->join('users', 'users.id', '=', 'assignment_user.user_id')
	     ->join('assessment','assignment_user.assessment_id','=','assessment.id')
	     ->where('gradestatus','=','completed')
	   //   ->where('user_assignment_result.assignment_id','=',$assign_id);
	     ->select('user_assignment_result.assignment_id','users.name as user', 'user_assignment_result.rawscore as score','assessment.subject_id as sub_id')
	     ->orderby('assignment_user.gradeddate', 'desc')->get();
	     $score=$students->sum('user_assignment_result.score');
	     $user=$students->count('users.name');
	    // $subject=DB::table('subject')->where('id',$students->assessment.subject_id)->lists('id','name');
	       $student_whole=isset($students[0])?$students[0]:'';
	  // dd($subject);
	     // dd($student_whole);
	     	  }
        //close
	    return view('dashboard::dashboard.main_dashboard',compact('class_students','user','assignments_user','assessment','list_details','slist','tlist','assignments','marks','All_users','complete_users','student_whole','score','user'));
	       
    }public function getStudentDetails(){
	$user_id=\Auth::user()->id;
	//dd($user_id);
	$now=date('Y-m-d h:i:s');
	//dd($now);
	$upcoming_assignments=Assignment::where('status',"upcoming")->where('startdatetime','>',$now)->where('enddatetime','!<=',$now)->get();
	$available_assignments=Assignment::where('startdatetime','<=',$now)->where('enddatetime','!<=',$now)->get();
	$query=\DB::table('user_assignment_result as uar')
		//->join('assignment_user as auser','ass.id','=','uar.assignment_id')
		->join('assignment as ass','ass.id','=',\DB::raw('uar.assignment_id && uar.user_id ='.$user_id ))
		//->where('uar.user_id',2)
		//->where('auser.status',"completed")
		->orderby('uar.updated_at','desc')
		->limit(5)
		->select('ass.name','uar.rawscore','uar.percentage');
	$completed_assignments=$query->get();
	$percentage=$query->lists('uar.percentage','uar.name');
	$percentage=array_reverse($percentage,true);
	//dd($percentage);
	//dd($assignments);
	//$percentage=\DB::table('user_assignment_result')->join('assignment','assignment.id','=','user_assignment_result.assignment_id')->where('user_id',2)->lists('percentage','name');
	//$percentage=\DB::table('user_assignment_result')->where('assignment_id',1)->lists('percentage','user_id');
	//dd($percentage);
	//return view('student_dashboard', compact('completed_assignments','upcoming_assignments','percentage'));
	//return view('home');
		//close mallikarjun
		return view('dashboard::dashboard.student_dashboard',compact('user','completed_assignments','upcoming_assignments','percentage'));
    }public function getTeacherAndAdminDetails(){
    	$assignments_user = DB::table('assignment')
					->join('assessment', 'assessment.id', '=', 'assignment.assessment_id')
					->select('assignment.id','assignment.name','assessment.name as assessment_name','assignment.created_at as created_at','assignment.startdatetime as startdatetime','assignment.status')
					/*->groupBy('startdatetime')*/
					->orderBy('startdatetime', 'desc')
					->take(5)
					->get();
		$assessment=Assessment::take(5)->get();
		//close mahesh
		//soma sekhar
	$ins= \Auth::user()->institution_id;
	$class_students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
			->join('users', 'users.id', '=', 'assignment_user.user_id')
			->where('gradestatus', '=', 'completed')
			->where('users.institution_id','=',$ins)
			->select('users.name', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
			->orderby('assignment_user.gradeddate', 'desc')
			->take(2)
			->get();

	//close soma sekhar
		//eswar
		$list_details=Question::join('question_type','questions.question_type_id','=','question_type.id')
                ->leftjoin('passage','questions.passage_id','=','passage.id')
                ->select('questions.id as qid','questions.title as question_title','passage.title as passage_title','question_type.qst_type_text as question_type')
                ->orderby('qid')
                ->take(5)
                ->get();
        $slist=User::join('roles','users.role_id','=','roles.id')
                ->where('roles.id','=','2')
                ->select('users.name')
                ->take(5)
                ->get();
        $tlist=User::join('roles','users.role_id','=','roles.id')
                ->where('roles.id','=','3')
                ->select('users.name as uname')
                ->take(5)
                ->get();
		//close eswar
        //siva krishna
                $uid= \Auth::user()->institution_id;
		//dd($uid);
		$counts=Array();
		$rec=Array();
		//$assessment_arr=Array();
		$lists=Assignment::where('institution_id','=',$uid)->lists('assessment_id','id');
		$assignments=array_keys($lists);
		$users=AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->whereIn('assignment_id',$assignments)->GroupBy('assignment_id')->get();
		$completed_users=AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->GroupBy('assignment_id')->where('status','completed')->get();
		foreach($users as $user){
			$All_users[$user->assignment_id]=$user->count;
		}
		foreach($completed_users as $completed_user){
			$complete_users[$completed_user->assignment_id]=$completed_user->count;
		}
		$assignments=Assignment::join('assessment','assignment.assessment_id','=',DB::raw('assessment.id && assignment.institution_id ='. $uid))->select('assignment.name as assign_name','assignment.id as assign_id','assessment.name as assess_name')
				->orderby('startdatetime','desc')
				->take(2)
				->get();
		//dd($assignments);
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
        //close sive krishna
        //kaladhar
        $uid= \Auth::user()->id;
	  $role=\Auth::user()->role_id;
	  if(getRole()!="administrator" && "teacher") {
 	   $assign_id = AssignmentUser::select('assignment_id')->where('assignment_user.user_id', '=', $uid)->orderby('created_at', 'desc')->get();
	  // dd($assign_id);
	   $subjects=Assessment::join('assignment','assessment.id','=','assignment.assessment_id')
				->join('subject','assessment.subject_id','=','subject.id')
				->where('assignment.id','=',$assign_id)
				->select('subject.name as subject','assignment.name as assignmentname')
				->groupby('subject.id')
				->get();
	   $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
	     ->join('users', 'users.id', '=', 'assignment_user.user_id')
	     ->where('gradestatus','=','completed')
	     ->where('user_assignment_result.assignment_id', '=', $assign_id)
	     ->select('users.name as user', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
	     ->orderby('assignment_user.gradeddate', 'desc')
	     ->get();
	     $score=$students->sum('score');
	     $user=$students->count('user.name');
	     //dd($sun);

	   $student_whole=isset($students[0])?$students[0]:'';
	  }
	  else{
	  	//dd();
	  	//$assign_id = AssignmentUser::select('assignment_id')->where('assignment_user.user_id', '=', $uid)->orderby('created_at', 'desc')->get();

	   $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
	     ->join('users', 'users.id', '=', 'assignment_user.user_id')
	     ->join('assessment','assignment_user.assessment_id','=','assessment.id')
	     ->where('gradestatus','=','completed')
	   //   ->where('user_assignment_result.assignment_id','=',$assign_id);
	     ->select('user_assignment_result.assignment_id','users.name as user', 'user_assignment_result.rawscore as score','assessment.subject_id as sub_id')
	     ->orderby('assignment_user.gradeddate', 'desc')->get();
	     $score=$students->sum('user_assignment_result.score');
	     $user=$students->count('users.name');
	    // $subject=DB::table('subject')->where('id',$students->assessment.subject_id)->lists('id','name');
	       $student_whole=isset($students[0])?$students[0]:'';
	  // dd($subject);
	     //dd($sun);
	     	  }
        //close
	    return view('dashboard::dashboard.teacher_admin_dashboard',compact('class_students','user','assignments_user','assessment','list_details','slist','tlist','assignments','marks','All_users','complete_users','student_whole','score','user'));
	       
    }

	public function student_assignment_reports(){

		$user_id=\Auth::user()->id;
		//dd($user_id);
		$now=date('Y-m-d h:i:s');
		//dd($now);
		$upcoming_assignments=Assignment::where('status',"upcoming")->where('startdatetime','>',$now)->where('enddatetime','!<=',$now)->get();
		$available_assignments=Assignment::where('startdatetime','<=',$now)->where('enddatetime','!<=',$now)->get();
		$query=\DB::table('user_assignment_result as uar')
			//->join('assignment_user as auser','ass.id','=','uar.assignment_id')
			->join('assignment as ass','ass.id','=',\DB::raw('uar.assignment_id && uar.user_id ='.$user_id ))
			//->where('uar.user_id',2)
			//->where('auser.status',"completed")
			->orderby('uar.updated_at','desc')
			//->limit(10)
			->select('ass.name','uar.rawscore','uar.percentage');
		$completed_assignments=$query->get();
		$percentage=$query->lists('uar.percentage','uar.name');
		$percentage=array_reverse($percentage,true);

	return view('dashboard::dashboard.student_report_lists',compact('user','completed_assignments','upcoming_assignments','percentage'));
}

}
