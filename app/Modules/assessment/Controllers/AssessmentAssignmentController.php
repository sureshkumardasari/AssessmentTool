<?php namespace App\Modules\Assessment\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Html\HtmlFacade;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use View;
use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Assessment\Models\QuestionUserAnswer;
use App\Modules\Assessment\Models\QuestionUserAnswerRetake;
use App\Modules\Grading\Models\Grade;

class AssessmentAssignmentController extends BaseController {

	

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');

		$obj = new Assignment();
		$this->assignment = $obj;

		$obj = new Institution();
		$this->institution = $obj;

		$obj = new User();
		$this->user = $obj;

		$obj = new AssignmentUser();
		$this->assignmentuser = $obj;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}    

	public function assignment($parent_id = 0)
	{
		$assignments = $this->assignment->getassignment();
        return view('resources::assignment.list',compact('assignments'));
	}

    public function myassignment_old($user_id = 0)
    {
        $myassignment = $this->assignment->getTests();
        //var_dump($myassignment);
        return view('assessment::myassignment', compact('myassignment'));
    }

    public function myassignment($user_id = 0)
    {
        $myassignment = $this->assignment->getMyTestList();
        //var_dump($myassignment);
        return view('assessment::myallassignment', compact('myassignment'));
    }
    
    public function getTestInstructions($id) {

        $from = Input::get('from', '');

    	$ids = explode('-', $id);

    	$aId 	= $ids[0];		// assessment id
    	$aAId 	= $ids[1];	// assessment assignment id

        $AssignmentUser = new AssignmentUser();
        $status = $AssignmentUser->getAssignmentUserStatus($aId, $aAId);
        if($status == 'completed' || $status == '')
        {
            $message = ($status == 'completed') ? 'Test is already completed...!' : 'Test not found...!';
            return view('assessment::test_error', compact('message'));
        }
        $AssignmentUser->complete($aId, $aAId, 'instructions');


    	$assessment = Assessment::find($aId);
        $assignment = $this->assignment->find($aAId);
    	
        $launchType = $assignment->launchtype;
        // get test type
        $assessmentType = '';//$this->getTestType($aId);

        $flag = \Input::get('flag', '');  
        $instructions['header'] = $assessment->header;
        $instructions['title_page'] = $assessment->titlepage;
        $instructions['begin_instructions'] = $assessment->begin_instruction;
        $instructions['footer'] = $assessment->footer;      

    	return view('assessment::test_instructions', compact('instructions', 'id', 'flag', 'assessmentType','launchType', 'from'));
    }
    public function testDetail($id) {
        
    	$ids = explode('-', $id);
        $aId    = $ids[0];      // assessment id
        $aAId   = $ids[1];  // assessment assignment id

        //getting Assessment
        $assessment = Assessment::find($aId);
        if($assessment == null)
        {
            $message = 'Test not found...!';
            return view('assessment::test_error', compact('message'));            
        }        

        $secs = ($assessment->totaltime > 0) ? $assessment->totaltime : 0;
        $secs = ($assessment->unlimitedtime == '1') ? 0 : $secs;


        $AssignmentUser = new AssignmentUser();
        $status = $AssignmentUser->getAssignmentUserStatus($aId, $aAId);
        if($status == 'completed' || $status == '')
        {
            $message = ($status == 'completed') ? 'Test is already completed...!' : 'Test not found...!';
            return view('assessment::test_error', compact('message'));
        }
        $AssignmentUser->complete($aId, $aAId, 'inprogress');

        $assessmentType = '';

    	$path = public_path('data/assessment_pdf_images/assessment_'.$aId);

    	$filesArr = $this->getFiles($id);
        $filesCount = $this->getFilesCount($id);
        // dd($filesArr);

    	$bulletType = '';
    	// get question based on subsection id
    	$qbank = new AssessmentQuestion();
    	$questions = $qbank->getQuestionsByAssessment($aId, $aAId);        

        $retaking = false;
        View::share('retaking', $retaking);

    	$ansPanel = view('assessment::partial._answer_panel', compact('questions', 'bulletType'));
    	return view('assessment::test_detail', compact( 'secs', 'id', 'filesArr', 'filesCount', 'path', 'ansPanel', 'aId', 'assessment'));
    }

    private function getFiles($id) {
        $ids = explode('-', $id);

        $aId    = $ids[0];      // assessment id
        $aAId   = $ids[1];  // assessment assignment id     

        $filesArr = [];
        if(getenv('s3storage'))
        {
            $s3 = new \App\Models\S3;
            $files = $s3->getFiles('assessment_'.$aId, 'assessment_pdf_images');
            if( !empty($files) ) {
                for($i = 0; $i < count($files); $i++){
                    $fileName = '/assessment_' . $aId . '/' . $i . '.jpg';
                    if ( s3FileExists($fileName, 'assessment_pdf_images') )
                    {
                        $filesArr[] = getS3ViewUrl($fileName, 'assessment_pdf_images') ;
                    }
                }
            }                                
        }
        else
        {
            $directory = public_path('data/assessment_pdf_images/assessment_' . $aId . '/');
            $files = glob($directory . '*.jpg');
            if ( $files !== false ) {
                for($i = 0; $i < count($files); $i++){
                    $fileName = '/assessment_' . $aId . '/' . $i . '.jpg';
                    if ( file_exists(public_path('data/assessment_pdf_images'.$fileName) ))
                    {
                        $filesArr[] = asset('data/assessment_pdf_images'.$fileName) ;
                    }
                }
            }  
        }
        return $filesArr;
    }

    private function getFilesCount($id) {
    	$ids = explode('-', $id);

    	$aId 	= $ids[0];		// assessment id
    	$aAId 	= $ids[1];	// assessment assignment id    	

        $files = [];
        if(getenv('s3storage'))
        {
            $s3 = new \App\Models\S3;
            $files = $s3->getFiles('assessment_'.$aId, 'assessment_pdf_images');

            if( empty($files) ) {
                return 0;
            };
        }
        else
        {
            $directory = public_path('data/assessment_pdf_images/assessment_' . $aId . '/');
            $files = glob($directory . '*.jpg');
            
            if ( $files === false )
            {
                return 0;
            }
        }
    	return count($files);
    }

    public function saveAnswer(Request $request) {

        $data = $request->all();
        $this->_updateTestTime($data['id']);
        //dd($data['credentials']);
        $questionAnswer = new QuestionUserAnswer();            
        if (isset($data['retaking']) && $data['retaking'] == '1') {
            $questionAnswer = new QuestionUserAnswerRetake();
        }

        $update = [];
        // delete old answer
        foreach ($data['credentials'] as $key => $credential) {  
            $oldAnswer = $questionAnswer::where([
                                        'question_id' => $credential['SubsectionQuestionId'],
                                        'assignment_id' => $credential['AssessmentAssignmentId'],
                                        'user_id' => Auth::user()->id
                                    ])->delete();
            /**********/
            $update[$key]['question_id'] = $credential['SubsectionQuestionId'];
            $update[$key]['assignment_id'] = $credential['AssessmentAssignmentId'];
            $update[$key]['assessment_id'] = $credential['AssessmentId'];
            $update[$key]['user_id'] = Auth::user()->id;
            $update[$key]['question_answer_id'] = $credential['QuestionAnswerId'];
            $update[$key]['added_by'] = Auth::user()->id;
            $update[$key]['updated_by'] = Auth::user()->id;
            // set empty values to null
            $update[$key]['question_answer_text'] = $credential['QuestionAnswerText'];
            $update[$key]['answer_option'] = $credential['Option'];
            // add timestamp fields to array
            $update[$key]['created_at'] = date('Y-m-d H:i:s');
            $update[$key]['updated_at'] = date('Y-m-d H:i:s');
            /**********/

            // delete index that have empty answer index
            if ((empty($credential['QuestionAnswerId']) || $credential['QuestionAnswerId'] == 0) && empty($credential['Option']) && empty($credential['QuestionAnswerText'])) {
                unset($update[$key]);
            }
         }


        if (count($update)) {
            $questionAnswer::insert($update);
        }
    }
    public function updateTestTime(Request $request) {

        $data = $request->all();
        return $this->_updateTestTime($data['id']);
    }

    private function _updateTestTime($id) {

        $ids = explode('-', $id);

        $aId    = $ids[0];      // assessment id
        $aAId   = $ids[1];  // assessment assignment id
        
        //$sSStatus = new SubsectionUserStatus();
        //$sSStatus->updateTestTime($aId, $aAId);

        return AssignmentUser::where(['assignment_id' => $aAId, 'user_id' => Auth::user()->id])->first()->status;
    }
    public function openSubmitConfirmPopuop($id) {

        $ids = explode('-', $id);

        $aId    = $ids[0];      // assessment id
        $aAId   = $ids[1];  // assessment assignment id

        $endInstructions = Assessment::find($aId)->end_instruction;

        return view('assessment::partial._confirm_popup', compact('endInstructions'));
    }
    public function openEssayPopuop($subSecQuestionId, $questionId) {

        $retaking = Input::get('retake', '');

        $questionObj = Question::find($questionId);
        $questionText = "";
        if(!empty($questionObj)){
            $questionText= $questionObj->qst_text;
        }
        
        $qAnswer = new QuestionUserAnswer();
        if ($retaking == "1") {
            $qAnswer = new QuestionUserAnswerRetake();
        }

        // get old entered essay
        $oldEssay = $qAnswer::where([
                                        'question_id' => $subSecQuestionId,
                                        'user_id' => Auth::user()->id
                                    ])->first();
        return view('assessment::partial._essay_popup', compact('questionText', 'subSecQuestionId', 'oldEssay'));
    }    

    public function submitTest(Request $request) {
        $data = $request->all();
        $ids = explode('-', $data['id']);

        $aId    = $ids[0];  // assessment id
        $aAId   = $ids[1];  // assessment assignment id

        $grade  = new Grade();
        
        $params = [
            'assessment_id' => $aId,
            'assignment_id' => $aAId,
            'user_id'       => \Auth::user()->id, 
            'retake'       => $data['retaking'], 
        ];
        try {
            $grade->gradeSystemStudents( $params );            
        } catch (Exception $e) {
            
        }
        
        $AssignmentUser = new AssignmentUser();
        $AssignmentUser->complete($aId, $aAId, 'completed');
        $allusers=AssignmentUser::where('assignment_id',$aAId)->get()->count();
        $completed_users=AssignmentUser::where('assignment_id',$aAId)->where('status',"completed")->get()->count();
        if($allusers==$completed_users){
            Assignment::where('id',$aAId)->update(['status'=>"completed"]);
        }

        // check if assessment type was fixed form
        return route('myassignment');
    }

    public function assignmentstatus()
    {
        /// testing cron functionality
        //$obj = new AssignmentUser();
        //$obj->updateAssignmentUserStatus();                
        
        /*$subsectionTimeType = 'Minutes';
            $subsectionUserStatuses = AssignmentUser::join('assignment', 'assignment.id','=','assignment_user.assignment_id')
            ->join('assessment', function($query){ 
                $query->on('assessment.id','=','assignment.assessment_id')
                ->where('neverexpires','=','0')
                ->where('totaltime','>','0');
            })
            ->where('isgraded', false)->whereNotNull('starttime')->where('starttime','>', 0)->limit(5)->get();

            if(count($subsectionUserStatuses) > 0){
                $leftOutSection = false;
                foreach($subsectionUserStatuses as $subsectionUserStatus){
                    //getting section start time
                    $sectionStartTime = strtotime($subsectionUserStatus->starttime);
                    //getting current time
                    $currentTime = time();
                    //getting time passed
                    $timePassed = $currentTime - $sectionStartTime;
                    //getting subsection total time
                    $subsectionTotalTime = $subsectionUserStatus->totaltime;
                    if($subsectionTimeType == 'Minutes'){
                        $subsectionTotalTime = $subsectionTotalTime * 60;
                    }
                    else{
                        if($subsectionTimeType == 'Hours'){
                            $subsectionTotalTime = $subsectionTotalTime * 60 * 60;
                        }
                    }
                    if($timePassed >= 0 && $timePassed > $subsectionTotalTime){
                        $assessmentAssignmentUser = AssignmentUser::where('assignment_id', '=', $subsectionUserStatus->assignment_id)
                                  ->where('user_id', $subsectionUserStatus->user_id)->first();
                        $assessmentAssignmentUser->gradeprogress = 'processed';
                        $assessmentAssignmentUser->save();
                        
                        $leftOutSection = true;
                    }
                }

                if($leftOutSection){
                    echo "Processing...\n";
                   // return true;
                }
                else{
                    echo "No Left out Assignment\n";
                   // return false;
                }
            }else{
                echo "No Ungraded Assignment\n";
                //return false;
            }
            */
    }
}
