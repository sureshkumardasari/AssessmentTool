<?php namespace App\Modules\Report\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Assessment\Models\QuestionUserAnswer;
use App\Modules\Assessment\Models\UserAssignmentResult;
use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\Question;
use App\Modules\Resources\Models\Subject;
//use App\Modules\Resources\Models\Subject;
use App\Modules\Resources\Models\Lesson;
use App\User;
use Illuminate\Http\Request;
use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Admin\Models\Role;
use Illuminate\Support\Facades\DB;
use Thujohn\Pdf\PdfServiceProvider;

use Response;
use Excel;

class ReportController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function view()
    {
        return view('report::report.report');
    }


    //-----class average and student scores report
    public function class_average_and_student_scores_report()
    {
        $ins = \Auth::user()->institution_id;
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        $assignments = Assignment::where('institution_id', $ins)->lists('name', 'id');
        $assessment = Assignment::join('assessment', 'assignment.assessment_id', '=', DB::raw('assessment.id && assignment.institution_id =' . $ins))->select('assessment.name', 'assessment.id')->get();
        //dd($assessment);
//		if(getRole()!="administrator"){
//			$assessments=
//		}
        return view('report::report.class_average_and_student_scores_report', compact('inst_arr', 'assignments'));
    }

    public function assignment()
    {
        $ins = \Auth::user()->institution_id;
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        $assignment = Assignment::where('institution_id', $ins)->select('assignment.name', 'assignment.id')->get();

        return view('report::report.assignment', compact('inst_arr', 'assignment'));
    }

    public function student()
    {
        $users = Array();
        if (getRole() != "administrator") {
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
        return view('report::report.student', compact('inst_arr', 'users'));
    }

    public function studentAnswerReport()
    {
        $users = Array();
        $assignments=[];
        if (getRole() != "administrator") {
            $ins = \Auth::user()->institution_id;
            $assignments = Assignment::where('institution_id', $ins)->lists('name','id');
            //$ass_users = AssignmentUser::where('assignment_id', $ass->id)->lists('user_id');
           // $users = User::whereNotIn('id', $ass_users)->select('id', 'name')->get();
            //AssignmentUser::where('assignment_id',$ass->id)->select(user_)
        }
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        return view('report::report.student_answer_report', compact('inst_arr', 'assignments'));
    }

    public function answer()
    {
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        $assignment =[];
        if(getRole()!="administrator"){
            $ids=array_keys($inst_arr);
            $assignment = Assignment::where('institution_id', $ids[0])->select('assignment.name', 'assignment.id')->get();
        }
        return view('report::report.answer', compact('inst_arr','assignment'));

    }

    public function report_inst($id)
    {//kaladhar for whole class score report
        $assignment = Assignment::where('institution_id', $id)
            ->select('name', 'id')
            ->get();
        return $assignment;
    }

    public function assignments($id)
    {
        $assignments = Assignment::where('institution_id', $id)
            ->select('name', 'id')
            ->get();
        return $assignments;
    }

    public function report_assignment($inst_id, $assi_id)
    {

        $userids = QuestionUserAnswer::select('user_id')->where('assignment_id', '=', $assi_id)->get();
        $c = array();
        foreach ($userids as $userid)
            array_push($c, $userid->user_id);
        $students = DB::table('assignment_user')
            ->leftjoin('users', 'assignment_user.user_id', '=', 'users.id')
            ->leftjoin('question_user_answer', 'assignment_user.user_id', '=', 'question_user_answer.user_id', 'and', 'assignment_user.assignment_id', '=', 'question_user_answer.assignment_id')
            ->where('assignment_user.assignment_id', '=', $assi_id)
            ->select(DB::raw('
					assignment_user.user_id,
					users.name,
					(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
					(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count
					'))
            ->groupby('assignment_user.user_id')
            ->get();
        return view('report::report.assignmentview', compact('students'));
    }

    //assignments based on institute through ajax......
    public function assess_inst($id)
    {
        $assignments = Assignment::where('institution_id', $id)
            ->select('name', 'id')
            ->get();
        //dd($assessnment);
        return $assignments;
    }


    public function report_questions($inst_id = 0, $assign_id = 0, $sub_id = 0)
    {


        $assessment = Assignment::find($assign_id);
        //dd($assessment->assessment_id);
        $question = [];
        if ($assessment) {
            $question = AssessmentQuestion::where('assessment_id', $assessment->assessment_id)->lists('question_id');
        }
        //dd($question);
        $ques = Question::whereIn('id', $question)->lists('title', 'id');

        $questions = Question::whereIn('id', $question);
        if ($sub_id != 0) {
            $questions->where('subject_id', '=', $sub_id);
        }
        $questions = $questions->lists('id');
        $user_count = QuestionUserAnswer::where('assignment_id', $assign_id)->selectRaw('question_id,count(user_id) as count')->groupBy('question_id')->lists('count', 'question_id');
        $user_answered_correct_count = QuestionUserAnswer::whereIn('question_id', $questions)->where('assignment_id', $assign_id)->where('is_correct', 'Yes')->selectRaw('question_id,count(user_id) as count')->groupBy('question_id')->lists('count', 'question_id');
        //dd($ques);
        //join('questions','assessment_question.assessment_id','=',$assessment->id)
        //dd($question);

        //dd($user_count);
        //-------------------------------

        //dd($questionnnnn);

        //---------------------------------
//dd($questions);
//		foreach($questions as $questionn)
//			$options[]=QuestionUserAnswer::select('answer_option','question_id','user_id')
//				->where('assignment_id',$assign_id)
//				->where('question_id',$questionn->id)->groupby('user_id')
//					->get();
//
//		//dd($options);
//		$userids=DB::table('question_user_answer')
//		->join('question_answers','question_answers.question_id','=','question_user_answer.question_id')
//		->leftjoin('questions','questions.id','=','question_user_answer.question_id')
//			->whereIn('questions.id',$question)
//		//->where('questions.institute_id', $inst_id)
//		->where('questions.subject_id', $sub_id)
//		//->where('question_user_answer.answer_option','=','A')
//		->where('question_answers.is_correct', 'like', 'Yes')
//		->select(DB::raw('questions.id,question_answers.order_id,(select count(question_answers.order_id))as count'))
//		->groupby('question_user_answer.question_id')
//		->get();
//		//dd($userids);
//
//
//
//		//if ($inst_id>0&&$sub_id>0) {
//			$questions = Question::join('question_answers', 'questions.id', '=', 'question_answers.question_id')
//					//->where('questions.institute_id', $inst_id)
//					->whereIn('questions.id',$question)
//					->where('questions.subject_id', $sub_id)
//					->where('question_answers.is_correct', 'like', 'Yes')
//					->select('questions.id', 'order_id')
//					->get();
        //	dd($questions);
        //}
//		elseif($inst_id>0){
//			$questions = Question::join('question_answers', 'questions.id', '=', 'question_answers.question_id')
//					->where('questions.institute_id', $inst_id)
//				->whereIn('questions.id',$question)
//					->where('question_answers.is_correct', 'like', 'yes')
//					->select('questions.id', 'order_id')
//					->get();
//		}
//		else{
//			$questions = Question::join('question_answers', 'questions.id', '=', 'question_answers.question_id')
//					->where('question_answers.is_correct', 'like', 'yes')
//				->whereIn('questions.id',$question)
//					->select('questions.id', 'order_id')
//					->get();
//		}
        //dd($questions);
        //return $questions;


        return view('report::report.question_answer_view', compact('ques', 'user_answered_correct_count', 'user_count'));
    }


    //class average and student scores report through ajax call......
    public function class_average_and_student_scores($inst_id = 0, $assign_id = 0)
    {
        $assignment = Assignment::find($assign_id);
        if ($assignment) {
            $marks = Assessment::find($assignment->assessment_id);
            //dd($marks);
            $question_ids = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', DB::Raw('questions.id and assessment_question.assessment_id =' . $assignment->assessment_id))
                //->where(' questions.question_type_id = 3 AND questions.id = assessment_question.question_id')
                ->select('questions.question_type_id as qstype', 'questions.id')->lists('qstype', 'id');//->groupBy('questions.question_type_id')
            //->get();//)->keyBy('question_type_id');
            $type = [];
            foreach ($question_ids as $key => $id) {
                if (!isset($type[$id])) {
                    $type[$id] = [];
                }
                array_push($type[$id], $key);
            }
            $multi_total_count = 0;
            $multi_answers_count = 0;
            $essay_total_count = 0;
            if (isset($type[2])) {
                $multi_total_count = count($type[2]);
            } else {
                $type[2] = [0];
            }
            if (isset($type[3])) {
                $essay_total_count = count($type[3]);
            } else {
                $type[3] = [0];
            }
            $total_marks = ($multi_total_count * $marks->mcsingleanswerpoint) + ($essay_total_count * $marks->essayanswerpoint);
            //dd($total_marks);
            /*
        //dd($type);
    //dd(array_values($question_type_count));
        $question_type_count = collect(AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', DB::Raw('questions.id and assessment_question.assessment_id =' . $assignment->assessment_id))
            //->where(' questions.question_type_id = 3 AND questions.id = assessment_question.question_id')
            ->selectRaw('questions.question_type_id ,count(questions.question_type_id) as count')->groupBy('questions.question_type_id')->get())->keyBy('question_type_id');
        //dd($question_type_count);
        $students = collect(Assignment::join('assignment_user', 'assignment.id', '=', DB::Raw('assignment_user.assignment_id && assignment.id =' . $assign_id))
            ->join('users', 'assignment_user.user_id', '=', 'users.id')
            ->join('question_user_answer', 'assignment_user.user_id', '=', 'question_user_answer.user_id')
            ->join('assessment_question', 'assignment.assessment_id', '=', 'assessment_question.assessment_id')
            ->join('questions', 'assessment_question.question_id', '=', 'questions.id')
            //->select('assignment.name','assignment.assessment_id','users.id as user_id','users.name as user_name')
            ->selectRaw('
                        assignment_user.user_id,
                        users.name,
                        (select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
                        (select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\' and  question_user_answer.question_id IN(' . implode(',', $type[2]) . ')) as multi_answers_count,
                        (select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\' and  question_user_answer.question_id IN(' . implode(',', $type[3]) . ')) as essay_answers_count
                        ')
            ->get())->keyBy('user_id');
        //dd($students);
        //Assignment::
    //		$students=DB::table('assignment_user')
    //				->leftjoin ('users','assignment_user.user_id','=','users.id')
    //				->leftjoin ('question_user_answer','assignment_user.user_id','=','question_user_answer.user_id','and','assignment_user.assignment_id','=','question_user_answer.assignment_id')
    //				->where('assignment_user.assignment_id','=',$assign_id)
    //				->select( DB::raw('
    //					assignment_user.user_id,
    //					users.name,
    //					(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
    //					(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count
    //					'))
    //				-> groupby('assignment_user.user_id')
    //				->get();
        //dd($students);
    }
            else{
                $students=[];
            }*/

            /*select `users`.`name`, `user_assignment_result`.`rawscore` as `score`, `user_assignment_result`.`percentage` from `assignment_user` join `users` on `users`.`id` = `assignment_user`.`user_id` left join `user_assignment_result` on `user_assignment_result`.`assignment_id` = `assignment_user`.`assignment_id` and `user_assignment_result`.`user_id` = `assignment_user`.`user_id` where `assignment_user`.`assignment_id` = '2'*/

            $assignment = Assignment::find($assign_id);
            if ($assignment) {
                $students = AssignmentUser::join('users', 'users.id', '=', 'assignment_user.user_id')
                    ->leftjoin('user_assignment_result', function($join){
			                $join->on('user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id');
			                $join->on('user_assignment_result.user_id', '=', 'assignment_user.user_id');
			            })
                    ->where('assignment_user.assignment_id', '=', $assign_id)
                    ->select('users.name', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
                    ->groupby('users.name')
                    ->get();
            } else {
                $students = [];
            }
           //dd($students);
            return view('report::report.assignmentview', compact('students', 'multi_total_count', 'multi_answers_count', 'essay_total_count', 'type', 'marks', 'total_marks'));
        }
    }

    public function student_inst($id)
    {
        $students = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->where('role_user.role_id', '=', 2)
            ->where('users.institution_id', '=', $id)
            ->select('users.name', 'users.id')
            ->get();
        return $students;
    }

    public function assignmtInst($id)
    {
        $assignments = DB::table('assignment_user')
            ->join('assignment', 'assignment.id', '=', 'assignment_user.assignment_id')
            ->where('assignment.institution_id', '=', $id)
            ->select('assignment.name', 'assignment.id')
            ->get();
        return $assignments;
    }

    public function stuentsAssignmtInst($inst_id, $assign_id)
    {
        $student_list = DB::table('assignment_user')
            ->join('assignment', 'assignment.id', '=', 'assignment_user.assignment_id')
            ->leftjoin('users', 'assignment_user.user_id', '=', 'users.id')
            ->where('assignment.institution_id', '=', $inst_id)
            ->where('assignment_user.assignment_id', '=', $assign_id)
            ->select('users.name', 'users.id')
            ->get();
        return $student_list;
    }

    public function inst_student($inst_id, $student_id)
    {
        $assignments = DB::table('assignment_user')
            ->join('assignment', 'assignment.id', '=', 'assignment_user.assignment_id')
            ->join('assessment', 'assessment.id', '=', 'assignment_user.assessment_id')
            ->leftjoin('question_user_answer', 'assignment_user.user_id', '=', 'question_user_answer.user_id', 'and', 'assignment_user.assignment_id', '=', 'question_user_answer.assignment_id')
            //->join('assessment_question','assessment_question.assessment_id','=','assignment_user.assessment_id')
            ->where('assignment_user.user_id', '=', $student_id)
            ->select(DB::raw('assessment.name as assessment_name, assignment.name as assignment_name,(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
					(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count'))
            ->groupby('assignment_name')
            ->get();
        //dd($assignments);
        return view('report::report.student_answer_view', compact('assignments'));
    }

    public function studentAnsList($inst_id, $assign_id, $student_id)
    {
        $assignments = DB::table('assignment_user')
            ->join('assignment', 'assignment.id', '=', 'assignment_user.assignment_id')
            ->join('assessment', 'assessment.id', '=', 'assignment_user.assessment_id')
            ->leftjoin('question_user_answer', 'assignment_user.user_id', '=', 'question_user_answer.user_id', 'and', 'assignment_user.assignment_id', '=', 'question_user_answer.assignment_id')
            ->leftjoin('questions', 'questions.id', '=', 'question_user_answer.question_id')
            ->leftjoin('question_answers', 'question_answers.question_id', '=', 'question_user_answer.question_id')
            ->where('question_user_answer.assignment_id', '=', $assign_id)
            ->where('assignment_user.user_id', '=', $student_id)
            ->where('question_answers.is_correct', '=', 'YES')
            ->select('questions.title as question_title', 'question_user_answer.answer_option as your_answer', 'question_answers.order_id as correct_answer', 'question_user_answer.is_correct as is_correct', 'assignment.id as id', 'question_user_answer.id as qid')
            ->groupby('questions.id')
            ->get();
        //dd($assignments);
        return view('report::report.student_answer_report_list', compact('assignments'));
    }

    public function SAR_pdf($inst_id, $assign_id, $student_id)
    { //dd($student_id);
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();
        $assign = Assignment::where('id', '=', $assign_id)->select('name')->get();
        $user = User::where('id', '=', $student_id)->select('name')->get();

        $assignments = DB::table('assignment_user')
            ->join('assignment', 'assignment.id', '=', 'assignment_user.assignment_id')
            ->join('assessment', 'assessment.id', '=', 'assignment_user.assessment_id')
            ->leftjoin('question_user_answer', 'assignment_user.user_id', '=', 'question_user_answer.user_id', 'and', 'assignment_user.assignment_id', '=', 'question_user_answer.assignment_id')
            ->leftjoin('questions', 'questions.id', '=', 'question_user_answer.question_id')
            ->leftjoin('question_answers', 'question_answers.question_id', '=', 'question_user_answer.question_id')
            ->where('question_user_answer.assignment_id', '=', $assign_id)
            ->where('assignment_user.user_id', '=', $student_id)
            ->where('question_answers.is_correct', '=', 'YES')
            ->select('questions.title as question_title', 'question_user_answer.answer_option as your_answer', 'question_answers.order_id as correct_answer', 'question_user_answer.is_correct as is_correct', 'assignment.id as id', 'question_user_answer.id as qid')
            ->groupby('questions.id')
            ->get();

        $htmlForPdf = view('report::report.SAR_pdf', compact('assignments', 'inst', 'assign','user'))->render();
        $fileName = 'sar_pdf';
        /*$fileFullUrl = createPdfForReport($fileName, $htmlForPdf);
        $name=explode('/',$fileFullUrl);
        $name=$name[5];
       // return response()->Download("/var/www/AssessmentTool/public/data/reports/".$name);
        return response()->Download(public_path()."/data/reports/".$name);*/
        $name = createPdfForReport($fileName, $htmlForPdf,'','only-name');
        if($name == url('data/error.pdf'))
        {
            return response()->download(public_path()."/data/error.pdf");    
        }
        else
        {
            return response()->download(public_path()."/data/reports/".$name);
        }
    }

    public function SAR_xls($inst_id, $assign_id, $student_id)
    {
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();
        $assign = Assignment::where('id', '=', $assign_id)->select('name')->get();
        $user = User::where('id', '=', $student_id)->select('name')->get();

        $assignments = DB::table('assignment_user')
            ->join('assignment', 'assignment.id', '=', 'assignment_user.assignment_id')
            ->join('assessment', 'assessment.id', '=', 'assignment_user.assessment_id')
            ->leftjoin('question_user_answer', 'assignment_user.user_id', '=', 'question_user_answer.user_id', 'and', 'assignment_user.assignment_id', '=', 'question_user_answer.assignment_id')
            ->leftjoin('questions', 'questions.id', '=', 'question_user_answer.question_id')
            ->leftjoin('question_answers', 'question_answers.question_id', '=', 'question_user_answer.question_id')
            ->where('question_user_answer.assignment_id', '=', $assign_id)
            ->where('assignment_user.user_id', '=', $student_id)
            ->where('question_answers.is_correct', '=', 'YES')
            ->select('questions.title as question_title', 'question_user_answer.answer_option as your_answer', 'question_answers.order_id as correct_answer', 'question_user_answer.is_correct as is_correct', 'assignment.id as id', 'question_user_answer.id as qid')
            ->groupby('questions.id')
            ->get();

        return Excel::create('Assessment report', function ($excel) use ($assignments, $inst, $assign, $user) {
            $excel->sheet('mySheet', function ($sheet) use ($assignments, $inst, $assign, $user) {
                //$sheet->loadView($students);
                $sheet->loadView('report::report.SAR_pdf', array("assignments" => $assignments, "inst" => $inst, "assign" => $assign, "user" => $user));
                //$sheet->fromArray($students);
            });
        })->download("xls");
    }

    public function inst_question($id)
    {
        $d = Assignment::select('id')->where('institution_id', '=', $id)->get();
        $c = array();
        foreach ($d as $asssignmentid)
            array_push($c, $asssignmentid->id);
        $questions = DB::table('question_user_answer')
            ->leftjoin('assignment_user', 'question_user_answer.user_id', '=', 'assignment_user.user_id', 'and', 'assignment_user.assignment_id', '=', 'question_user_answer.assignment_id')
            ->select(DB::raw('(select count(*) from assessment_question aq where aq.assessment_id = assignment_user.assessment_id) as total_count,
			(select count(*) from question_user_answer qua where qua.user_id = question_user_answer.user_id and qua.assignment_id=question_user_answer.assignment_id and qua.is_correct=\'Yes\') as answers_count'))
            ->wherein('question_user_answer.assignment_id', $c)
            ->get();
        //dd($questions);
        return view('report::report.question_answer_view', compact('questions'));
    }

    public function getDownload($filename)
    {
        $file= $filename;
        $headers = array(
            'Content-Type: application/pdf',
           // 'Content-Disposition:attachment; filename="cv.pdf"',
//            'Content-Transfer-Encoding:binary',
//            'Content-Length:'.filesize($file)
        );

        return response()->download($file,"abc.pdf",$headers);
    }

// test history class average report home page...
    public function TestHistoryReport()
    {
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        return view('report::report.testhistoryreport', compact('inst_arr'));

    }

// Test history class average report....generator
    public function TestHistory($id)
    {
        $counts = Array();
        $rec = Array();
        //$assessment_arr=Array();
        $lists = Assignment::where('institution_id', '=', $id)->lists('assessment_id', 'id');
        $assignments = array_keys($lists);
        $users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->whereIn('assignment_id', $assignments)->GroupBy('assignment_id')->get();
        $completed_users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->GroupBy('assignment_id')->where('status', 'completed')->get();
        foreach ($users as $user) {
            $All_users[$user->assignment_id] = $user->count;
        }
        foreach ($completed_users as $completed_user) {
            $complete_users[$completed_user->assignment_id] = $completed_user->count;
        }
        $assignments = Assignment::join('assessment', 'assignment.assessment_id', '=', DB::raw('assessment.id && assignment.institution_id =' . $id))->select('assignment.name as assign_name', 'assignment.id as assign_id', 'assessment.name as assess_name')->get();
        $assessment_arr = array_unique($lists);
        foreach ($assessment_arr as $arr) {
            $counts[$arr] = AssessmentQuestion::where('assessment_id', $arr)->count('question_id');
        }
        $records = Assessment::whereIn('id', $assessment_arr)->select('id', 'guessing_panality', 'mcsingleanswerpoint', 'essayanswerpoint')->get();
        //dd($records);
        foreach ($records as $record) {
            $rec[$record['id']] = Array();
            array_push($rec[$record['id']], $record);
        }
        $a = Array();
        $marks = Array();
        foreach ($lists as $key => $list) {
            $correct = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'Yes')->count();
            $wrong = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'No')->count();
            //dd($wrong);
            $lost_marks[$key] = (float)($wrong) * ($rec[$list][0]->guessing_panality);

            $mark = ((float)$correct * $rec[$list][0]->mcsingleanswerpoint) - (float)$lost_marks[$key];
            //dd($mark);
            $marks[$key] = isset($complete_users[$key]) ? ($mark / ($complete_users[$key] * $counts[$list] * $rec[$list][0]->mcsingleanswerpoint)) * 100 : 0;
        }
//		dd($rec);
//		dd($counts);
//		dd($complete_users);
		//dd($lost_marks);
		//dd($marks);
        return view('report::report.testhistory', compact('assignments', 'marks', 'All_users', 'complete_users'));

    }

    //Generating pdf...
    public function exportPDF($inst_id = 0, $assi_id = 0)
    {
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();
        //dd($inst);
        $assi = Assignment::where('id', '=', $assi_id)->select('name')->get();
        //dd($assi);
        $assignment = Assignment::find($assi_id);
        if ($assignment) {
            $marks = Assessment::find($assignment->assessment_id);
            //dd($marks);
            $question_ids = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', DB::Raw('questions.id and assessment_question.assessment_id =' . $assignment->assessment_id))
                //->where(' questions.question_type_id = 3 AND questions.id = assessment_question.question_id')
                ->select('questions.question_type_id as qstype', 'questions.id')->lists('qstype', 'id');//->groupBy('questions.question_type_id')
            //->get();//)->keyBy('question_type_id');
            $type = [];
            foreach ($question_ids as $key => $id) {
                if (!isset($type[$id])) {
                    $type[$id] = [];
                }
                array_push($type[$id], $key);
            }
            $multi_total_count = 0;
            $multi_answers_count = 0;
            $essay_total_count = 0;
            if (isset($type[2])) {
                $multi_total_count = count($type[2]);
            } else {
                $type[2] = [0];
            }
            if (isset($type[3])) {
                $essay_total_count = count($type[3]);
            } else {
                $type[3] = [0];
            }
            $total_marks = ($multi_total_count * $marks->mcsingleanswerpoint) + ($essay_total_count * $marks->essayanswerpoint);

            $assignment = Assignment::find($assi_id);
            if ($assignment) {
                $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
                    ->join('users', 'users.id', '=', 'assignment_user.user_id')
                    ->where('user_assignment_result.assignment_id', '=', $assi_id)
                    ->select('users.name', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
                    ->groupby('users.name')
                    ->get();
            } else {
                $students = [];
            }
            $htmlForPdf = view('report::report.pdf', compact('inst', 'students', 'assi'))->render();
            // dd($htmlForPdf);
            $fileName = 'pdf';
            /*$fileFullUrl = createPdfForReport($fileName, $htmlForPdf);
            //dd($fileFullUrl);
            $name=explode('/',$fileFullUrl);
            $name=$name[5];
            // return url($fileFullUrl);
            // return response()->Download($fileFullUrl);
            //  return response()->Download("/var/www/AssessmentTool/public/data/reports/".$name);
            return response()->Download(public_path()."/data/reports/".$name);*/
            $name = createPdfForReport($fileName, $htmlForPdf,'','only-name');
            if($name == url('data/error.pdf'))
            {
                return response()->download(public_path()."/data/error.pdf");    
            }
            else
            {
                return response()->download(public_path()."/data/reports/".$name);
            }
        }

        //return Redirect::route('class_average_and_student_scores_report');
    }

    public function exportXLS($inst_id = 0, $assi_id = 0)
    {
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();

        $assi = Assignment::where('id', '=', $assi_id)->select('name')->get();
        $assignment = Assignment::find($assi_id);
        if ($assignment) {
            $marks = Assessment::find($assignment->assessment_id);
            //dd($marks);
            $question_ids = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', DB::Raw('questions.id and assessment_question.assessment_id =' . $assignment->assessment_id))
                //->where(' questions.question_type_id = 3 AND questions.id = assessment_question.question_id')
                ->select('questions.question_type_id as qstype', 'questions.id')->lists('qstype', 'id');//->groupBy('questions.question_type_id')
            //->get();//)->keyBy('question_type_id');
            $type = [];
            foreach ($question_ids as $key => $id) {
                if (!isset($type[$id])) {
                    $type[$id] = [];
                }
                array_push($type[$id], $key);
            }
            $multi_total_count = 0;
            $multi_answers_count = 0;
            $essay_total_count = 0;
            if (isset($type[2])) {
                $multi_total_count = count($type[2]);
            } else {
                $type[2] = [0];
            }
            if (isset($type[3])) {
                $essay_total_count = count($type[3]);
            } else {
                $type[3] = [0];
            }
            $total_marks = ($multi_total_count * $marks->mcsingleanswerpoint) + ($essay_total_count * $marks->essayanswerpoint);

            $assignment = Assignment::find($assi_id);
            if ($assignment) {
                $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
                    ->join('users', 'users.id', '=', 'assignment_user.user_id')
                    ->where('user_assignment_result.assignment_id', '=', $assi_id)
                    ->select('users.name', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
                    ->groupby('users.name')
                    ->get();
            } else {
                $students = [];
            }
            return Excel::create('Assessment report', function ($excel) use ($students, $inst, $assi) {
                $excel->sheet('mySheet', function ($sheet) use ($students, $inst, $assi) {
                    //$sheet->loadView($students);
                    $sheet->loadView('report::report.pdf', array("students" => $students, "inst" => $inst, "assi" => $assi));
                    //$sheet->fromArray($students);
                });
            })->download("xls");
        }
    }

//getting subjects list based on assignment...
    public function subjects_list($inst_id, $assign_id)
    {
        $assignment = Assignment::find($assign_id);
        $assessment = Assessment::find($assignment->assessment_id);
        $sub_list = explode(',', $assessment->subject_id);
        $subjects = Subject::whereIn('id', $sub_list)->select('name', 'id')->get();

        //dd($subjects);
        return $subjects;
        /*$assessment=Assignment::find($assign_id);
            $subjects=Assessment::join('subject as sub','assessment.subject_id','=',DB::raw('sub.id && assessment.id ='.$assessment->id))->select('sub.name','sub.id')
                ->lists('name','id');*/
        //dd($subjects);
        //return $subjects;
    }

    public function wholeclassscorereport()
    {
        $ins = \Auth::user()->institution_id;
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        $assignment = Assignment::where('institution_id', $ins)->select('assignment.name', 'assignment.id')->get();
        $subjectObj = new Subject();
        $subjects = $subjectObj->getSubject();  //dd($subjects);

        return view('report::report.wholeclassscorereport', compact('inst_arr', 'subjects', 'assignment'));
    }

    public function subject_change($assi_id)
    {
        $assignment = Assignment::find($assi_id);
        $assessment = Assessment::find($assignment->assessment_id);
        $sub_list = explode(',', $assessment->subject_id);
        //dd($sub_list);
        $subjects = Subject::whereIn('id', $sub_list)->select('name', 'id')->get();
        //dd($subjects);
        return $subjects;
    }

    public function report_wholeclass($inst_id = 0, $assi_id = 0, $sub_id = 0, $less_id = 0)
    {
        if ($assi_id == 0 || $assi_id == "null" || $sub_id == "null" || $sub_id == 0) {
            return "";
        }
        $assignment = Assignment::find($assi_id);
        $assessment = Assessment::find($assignment->assessment_id);

        if ($sub_id != "null" || $sub_id != 0) {
            $subjects = explode(",", $sub_id);
        } else {
            $subjects = [$sub_id];
        }


        //  dd($lessons);
        if (count($subjects) > 1) {
            $case = 1;
        } else {
            $case = 2;
            if ($less_id != "null" || $less_id != 0) {
                $lessons = explode(",", $less_id);
                $lessons = Lesson::whereIn('id', $lessons)->lists('name', 'id');
            } else {
                $lessons = Lesson::whereIn('subject_id', $subjects)->lists('name', 'id');
            }

        }

        $subjects = Subject::whereIn('id', $subjects)->lists('name', 'id');
        $multi_answer_type_id = DB::table('question_type')->where('qst_type_text', "Multiple Choice - Multi Answer")->first()->id;
        $single_answer_type_id = DB::table('question_type')->where('qst_type_text', "Multiple Choice - Single Answer")->first()->id;
        $essay_answer_type_id = DB::table('question_type')->where('qst_type_text', "Essay")->first()->id;
        $subject_questions = [];

        //dd($subject_questions);
        $students = AssignmentUser::join('users', 'assignment_user.user_id', '=', DB::raw('users.id and assignment_user.assignment_id =' . $assi_id))
            ->select('users.name', 'users.id')
            ->lists('name', 'id');
        // dd($students);
        switch ($case) {
            case  1 : {
                $type = "subjects";
                foreach ($subjects as $key => $sub) {
                    $subject_questions[$key]['multi_or_single_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $key)
                        ->whereIn('questions.question_type_id', [$multi_answer_type_id, $single_answer_type_id])
                        ->select('questions.id')
                        ->lists('id');
                    $subject_questions[$key]['essay_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $key)
                        ->where('questions.question_type_id', $essay_answer_type_id)
                        ->select('questions.id')
                        ->lists('id');
                }
                $subject_score = [];
                $penality = [];
                foreach ($students as $stud_id => $student) {
                    foreach ($subjects as $key => $name) {
                        $total_questions = array_merge($subject_questions[$key]['multi_or_single_answer_type'], $subject_questions[$key]['essay_answer_type']);
                        $subject_score[$stud_id][$key] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $total_questions)->selectRaw(' sum(points) as sum, count(*) as total')->get();
                        $penality[$stud_id][$key]['multi_single'] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $subject_questions[$key]['multi_or_single_answer_type'])
                            ->where('is_correct', "No")
                            ->count();
                        // $penality[$stud_id][$key]['essay']=QuestionUserAnswer::where('assignment_id',$assi_id)->where('user_id',$stud_id)
                        //     ->whereIn('question_id',$subject_questions[$key]['essay_answer_type'])
                        //     ->where('is_correct',"Open")
                        //     ->count();
                        // dd($penality);
//
                    }
                }

                //  dd($subject_score);
                return view('report::report.wholescoreview_duplicate', compact('type', 'subjects', 'assignment', 'subject_score', 'students', 'penality'));
            }
            case 2 : {
                $type = "lessons";
                foreach ($lessons as $key => $lesson) {
                    $lesson_questions[$key]['multi_or_single_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $sub_id)
                        ->where('questions.lesson_id', $key)
                        ->whereIn('questions.question_type_id', [$multi_answer_type_id, $single_answer_type_id])
                        ->select('questions.id')
                        ->lists('id');
                    $lesson_questions[$key]['essay_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $sub_id)
                        ->where('questions.lesson_id', $key)
                        ->where('questions.question_type_id', $essay_answer_type_id)
                        ->select('questions.id')
                        ->lists('id');
                }
                // dd($lessons);
                $lesson_score = [];
                $penality = [];
                foreach ($students as $stud_id => $student) {
                    foreach ($lessons as $key => $name) {
                        $total_questions = array_merge($lesson_questions[$key]['multi_or_single_answer_type'], $lesson_questions[$key]['essay_answer_type']);
                        $lesson_score[$stud_id][$key] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $total_questions)->selectRaw('sum(points) as sum, count(*) as total')->get();
                        $penality[$stud_id][$key]['multi_single'] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $lesson_questions[$key]['multi_or_single_answer_type'])
                            ->where('is_correct', "No")
                            ->count();
                        // $penality[$stud_id][$key]['essay']=QuestionUserAnswer::where('assignment_id',$assi_id)->where('user_id',$stud_id)
                        //     ->whereIn('question_id',$lesson_questions[$key]['essay_answer_type'])
                        //     ->where('is_correct',"Open")
                        //     ->count();
//
                    }
                }
                 //dd($assessment->guessing_panality);
                return view('report::report.wholescoreview_duplicate', compact('type', 'lessons', 'assignment','assessment', 'students', 'lesson_score', 'subjects', 'sub_id', 'penality'));
            }
        }
        //  dd($lessons);
    }

    public function assignmentdash($parent_id = 0)
    {
        $assignments = DB::table('assignment')
            ->join('assessment', 'assessment.id', '=', 'assignment.assessment_id')
            ->select('assignment.id', 'assignment.name', 'assessment.name as assessment_name', 'assignment.created_at as created_at', 'assignment.startdatetime as startdatetime', 'assignment.status')
            /*->groupBy('startdatetime')*/
            ->orderBy('startdatetime', 'desc')
            ->take(5)
            ->get();
        //dd($assignments);
        $assessment = Assessment::take(5)->get();
        return view('report::report.report123', compact('assignments', 'assessment'));
    }

    public function leastscore()
    {
        $ins = \Auth::user()->institution_id;
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        $insts = array_keys($inst_arr);
       //dd($ins);
        $assignment = Assignment::where('gradestatus', '=', 'completed')->where('institution_id','=',$ins)->take(3)->orderby('id', 'desc')->lists('id');
        //dd($assignment);
        $assignmentname = Assignment::whereIn('id', $assignment)->orderby('id', 'desc')->lists('name');
        $length = 0;
        if(!empty($assignment)){
            // dd("empty");
            $length=count($assignment);
         //dd($length);
            $report_data = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
                ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
                ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
                ->where('assignment.id', '=', isset($assignment[0])?$assignment[0]:0)
                ->orderBy('assignment.id')
                ->orderby('user_assignment_result.rawscore', 'asc')
                ->take(10)
                ->get();
                //dd($report_data);
            if($length >1){
                $report_data1 = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
                    ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
                    ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
                    ->where('assignment.id', '=', isset($assignment[1])?$assignment[1]:0)
                    ->orderBy('assignment.id')
                    ->orderby('user_assignment_result.rawscore', 'asc')
                    ->take(10)
                    ->get();
            }
            if($length >2){
                $report_data2 = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
                    ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
                    ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name','users.id as uid')
                    ->where('assignment.id', '=', isset($assignment[2])?$assignment[2]:0)
                    ->orderBy('assignment.id')
                    ->orderby('user_assignment_result.rawscore', 'asc')
                    ->take(10)
                    ->get();
            }
        }
//dd($report_data2);
        return view('report::report.least_score', compact('report_data', 'report_data1', 'report_data2', 'assignmentname','length'));
    }

    public function dashboard()
    {
        $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
            ->join('users', 'users.id', '=', 'assignment_user.user_id')
            ->where('gradestatus', '=', 'completed')
            ->select('users.name', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
            ->orderby('assignment_user.gradeddate', 'desc')
            ->take(1)
            ->get();

        return view('report::report.dashboard', compact('students'));
    }

    public function sqt()
    {

        $list = Question::join('question_type', 'questions.question_type_id', '=', 'question_type.id')
            ->leftjoin('passage', 'questions.passage_id', '=', 'passage.id')
            ->select('questions.id as qid', 'questions.title as question_title', 'passage.title as passage_title', 'question_type.qst_type_text as question_type')
            ->orderby('qid')
            ->take(5)
            ->get();
        $slist = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.id', '=', '2')
            ->select('users.name')
            ->take(5)
            ->get();
        $tlist = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.id', '=', '3')
            ->select('users.name as uname')
            ->take(5)
            ->get();


        //dd($tlist);
        return view('report::report.studentquestionteacher', compact('list', 'slist', 'tlist'));
//        ->nest('a','report::report.teacher_dashbord',compact('tlist'));
    }

    public function dashboard1()
    {
        $uid = \Auth::user()->institution_id;
        //dd($uid);
        $counts = Array();
        $rec = Array();
        //$assessment_arr=Array();
        $lists = Assignment::where('institution_id', '=', $uid)->lists('assessment_id', 'id');
        $assignments = array_keys($lists);
        $users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->whereIn('assignment_id', $assignments)->GroupBy('assignment_id')->get();
        $completed_users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->GroupBy('assignment_id')->where('status', 'completed')->get();
        foreach ($users as $user) {
            $All_users[$user->assignment_id] = $user->count;
        }
        foreach ($completed_users as $completed_user) {
            $complete_users[$completed_user->assignment_id] = $completed_user->count;
        }
        $assignments = Assignment::join('assessment', 'assignment.assessment_id', '=', DB::raw('assessment.id && assignment.institution_id =' . $uid))->select('assignment.name as assign_name', 'assignment.id as assign_id', 'assessment.name as assess_name')
            ->orderby('startdatetime', 'desc')
            ->take(2)
            ->get();
        //dd($assignments);
        $assessment_arr = array_unique($lists);
        foreach ($assessment_arr as $arr) {
            $counts[$arr] = AssessmentQuestion::where('assessment_id', $arr)->count('question_id');
        }
        $records = Assessment::whereIn('id', $assessment_arr)->select('id', 'guessing_panality', 'mcsingleanswerpoint', 'essayanswerpoint')->get();
        //dd($records);
        foreach ($records as $record) {
            $rec[$record['id']] = Array();
            array_push($rec[$record['id']], $record);
        }
        $a = Array();
        $marks = Array();
        foreach ($lists as $key => $list) {
            $correct = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'Yes')->count();
            $wrong = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'No')->count();
            $lost_marks[$key] = (float)($wrong) * ($rec[$list][0]->guessing_panality);
            $mark = ((float)$correct * $rec[$list][0]->mcsingleanswerpoint) - (float)$lost_marks[$key];
            //dd($mark);
            $marks[$key] = isset($complete_users[$key]) ? ($mark / ($complete_users[$key] * $counts[$list] * $rec[$list][0]->mcsingleanswerpoint)) * 100 : 0;
        }
//		dd($rec);
//		dd($counts);
//		dd($complete_users);
//		dd($lost_marks);
//		dd($marks);
        return view('report::report.testhistorytile', compact('assignments', 'marks', 'All_users', 'complete_users'));
    }

    public function dashboardwholeclass()
    {
        //dd();
        $uid = \Auth::user()->id;
        $role = \Auth::user()->role_id;
        if (getRole() != "administrator" && "teacher") {
            dd();
            $assign_id = AssignmentUser::select('assignment_id')->where('assignment_user.user_id', '=', $uid)->orderby('created_at', 'desc')->get();
            // dd($assign_id);
            $subjects = Assessment::join('assignment', 'assessment.id', '=', 'assignment.assessment_id')
                ->join('subject', 'assessment.subject_id', '=', 'subject.id')
                ->where('assignment.id', '=', $assign_id)
                ->select('subject.name as subject', 'assignment.name as assignmentname')
                ->groupby('subject.id')
                ->get();
            $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
                ->join('users', 'users.id', '=', 'assignment_user.user_id')
                ->where('gradestatus', '=', 'completed')
                ->where('user_assignment_result.assignment_id', '=', $assign_id)
                ->select('users.name as user', 'user_assignment_result.rawscore as score', 'user_assignment_result.percentage')
                ->orderby('assignment_user.gradeddate', 'desc')
                ->get();
            $score = $students->sum('score');
            $user = $students->count('user.name');
            //dd($sun);

            $student = $students[0];
        } else {
            //dd();
            //$assign_id = AssignmentUser::select('assignment_id')->where('assignment_user.user_id', '=', $uid)->orderby('created_at', 'desc')->get();

            $students = AssignmentUser::join('user_assignment_result', 'user_assignment_result.assignment_id', '=', 'assignment_user.assignment_id')
                ->join('users', 'users.id', '=', 'assignment_user.user_id')
                ->join('assessment', 'assignment_user.assessment_id', '=', 'assessment.id')
                ->where('gradestatus', '=', 'completed')
                //   ->where('user_assignment_result.assignment_id','=',$assign_id);
                ->select('user_assignment_result.assignment_id', 'users.name as user', 'user_assignment_result.rawscore as score', 'assessment.subject_id as sub_id')
                ->orderby('assignment_user.gradeddate', 'desc')->get();
            $score = $students->sum('user_assignment_result.score');
            $user = $students->count('users.name');
            // $subject=DB::table('subject')->where('id',$students->assessment.subject_id)->lists('id','name');
            $student = $students[0];
            // dd($subject);
            //dd($sun);
        }
        return view('report::report.wholescoretile', compact('student', 'score', 'user'));
    }
    public function testhistoryexportPDF($id)
    {

        $inst = Institution::where('id', '=', $id)->select('name')->get();

        $counts = Array();
        $rec = Array();
        //$assessment_arr=Array();
        $lists = Assignment::where('institution_id', '=', $id)->lists('assessment_id', 'id');
        $assignments = array_keys($lists);
        $users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->whereIn('assignment_id', $assignments)->GroupBy('assignment_id')->get();
        $completed_users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->GroupBy('assignment_id')->where('status', 'completed')->get();
        foreach ($users as $user) {
            $All_users[$user->assignment_id] = $user->count;
        }
        foreach ($completed_users as $completed_user) {
            $complete_users[$completed_user->assignment_id] = $completed_user->count;
        }
        $assignments = Assignment::join('assessment', 'assignment.assessment_id', '=', DB::raw('assessment.id && assignment.institution_id =' . $id))->select('assignment.name as assign_name', 'assignment.id as assign_id', 'assessment.name as assess_name')->get();
        $assessment_arr = array_unique($lists);
        foreach ($assessment_arr as $arr) {
            $counts[$arr] = AssessmentQuestion::where('assessment_id', $arr)->count('question_id');
        }
        $records = Assessment::whereIn('id', $assessment_arr)->select('id', 'guessing_panality', 'mcsingleanswerpoint', 'essayanswerpoint')->get();
        //dd($records);
        foreach ($records as $record) {
            $rec[$record['id']] = Array();
            array_push($rec[$record['id']], $record);
        }
        $a = Array();
        $marks = Array();
        foreach ($lists as $key => $list) {
            $correct = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'Yes')->count();
            $wrong = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'No')->count();
            $lost_marks[$key] = (float)($wrong) * ($rec[$list][0]->guessing_panality);
            $mark = ((float)$correct * $rec[$list][0]->mcsingleanswerpoint) - (float)$lost_marks[$key];
            //dd($mark);
            $marks[$key] = isset($complete_users[$key]) ? ($mark / ($complete_users[$key] * $counts[$list] * $rec[$list][0]->mcsingleanswerpoint)) * 100 : 0;
        }

        //$footerHtml = view('layouts.pdf_partials.footer', compact('footerMeta'))->render();
        $htmlForPdf = view('report::report.testhistorypdf', compact('assignments', 'marks', 'All_users', 'complete_users', 'inst'))->render();
       // dd($htmlForPdf);
        $fileName = 'testhistoryreport';
        /*$fileFullUrl = createPdfForReport($fileName, $htmlForPdf);
        //dd($fileFullUrl);
         $name=explode('/',$fileFullUrl);
        $name=$name[5];
       // return url($fileFullUrl);
       // return response()->Download($fileFullUrl);
          return response()->Download(public_path()."/data/reports/".$name);
//        return response()->Download("/var/www/AssessmentTool/public/data/reports/".$name);*/
          $name = createPdfForReport($fileName, $htmlForPdf,'','only-name');
            if($name == url('data/error.pdf'))
            {
                return response()->download(public_path()."/data/error.pdf");    
            }
            else
            {
                return response()->download(public_path()."/data/reports/".$name);
            }
    }


    public function testhistoryexportXLS($id)
    {
        $inst = Institution::where('id', '=', $id)->select('name')->get();
        $All_users=[];
        $counts = Array();
        $rec = Array();
        //$assessment_arr=Array();
        $lists = Assignment::where('institution_id', '=', $id)->lists('assessment_id', 'id');
        $assignments = array_keys($lists);
        $users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->whereIn('assignment_id', $assignments)->GroupBy('assignment_id')->get();
        $completed_users = AssignmentUser::selectRaw('assignment_id, count(assignment_id) as count')->GroupBy('assignment_id')->where('status', 'completed')->get();
        foreach ($users as $user) {
            $All_users[$user->assignment_id] = $user->count;
        }
        foreach ($completed_users as $completed_user) {
            $complete_users[$completed_user->assignment_id] = $completed_user->count;
        }
        $assignments = Assignment::join('assessment', 'assignment.assessment_id', '=', DB::raw('assessment.id && assignment.institution_id =' . $id))->select('assignment.name as assign_name', 'assignment.id as assign_id', 'assessment.name as assess_name')->get();
        $assessment_arr = array_unique($lists);
        foreach ($assessment_arr as $arr) {
            $counts[$arr] = AssessmentQuestion::where('assessment_id', $arr)->count('question_id');
        }
        $records = Assessment::whereIn('id', $assessment_arr)->select('id', 'guessing_panality', 'mcsingleanswerpoint', 'essayanswerpoint')->get();
        //dd($records);
        foreach ($records as $record) {
            $rec[$record['id']] = Array();
            array_push($rec[$record['id']], $record);
        }
        $a = Array();
        $marks = Array();
        foreach ($lists as $key => $list) {
            $correct = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'Yes')->count();
            $wrong = db::table('question_user_answer')->where('assessment_id', $list)->where('assignment_id', $key)->where('is_correct', 'No')->count();
            $lost_marks[$key] = (float)($wrong) * ($rec[$list][0]->guessing_panality);
            $mark = ((float)$correct * $rec[$list][0]->mcsingleanswerpoint) - (float)$lost_marks[$key];
            //dd($mark);
            $marks[$key] = isset($complete_users[$key]) ? ($mark / ($complete_users[$key] * $counts[$list] * $rec[$list][0]->mcsingleanswerpoint)) * 100 : 0;
        }
        return Excel::create('Assessment report', function ($excel) use ($assignments, $marks, $All_users, $complete_users, $inst) {
            $excel->sheet('mySheet', function ($sheet) use ($assignments, $marks, $All_users, $complete_users, $inst) {
                //$sheet->loadView($students);
                $sheet->loadView('report::report.testhistorypdf', array("assignments" => $assignments, "marks" => $marks, "All_users" => $All_users, "complete_users" => $complete_users, "inst" => $inst));
                //$sheet->fromArray($students);
            });
        })->download("xls");


    }

    public function QuestionsexportPDF($inst_id = 0, $assign_id = 0, $sub_id = 0)
    {
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();
        $assign = Assignment::where('id', '=', $assign_id)->select('name')->get();
        $sub = Subject::where('id', '=', $sub_id)->select('name')->get();

        $assessment = Assignment::find($assign_id);
        $question = [];
        if ($assessment) {
            $question = AssessmentQuestion::where('assessment_id', $assessment->id)->lists('question_id');
        }
        $ques = Question::whereIn('id', $question)->lists('title', 'id');
        $questions = Question::whereIn('id', $question);
        if ($sub_id != 0) {
            $questions->where('subject_id', '=', $sub_id);
        }
        $questions = $questions->lists('id');
        $user_count = QuestionUserAnswer::where('assignment_id', $assign_id)->selectRaw('question_id,count(user_id) as count')->groupBy('question_id')->lists('count', 'question_id');
        $user_answered_correct_count = QuestionUserAnswer::whereIn('question_id', $questions)->where('assignment_id', $assign_id)->where('is_correct', 'Yes')->selectRaw('question_id,count(user_id) as count')->groupBy('question_id')->lists('count', 'question_id');

        $htmlForPdf = view('report::report.Questionpdf', compact('ques', 'user_answered_correct_count', 'user_count', 'inst', 'assign','sub'))->render();
        $fileName = 'answer';
        /*$fileFullUrl = createPdfForReport($fileName, $htmlForPdf);
        $name=explode('/',$fileFullUrl);
        $name=$name[5];
       // return response()->Download("/var/www/AssessmentTool/public/data/reports/".$name);
        return response()->Download(public_path()."/data/reports/".$name);*/
        $name = createPdfForReport($fileName, $htmlForPdf,'','only-name');
        if($name == url('data/error.pdf'))
        {
            return response()->download(public_path()."/data/error.pdf");    
        }
        else
        {
            return response()->download(public_path()."/data/reports/".$name);
        }
    }
    public function QuestionsexportXLS($inst_id = 0, $assign_id = 0, $sub_id = 0)
    {
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();
        $assign = Assignment::where('id', '=', $assign_id)->select('name')->get();
        $sub = Subject::where('id', '=', $sub_id)->select('name')->get();

        $assessment = Assignment::find($assign_id);
        $question = [];
        if ($assessment) {
            $question = AssessmentQuestion::where('assessment_id', $assessment->id)->lists('question_id');
        }
        $ques = Question::whereIn('id', $question)->lists('title', 'id');
        $questions = Question::whereIn('id', $question);
        if ($sub_id != 0) {
            $questions->where('subject_id', '=', $sub_id);
        }
        $questions = $questions->lists('id');
        $user_count = QuestionUserAnswer::where('assignment_id', $assign_id)->selectRaw('question_id,count(user_id) as count')->groupBy('question_id')->lists('count', 'question_id');
        $user_answered_correct_count = QuestionUserAnswer::whereIn('question_id', $questions)->where('assignment_id', $assign_id)->where('is_correct', 'Yes')->selectRaw('question_id,count(user_id) as count')->groupBy('question_id')->lists('count', 'question_id');
        return Excel::create('Assessment report', function ($excel) use ($ques, $user_answered_correct_count, $user_count, $inst, $assign, $sub) {
            $excel->sheet('mySheet', function ($sheet) use ($ques, $user_answered_correct_count, $user_count, $inst, $assign, $sub) {
                //$sheet->loadView($students);
                $sheet->loadView('report::report.Questionpdf', array("ques" => $ques, "user_answered_correct_count" => $user_answered_correct_count, "user_count" => $user_count, "inst" => $inst, "assign" => $assign, "sub" => $sub));
                //$sheet->fromArray($students);
            });
        })->download("xls");
    }

    public function leastscoreexportPDF()
    {
        $ins = \Auth::user()->institution_id;
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        /*$assignment = Assignment::select('id')
            ->where('gradestatus', '=', 'completed')
            ->take(3)
            ->orderby('id', 'desc')
            ->get();
        $assignment = [];
        $data = [];*/
        $assignment = Assignment::where('gradestatus', '=', 'completed')->take(3)->orderby('id', 'desc')->lists('id');
        $assignmentname = Assignment::whereIn('id', $assignment)->orderby('id', 'desc')->lists('name');
        //dd($assignmentname);
        //$ques=Question::whereIn('id',$question)->lists('title','id');
        /*$students=UserAssignmentResult::whereIn('assignment_id',$assignment)->take(10)->select('rawscore','user_id','assignment_id')->lists('user_id');
        $asgnmts=UserAssignmentResult::whereIn('assignment_id',$assignment)->take(10)->select('rawscore','user_id','assignment_id')->lists('assignment_id');*/
        //dd($assignment);

        $report_data = $report_data1 = $report_data2 = [];
        if(isset($assignment[0]) && $assignment[0]>0)
        {
        $report_data = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
            ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
            ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
            ->where('assignment.id', '=', $assignment[0])
            ->orderBy('assignment.id')
            ->orderby('user_assignment_result.rawscore', 'asc')
            ->take(10)
            ->get();

        }
        if(isset($assignment[1]) && $assignment[1]>0)
        { 
            $report_data1 = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
            ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
            ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
            ->where('assignment.id', '=', $assignment[1])
            ->orderBy('assignment.id')
            ->orderby('user_assignment_result.rawscore', 'asc')
            ->take(10)
            ->get();
        }
        if(isset($assignment[2]) && $assignment[2]>0)
        {
        $report_data2 = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
            ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
            ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
            ->where('assignment.id', '=', $assignment[2])
            ->orderBy('assignment.id')
            ->orderby('user_assignment_result.rawscore', 'asc')
            ->take(10)
            ->get();
        }
        //return view('report::report.least_score', compact('report_data','report_data1','report_data2','assignmentname'));
       /* return Excel::create('Assessment report', function ($excel) use ($report_data, $report_data1, $report_data2, $assignmentname) {
            $excel->sheet('mySheet', function ($sheet) use ($report_data, $report_data1, $report_data2, $assignmentname) {
                //$sheet->loadView($students);
                $sheet->loadView('report::report.leastpdf', array("report_data" => $report_data, "report_data1" => $report_data1, "report_data2" => $report_data2, "assignmentname" => $assignmentname));
                //$sheet->fromArray($students);
            });
        })->download("pdf");*/
        $htmlForPdf = view('report::report.leastpdf', compact('report_data','report_data1','report_data2','assignmentname'))->render();
        $fileName = 'least_score';

        /*$fileFullUrl = createPdfForReport($fileName, $htmlForPdf);
        $name=explode('/',$fileFullUrl);
        $name=$name[5];*/
        $name = createPdfForReport($fileName, $htmlForPdf,'','only-name');
        if($name == url('data/error.pdf'))
        {
            return response()->download(public_path()."/data/error.pdf");    
        }
        else
        {
            return response()->download(public_path()."/data/reports/".$name);
        }        
    }

    public function leastscoreexportXLS()
    {
        $ins = \Auth::user()->institution_id;
        $InstitutionObj = new Institution();
        $inst_arr = $InstitutionObj->getInstitutions();
        /*$assignment = Assignment::select('id')
            ->where('gradestatus', '=', 'completed')
            ->take(3)
            ->orderby('id', 'desc')
            ->get();
        $assignment = [];
        $data = [];*/
        $assignment = Assignment::where('gradestatus', '=', 'completed')->take(3)->orderby('id', 'desc')->lists('id');
        $assignmentname = Assignment::whereIn('id', $assignment)->orderby('id', 'desc')->lists('name');
        //dd($assignmentname);
        //$ques=Question::whereIn('id',$question)->lists('title','id');
        /*$students=UserAssignmentResult::whereIn('assignment_id',$assignment)->take(10)->select('rawscore','user_id','assignment_id')->lists('user_id');
        $asgnmts=UserAssignmentResult::whereIn('assignment_id',$assignment)->take(10)->select('rawscore','user_id','assignment_id')->lists('assignment_id');*/
        //dd($assignment);

        $report_data = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
            ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
            ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
            ->where('assignment.id', '=', $assignment[0])
            ->orderBy('assignment.id')
            ->orderby('user_assignment_result.rawscore', 'asc')
            ->take(10)
            ->get();
        $report_data1 = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
            ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
            ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
            ->where('assignment.id', '=', $assignment[1])
            ->orderBy('assignment.id')
            ->orderby('user_assignment_result.rawscore', 'asc')
            ->take(10)
            ->get();
        $report_data2 = UserAssignmentResult::join('users', 'users.id', '=', 'user_assignment_result.user_id')
            ->join('assignment', 'assignment.id', '=', 'user_assignment_result.assignment_id')
            ->select('user_assignment_result.rawscore', 'users.name as user_name', 'assignment.name as assignment_name')
            ->where('assignment.id', '=', $assignment[2])
            ->orderBy('assignment.id')
            ->orderby('user_assignment_result.rawscore', 'asc')
            ->take(10)
            ->get();
        return Excel::create('Assessment report', function ($excel) use ($report_data, $report_data1, $report_data2, $assignmentname) {
            $excel->sheet('mySheet', function ($sheet) use ($report_data, $report_data1, $report_data2, $assignmentname) {
                $sheet->loadView('report::report.leastpdf', array("report_data" => $report_data, "report_data1" => $report_data1, "report_data2" => $report_data2, "assignmentname" => $assignmentname));
            });
        })->download("xls");
    }

    public function lesson_change($sub)
    {
        $subject_id = explode(',', $sub);
        $lessons = Lesson::whereIn('subject_id', $subject_id)->lists('name', 'id');
        //dd($lessons);

        return $lessons;
    }

    public function wholeclassscoreexportPDF($inst_id = 0, $assi_id = 0, $sub_id = 0, $less_id = 0)
    {
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();
        $assign = Assignment::where('id', '=', $assi_id)->select('name')->get();
        $sub = Subject::where('id', '=', $sub_id)->select('name')->get();
        $less = Lesson::where('id', '=', $less_id)->select('name')->get();

        if ($assi_id == 0 || $assi_id == "null" || $sub_id == "null" || $sub_id == 0) {
            return "";
        }
        $assignment = Assignment::find($assi_id);
        $assessment = Assessment::find($assignment->assessment_id);

        if ($sub_id != "null" || $sub_id != 0) {
            $subjects = explode(",", $sub_id);
        } else {
            $subjects = [$sub_id];
        }


        //  dd($lessons);
        if (count($subjects) > 1) {
            $case = 1;
        } else {
            $case = 2;
            if ($less_id != "null" || $less_id != 0) {
                $lessons = explode(",", $less_id);
                $lessons = Lesson::whereIn('id', $lessons)->lists('name', 'id');
            } else {
                $lessons = Lesson::whereIn('subject_id', $subjects)->lists('name', 'id');
            }

        }

        $subjects = Subject::whereIn('id', $subjects)->lists('name', 'id');
        $multi_answer_type_id = DB::table('question_type')->where('qst_type_text', "Multiple Choice - Multi Answer")->first()->id;
        $single_answer_type_id = DB::table('question_type')->where('qst_type_text', "Multiple Choice - Single Answer")->first()->id;
        $essay_answer_type_id = DB::table('question_type')->where('qst_type_text', "Essay")->first()->id;
        $subject_questions = [];

        //dd($subject_questions);
        $students = AssignmentUser::join('users', 'assignment_user.user_id', '=', DB::raw('users.id and assignment_user.assignment_id =' . $assi_id))
            ->select('users.name', 'users.id')
            ->lists('name', 'id');
        // dd($students);
        switch ($case) {
            case  1 : {
                $type = "subjects";
                foreach ($subjects as $key => $sub) {
                    $subject_questions[$key]['multi_or_single_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $key)
                        ->whereIn('questions.question_type_id', [$multi_answer_type_id, $single_answer_type_id])
                        ->select('questions.id')
                        ->lists('id');
                    $subject_questions[$key]['essay_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $key)
                        ->where('questions.question_type_id', $essay_answer_type_id)
                        ->select('questions.id')
                        ->lists('id');
                }
                $subject_score = [];
                $penality = [];
                foreach ($students as $stud_id => $student) {
                    foreach ($subjects as $key => $name) {
                        $total_questions = array_merge($subject_questions[$key]['multi_or_single_answer_type'], $subject_questions[$key]['essay_answer_type']);
                        $subject_score[$stud_id][$key] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $total_questions)->selectRaw(' sum(points) as sum, count(*) as total')->get();
                        $penality[$stud_id][$key]['multi_single'] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $subject_questions[$key]['multi_or_single_answer_type'])
                            ->where('is_correct', "No")
                            ->count();
                        // $penality[$stud_id][$key]['essay']=QuestionUserAnswer::where('assignment_id',$assi_id)->where('user_id',$stud_id)
                        //     ->whereIn('question_id',$subject_questions[$key]['essay_answer_type'])
                        //     ->where('is_correct',"Open")
                        //     ->count();
                        // dd($penality);
//
                    }
                }

                //  dd($subject_score);
                return view('report::report.wholescoreview_duplicate', compact('type', 'subjects', 'assignment', 'subject_score', 'students', 'penality'));
            }
            case 2 : {
                $type = "lessons";
                foreach ($lessons as $key => $lesson) {
                    $lesson_questions[$key]['multi_or_single_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $sub_id)
                        ->where('questions.lesson_id', $key)
                        ->whereIn('questions.question_type_id', [$multi_answer_type_id, $single_answer_type_id])
                        ->select('questions.id')
                        ->lists('id');
                    $lesson_questions[$key]['essay_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $sub_id)
                        ->where('questions.lesson_id', $key)
                        ->where('questions.question_type_id', $essay_answer_type_id)
                        ->select('questions.id')
                        ->lists('id');
                }
                // dd($lessons);
                $lesson_score = [];
                $penality = [];
                foreach ($students as $stud_id => $student) {
                    foreach ($lessons as $key => $name) {
                        $total_questions = array_merge($lesson_questions[$key]['multi_or_single_answer_type'], $lesson_questions[$key]['essay_answer_type']);
                        $lesson_score[$stud_id][$key] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $total_questions)->selectRaw('sum(points) as sum, count(*) as total')->get();
                        $penality[$stud_id][$key]['multi_single'] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $lesson_questions[$key]['multi_or_single_answer_type'])
                            ->where('is_correct', "No")
                            ->count();
                        // $penality[$stud_id][$key]['essay']=QuestionUserAnswer::where('assignment_id',$assi_id)->where('user_id',$stud_id)
                        //     ->whereIn('question_id',$lesson_questions[$key]['essay_answer_type'])
                        //     ->where('is_correct',"Open")
                        //     ->count();
//
                    }
                }
                $htmlForPdf =view('report::report.wholeclassscorereportpdf', compact('inst','assign','sub','less','type', 'lessons', 'assignment', 'students', 'lesson_score', 'subjects', 'sub_id', 'penality'))->render();
                $fileName = 'wholeclassscorereportpdf';
                /*$fileFullUrl = createPdfForReport($fileName, $htmlForPdf);
                $name=explode('/',$fileFullUrl);
                $name=$name[5];
                //return response()->Download("/var/www/AssessmentTool/public/data/reports/".$name);
                return response()->Download(public_path()."/data/reports/".$name);*/
                $name = createPdfForReport($fileName, $htmlForPdf,'','only-name');
                if($name == url('data/error.pdf'))
                {
                    return response()->download(public_path()."/data/error.pdf");    
                }
                else
                {
                    return response()->download(public_path()."/data/reports/".$name);
                }
              }
            }

        }


    public function wholeclassscoreexportXLS($inst_id = 0, $assi_id = 0, $sub_id = 0, $less_id = 0)
    {
        $inst = Institution::where('id', '=', $inst_id)->select('name')->get();
        $assign = Assignment::where('id', '=', $assi_id)->select('name')->get();
        $sub = Subject::where('id', '=', $sub_id)->select('name')->get();
        $less=Lesson::where('id', '=',$less_id)->select('name')->get();

        if ($assi_id == 0 || $assi_id == "null" || $sub_id == "null" || $sub_id == 0) {
            return "";
        }
        $assignment = Assignment::find($assi_id);
        $assessment = Assessment::find($assignment->assessment_id);

        if ($sub_id != "null" || $sub_id != 0) {
            $subjects = explode(",", $sub_id);
        } else {
            $subjects = [$sub_id];
        }


        //  dd($lessons);
        if (count($subjects) > 1) {
            $case = 1;
        } else {
            $case = 2;
            if ($less_id != "null" || $less_id != 0) {
                $lessons = explode(",", $less_id);
                $lessons = Lesson::whereIn('id', $lessons)->lists('name', 'id');
            } else {
                $lessons = Lesson::whereIn('subject_id', $subjects)->lists('name', 'id');
            }

        }

        $subjects = Subject::whereIn('id', $subjects)->lists('name', 'id');
        $multi_answer_type_id = DB::table('question_type')->where('qst_type_text', "Multiple Choice - Multi Answer")->first()->id;
        $single_answer_type_id = DB::table('question_type')->where('qst_type_text', "Multiple Choice - Single Answer")->first()->id;
        $essay_answer_type_id = DB::table('question_type')->where('qst_type_text', "Essay")->first()->id;
        $subject_questions = [];

        //dd($subject_questions);
        $students = AssignmentUser::join('users', 'assignment_user.user_id', '=', DB::raw('users.id and assignment_user.assignment_id =' . $assi_id))
            ->select('users.name', 'users.id')
            ->lists('name', 'id');
        // dd($students);
        switch ($case) {
            case  1 : {
                $type = "subjects";
                foreach ($subjects as $key => $sub) {
                    $subject_questions[$key]['multi_or_single_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $key)
                        ->whereIn('questions.question_type_id', [$multi_answer_type_id, $single_answer_type_id])
                        ->select('questions.id')
                        ->lists('id');
                    $subject_questions[$key]['essay_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $key)
                        ->where('questions.question_type_id', $essay_answer_type_id)
                        ->select('questions.id')
                        ->lists('id');
                }
                $subject_score = [];
                $penality = [];
                foreach ($students as $stud_id => $student) {
                    foreach ($subjects as $key => $name) {
                        $total_questions = array_merge($subject_questions[$key]['multi_or_single_answer_type'], $subject_questions[$key]['essay_answer_type']);
                        $subject_score[$stud_id][$key] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $total_questions)->selectRaw(' sum(points) as sum, count(*) as total')->get();
                        $penality[$stud_id][$key]['multi_single'] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $subject_questions[$key]['multi_or_single_answer_type'])
                            ->where('is_correct', "No")
                            ->count();
                        // $penality[$stud_id][$key]['essay']=QuestionUserAnswer::where('assignment_id',$assi_id)->where('user_id',$stud_id)
                        //     ->whereIn('question_id',$subject_questions[$key]['essay_answer_type'])
                        //     ->where('is_correct',"Open")
                        //     ->count();
                        // dd($penality);
//
                    }
                }

                //  dd($subject_score);
                return view('report::report.wholescoreview_duplicate', compact('type', 'subjects', 'assignment', 'subject_score', 'students', 'penality'));
            }
            case 2 : {
                $type = "lessons";
                foreach ($lessons as $key => $lesson) {
                    $lesson_questions[$key]['multi_or_single_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $sub_id)
                        ->where('questions.lesson_id', $key)
                        ->whereIn('questions.question_type_id', [$multi_answer_type_id, $single_answer_type_id])
                        ->select('questions.id')
                        ->lists('id');
                    $lesson_questions[$key]['essay_answer_type'] = AssessmentQuestion::join('questions', 'assessment_question.question_id', '=', 'questions.id')
                        ->where('assessment_question.assessment_id', $assignment->assessment_id)
                        ->where('questions.subject_id', $sub_id)
                        ->where('questions.lesson_id', $key)
                        ->where('questions.question_type_id', $essay_answer_type_id)
                        ->select('questions.id')
                        ->lists('id');
                }
                // dd($lessons);
                $lesson_score = [];
                $penality = [];
                foreach ($students as $stud_id => $student) {
                    foreach ($lessons as $key => $name) {
                        $total_questions = array_merge($lesson_questions[$key]['multi_or_single_answer_type'], $lesson_questions[$key]['essay_answer_type']);
                        $lesson_score[$stud_id][$key] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $total_questions)->selectRaw('sum(points) as sum, count(*) as total')->get();
                        $penality[$stud_id][$key]['multi_single'] = QuestionUserAnswer::where('assignment_id', $assi_id)->where('user_id', $stud_id)
                            ->whereIn('question_id', $lesson_questions[$key]['multi_or_single_answer_type'])
                            ->where('is_correct', "No")
                            ->count();
                        // $penality[$stud_id][$key]['essay']=QuestionUserAnswer::where('assignment_id',$assi_id)->where('user_id',$stud_id)
                        //     ->whereIn('question_id',$lesson_questions[$key]['essay_answer_type'])
                        //     ->where('is_correct',"Open")
                        //     ->count();
//
                    }
                }
                // dd($lesson_score);
                // return view('report::report.wholescoreview_duplicate', compact('type', 'lessons', 'assignment', 'students', 'lesson_score', 'subjects', 'sub_id', 'penality'));
                return Excel::create('Assessment report', function ($excel) use ($type, $lessons, $assignment, $students, $lesson_score, $subjects, $sub_id, $penality,$inst,$assign,$sub,$less) {
                    $excel->sheet('mySheet', function ($sheet) use ($type, $lessons, $assignment, $students, $lesson_score, $subjects, $sub_id, $penality,$inst,$assign,$sub,$less) {
                        //$sheet->loadView($students);
                        $sheet->loadView('report::report.wholeclassscorereportpdf', array("type" => $type, "lessons" => $lessons, "assignment" => $assignment, "students" => $students, "lesson_score" => $lesson_score, "subjects" => $subjects, "sub_id" => $sub_id, "penality" => $penality,'inst' =>$inst,'assign'=>$assign,'sub'=>$sub,'less'=>$less));
                        //$sheet->fromArray($students);
                    });
                })->download("xls");
            }

        }
    }

}
