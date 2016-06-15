<?php namespace App\Modules\Resources\Controllers;

use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\QuestionAnswer;
use Illuminate\Support\Facades\Auth;
use Request;
use Response;
use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Subject;
use App\Modules\Resources\Models\Lesson;
use App\Modules\Resources\Models\Category;
use App\Modules\Resources\Models\Question;
use App\Modules\Resources\Models\QuestionType;
use App\Modules\Resources\Models\Passage;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Admin\Models\User;
use DB;

class AssessmentController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$obj = new Institution();
		$this->institution = $obj;

		$obj = new Subject();
		$this->subject = $obj;

		$obj = new Lesson();
		$this->lesson = $obj;

		$obj = new Category();
		$this->category = $obj;

		$obj = new Question();
		$this->question = $obj;

		$obj = new QuestionType();
		$this->question_type = $obj;

		$obj = new Passage();
		$this->passage = $obj;
		$obj = new Question();
		$this->question = $obj;
		$obj = new Assessment();
		$this->assessment = $obj;


	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;		
		$inst_arr = $this->institution->getInstitutions();	
		$subjects = $this->subject->getSubject();	
		$category = $this->category->getCategory();
		$questions = $this->question->getQuestions();
		$assessment=Assessment::get();
 		$institution_id='';
        return view('resources::assessment.list'  ,compact('assessment','institution_id','inst_arr', 'questions','subjects','category'));
	}

	public function assessmentdel($aid=0)
	{
		if($aid > 0)
		{
			$this->assessment->deleteAssessment($aid);
		}
		return redirect('/resources/assessment');
	}
	public function assessmentcreate(){
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;
		$id = $institution_id = $subject_id = $category_id = 0;
		$inst_arr = $this->institution->getInstitutions();
		$id=Auth::user()->id;
		$user_institution=User::find($id);
		$user_institution_id=$user_institution['institution_id'];
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$questions = $this->question->getQuestions();
		$inst_questions_list=Question::where('institute_id',$user_institution_id)->get();
		$inst_passages_list=Passage::where('institute_id',$user_institution_id)->get();
//		$inst_questions_list=[];
	    return view('resources::assessment.add',compact('inst_passages_list','inst_questions_list','inst_arr', 'id','institution_id','questions','subjects','category'));
	}
	public function assessmentInsert(){

		$post = Input::All();
		if(!isset($post['passageIds'])){
			$post['passageIds']="";
		}
		if(!isset($post['QuestionIds'])){
			$post['QuestionIds']="";
		}
		//dd($post);
  		$messages=[
// 			'subject_id.required'=>'The Subject field is required',
//			'category_id.required'=>'The Category field is required',
//			'lessons_id.required'=>'The Lessons field is required',
//			'institution_id.required'=>'The Institution field is required',
			'QuestionIds.required'=>'The Questions is required',
		];
		$rules = [
			'title' => 'required|unique:assessment,name',
			'header'=>'required',
			'footer'=>'required',
			'begin_instruction'=>'required',
			'end_instruction'=>'required',
			'institution_id' => 'required|not_in:0',
			'category_id' => 'required|not_in:0',
//			'subject_id' => 'required',
// 			'lessons_id' => 'required',
 			'QuestionIds' => 'required',];

		$validator=Validator::make($post,$rules,$messages);
		if ($validator->fails())
		{
			//dd($validator);
			return Redirect::back()->withInput()->withErrors($validator);
		} else
		{
//			$passage_id=[];
			$pass_list=[];
			$Question_ids=$post['QuestionIds'];
//			dd($Question_ids);
			$questions = Question::wherein('id',$post['QuestionIds'])->get();
//			dd($questions);
//			foreach($questions as $s){
//				dd($s['id']);
//
//			}
//			dd('eee');
// 			foreach($Question_ids as $key=>$q){
//				$pass=Question::find($q)->select('passage_id')->get();
//				dd($pass);
//			}
//			dd('e');
//			$passage_Ids=isset( $post['passageIds'] ) ? $post['passageIds'] : 0;
//			foreach($passage_Ids as $pass_id){
//				array_push($passage_id,$pass_id);
//			}
// 			$Question_ids=explode(',',$post['QuestionIds'][0]);
  				$assessment_insert = new Assessment();
 				$assessment_insert->name = $post['title'] ;
			$assessment_insert->header = $post['header'];
			$assessment_insert->footer = $post['footer'];
			$assessment_insert->begin_instruction = $post['begin_instruction'];
			$assessment_insert->end_instruction = $post['end_instruction'];
//				$assessment_insert->institution_id = $post['institution_id'] ;
//				$assessment_insert->category_id = $post['category_id'] ;
//				$assessment_insert->subject_id = $post['subject_id'] ;
//				$assessment_insert->lessons_id = $post['lessons_id'] ;
  				if($assessment_insert->save()){
					//dd("lk");
//					if($passage_Ids){
//						foreach ($passage_Ids as $key => $value) {
//							$assessment_id=$assessment_insert->id;
//							$assessment_question=new AssessmentQuestion();
//							$assessment_question->assessment_id=$assessment_id;
//							$assessment_question->question_id=$value;
//							$assessment_question->save();
//						}
//					}else{
						foreach ($questions as $value) {
						if($value=='')continue;
						$assessment_id=$assessment_insert->id;
						$assessment_question=new AssessmentQuestion();
						$assessment_question->assessment_id=$assessment_id;
						$assessment_question->question_id=$value['id'];
						$assessment_question->passage_id=isset( $value['passage_id'] ) ? $value['passage_id'] : 0;
						$assessment_question->save();
						}
//					}
//					foreach ($passage_id as $key=>$passage_Idss) {
//						$assessment_id=$assessment_insert->id;
//						$assessment_question=new AssessmentQuestion();
//						$assessment_question->assessment_id=$assessment_id;
//						$assessment_question->question_id=$value;
//						$assessment_question->passage_id=$passage_Idss;
//						$assessment_question->save();
//					}
 				}
 			return redirect('/resources/assessment');
		}
 	}
	public function assessmentedit($id=0){
		$question_id_passage=$id;
   		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$questions = $this->question->getQuestions();
		$assessment_details = Assessment::find($id);
 		$question_selected_list=AssessmentQuestion::join('assessment','assessment_question.assessment_id','=','assessment.id')
			->where('assessment_question.assessment_id',$id)
 			->get();
   		$question_tilte_details=[];
 		$ids=[];
		foreach($question_selected_list as $question){
			$question_id=$question['question_id'];
			$question_title=Question::find($question_id);
			array_push($ids,$question_id);
			array_push($question_tilte_details,$question_title);
		}
		$question_title_remove_ids=Question::wherenotin('id',$ids)->get();
		$id = $institution_id = $subject_id = $category_id = 0;
//		$passages_list=Question::join('passage','questions.passage_id','=','passage.id')->where('questions.id',$question_id_passage)->get();
		$passage_question_list=AssessmentQuestion::join('assessment','assessment_question.assessment_id','=','assessment.id')->where('assessment.id','=',$question_id_passage)->get();
		$list_question_passage=[];
		$passages_list=[];
		$questions_list=[];
		foreach($passage_question_list as $passage){
			array_push($passages_list,$passage['passage_id']);
			array_push($questions_list,$passage['question_id']);
		}
		$questions_lists=Question::wherein('id',$questions_list)->get();
		$passages_lists=Passage::wherein('id',$passages_list)->get();
		$passages_list_not=Passage::wherenotin('id',$passages_list)->get();
		return view('resources::assessment.edit',compact('passages_list_not','questions_lists','passages_lists','question_title_remove_ids','passages_list','question_tilte_details','assessment_details','inst_arr','id','institution_id', 'questions','subjects','category'));
 	}
	public function assessmentupdate(){
 		$post = Input::All();
 		$passage_id=[];
  		$messages=[
  			'QuestionIds.required'=>'The Questions is required',
		];
		$rules = [
			'title' => 'required',
 			'QuestionIds' => 'required',];
		$validator=Validator::make($post,$rules,$messages);
		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		} else
		{
//			$passage_Ids=$post['passageIds'];
//			foreach($passage_Ids as $pass_id){
//				array_push($passage_id,$pass_id);
//			}
			$questions = Question::wherein('id',$post['QuestionIds'])->get();
  			$assessment_insert = Assessment::find($post['id']);
 			$assessment_insert->name = $post['title'] ;
 			//delete previous questions-answers
 			$assessment_question=AssessmentQuestion::where('assessment_id',$post['id'])->delete();

// 			foreach ($post['QuestionIds'] as $key => $value) {
// 				if($assessment_insert->save()){
//					// $assessment_question=AssessmentQuestion::where('question_id',$value)->where('assessment_id',$post['id'])->delete();
//					if($value!=""){
//					foreach ($passage_id as $key=>$passage_Idss) {
//					$assessment_question=new AssessmentQuestion();
//					$assessment_question->assessment_id=$post['id'];
//					$assessment_question->question_id=$value;
//					$assessment_question->passage_id=$passage_Idss;
//					$assessment_question->save();
//					}
//					}
//				}
// 			}
			foreach ($questions as $value) {
				if($value=='')continue;
				$assessment_id=$assessment_insert->id;
				$assessment_question=new AssessmentQuestion();
				$assessment_question->assessment_id=$assessment_id;
				$assessment_question->question_id=$value['id'];
				$assessment_question->passage_id=isset( $value['passage_id'] ) ? $value['passage_id'] : 0;
				$assessment_question->save();
			}
			return redirect('/resources/assessment');
 		}
	}

	public function assessmentQst(){
		$post = Input::All();
		$question=$post['questions'];
  		$subjects = $this->question->getassessmentQst($question);
 		return $subjects;
	}
	public function assessmentOldPassage(){
		$post = Input::All();
 		$passage=$post['passages'];
		$subjects = $this->question->getassessmentOldPassage($passage);
		return $subjects;
	}public function assessmentRemoveOldPassage(){
 		$post = Input::All();
  		$passage=$post['passages'];
		$subjects = $this->question->getassessmentRemoveOldPassage($passage);
		return $subjects;
	}
	public function assessmentAppendQst(){
 		$post = Input::All();
		$question=isset( $post['QuestionIds'] ) ? $post['QuestionIds'] : 0;
		$flag=$post['flag'];
		$subjects = $this->question->getassessmentAppendQst($question,$flag);
		return $subjects;
	}
	public function assessmentQstPassage(){
 		$post = Input::All();
  		$passage_id=$post['id'];
		$flag=$post['flag'];
		$question_Ids=isset( $post['QuestionIds'] ) ? $post['QuestionIds'] : 0;
   		$subjects = $this->question->getPassageQst($passage_id,$flag,$question_Ids);
		return $subjects;
	}

	public function assessmentFilter(){
		$post = Input::All();
		$institution=$post['institution'];
		$category=$post['category'];
		$subject=$post['subject'];
		$lessons=$post['lessons'];
		$question = 0;
		if(isset($post['questions']))$question=$post['questions'];
  		$subjects = $this->question->getassessmentFilter($institution,$category,$subject,$lessons,$question);
 		return $subjects;
 	}

 	public function assessmentFilterList(){
 		$post = Input::All();
  		$institution=$post['institution'];
		$category=$post['category'];
		$subject=$post['subject'];
		$lessons=$post['lessons'];
 		$subjects = $this->assessment->getassessmentFilterList($institution,$category,$subject,$lessons);
 		return $subjects;
 	}

	public function questionsListing(){
		return "question listing";
	}
	public function passageListing(){
		return "passage listing";
	}
	public function _renderQbankGrid($questionIds = []) {
		dd('question grid');
		$data = [];
		$addedListOfQuestions = '';
		$data['selectedIds'] = $questionIds;
		$option_model = new Option;
		$question_model = new \App\Modules\Resources\Models\Question;
		$questions_array = $question_model->getQuestionsForPrograms($data);
		if (!empty($questionIds)) {
			$filter['fetch_specific'] = $questionIds;
			$filter['no_limit'] = true;
			$added_questions_array = $question_model->getQuestionsForPrograms($filter);
			$addedListOfQuestions = view('programs::programs.partials._item_access.lists._question_list')->with('child_record', true)->with('questions', $added_questions_array)->render();
		}
		$questions = isset($questions_array) ? $questions_array : [];
		$subjects = $option_model->getOptions('QbankSubjects');
		$question_types = $option_model->getOptions('QuestionTypes', 'Id', null, 'Display', 'asc', ['Fill in the Blank']); //Exclude Question Type 'Fill in the Blank' From Filters
		// Update Option From 'Open Ended Response' To 'OER' In Question Type Filters
		foreach ($question_types as $key => $question) {
			if ($question == 'Open Ended Response') {
				$question_types[$key] = 'OER';
			}
		}
		if (($key = array_search('Selection', $question_types)) !== false) {
			unset($question_types[$key]);
		}
		$specificQbankQuestionGrid = view('programs::programs.partials._item_access._specific_qbank_questions', compact('addedListOfQuestions'))
			->nest('questions_filters', 'programs::programs.partials._item_access._qbank_question_filters', compact('subjects', 'question_types'))
			->nest('questions_list', 'programs::programs.partials._item_access.lists._question_list', compact('questions'))
			->render();
		return $specificQbankQuestionGrid;
	}

	public function _renderPassagesGrid($passagesIds = []) {
		dd('passage grid');
		$data = [];
		$addedListOfPassages = '';
		$data['selectedIds'] = $passagesIds;
		$subjects = Option::getOption('QbankSubjects', 'Id', 'All', 'Display');
		$passageObj = new \App\Modules\Resources\Models\Passage();
		if (!empty($passagesIds)) {
			$filter['fetchSpecific'] = $passagesIds;
			$filter['no_limit'] = true;
			$added_passageList = $passageObj->getListByFiltersForPrograms($filter);
			$addedListOfPassages = view('programs::programs.partials._item_access.lists._passage_list')->with('child_record', true)->with('passageList', $added_passageList)->render();
//            echo '<pre>'; print_r($added_questions_array); die;
		}
		$passageList = $passageObj->getListByFiltersForPrograms($data);
		$specificQbankPassagesGrid = view('programs::programs.partials._item_access._specific_qbank_passages', compact('subjects', 'addedListOfPassages'))
			->nest('passages_list', 'programs::programs.partials._item_access.lists._passage_list', compact('passageList'))
			->render();
		return $specificQbankPassagesGrid;
	}


	 public function zipDownload($assessmentId) {
        
        $assessment = Assessment::find($assessmentId);
        //$s3 = new \App\Models\S3;
        
        if (!empty($assessment)) {
            
            // generate file paths
            $filesToDownload = [];
            $files = explode(',', $assessment->print_view_file);


            foreach ($files as $file) {
                
                //make sure the file exists
               // if($s3->fileExists($file, 'assessment_fixedform_path')) {
                    $filesToDownload[] = $file;
               // }
            }

            // dd($filesToDownload);
            if (!empty($filesToDownload)) {
                $data['files'] = $filesToDownload;
                $data['fileBasePath'] = public_path('data/assessment_pdf/');
                $data['Download'] = true;
                // create zip, download then delete it
                makeZipFile($data);                
            } else {
                return redirect()->back()->with('success', 'Files not found');
            }
        }        
                
    }


     /**
     * getPrintAnswerKeyCSV | A get method for download the Answer-Key into CSV form
     * 
     * @access public
     * @param Request $request 
     * @return Response::json
     */
    public function getPrintAnswerKeyCSV(Request $request) {
       
    	//dd( $request);
        $pPath = public_path('/data/tmp/');
        $pUrl = url('/data/tmp/');
        $fileName = 'print_answer_key_' . time();
        $fExt = 'csv';
$post = Input::All();		
$assignmentId = $post['assignmentId'];
//dd($assignment);
       //echo  $assignmentId = $request->get('assignmentId', 0);
        $aaModel = Assignment::find($assignmentId);
        if ($aaModel) {
            $dataset = $aaModel->getAssignmentAnswerKeys();
            $response = ['success' => true, 'fileUrl' => array_to_csv_download($dataset)];
        } else {
            $response = ['success' => false, 'message' => 'Assignment does not exists.'];
        }
        return Response::json($response);
    }
}
