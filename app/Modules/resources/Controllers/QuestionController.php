<?php namespace App\Modules\Resources\Controllers;

use App\Modules\Assessment\Models\QuestionUserAnswer;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Resources\Models\QuestionAnswer;
use Illuminate\Support\Facades\Auth;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;
use DB;
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
use Storage;
use Illuminate\Http\JsonResponse;
use Response;
use App\Modules\Admin\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Modules\Admin\Models\RoleUser;
// use Request;
use Illuminate\Http\Request;
use Session;


class QuestionController extends BaseController {

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

		$obj = new QuestionUserAnswer();
		$this->questionuser = $obj;

		$obj = new QuestionType();
		$this->question_type = $obj;

		$obj = new Passage();
		$this->passage = $obj;

		$obj = new Lesson();
		$this->lesson = $obj;
		$errorArray=[];

  	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{

	}



	public function question()
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;
		if (getRole() == "student") {
     return view('permission');
    }elseif(getRole() == "administrator")
           {
           	$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$questions = $this->question->getQuestions();
		$questions_type=$this->question_type->getQuestionTypes();
		$passages=$this->passage->getPassage();
		$lessons = $this->lesson->getLesson();
		$list=Question::join('question_type','questions.question_type_id','=','question_type.id')
				->leftjoin('passage','questions.passage_id','=','passage.id')
				//->leftjoin('institution','questions.institute_id','=','institution.id')
				->select('questions.id as qid','questions.title as question_title','passage.title as passage_title','question_type.qst_type_text as question_type','questions.status as status')
				->orderby('qid')
				//->groupBy('institution')
				->get();
		//dd($list);
			}else{
				$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$questions = $this->question->getQuestions();
		$questions_type=$this->question_type->getQuestionTypes();
		$passages=$this->passage->getPassage();
		$lessons = $this->lesson->getLesson();
				$list=Question::join('question_type','questions.question_type_id','=','question_type.id')
				->where("questions.institute_id", Auth::user()->institution_id)
				->leftjoin('passage','questions.passage_id','=','passage.id')
				//->leftjoin('institution','questions.institute_id','=','institution.id')
				->select('questions.id as qid','questions.title as question_title','passage.title as passage_title','question_type.qst_type_text as question_type','questions.status as status')
				->orderby('qid')
 				->get();
		//dd($list);
				          

			}
		return view('resources::question.list',compact('inst_arr', 'questions','subjects','category','lessons','questions_type','passages','list'));
	
	}
	public function questionlist()
	{

		$post = Input::All();
		//dd($post);
		$institution=$post['institution'];
		$category=$post['category'];
		$subject=$post['subject'];
		$lessons=$post['lessons'];
		$questions=$post['question_type'];
		//dd($questions);
		$obj=Question::join('question_type','questions.question_type_id','=','question_type.id')
				->leftjoin('passage','questions.passage_id','=','passage.id');

		if($institution > 0){
			$obj->where("questions.institute_id", $institution);
		}
		if($category > 0){
			$obj->where("questions.category_id", $category);
		}
		if($subject > 0){
			$obj->where("questions.subject_id", $subject);
		}
		if($lessons > 0){
			$obj->where("questions.lesson_id", $lessons);
		}
		if($questions=$post['question_type']){
			$obj->where("question_type.qst_type_text",$questions);
		}
		



		$list=	$obj->select('questions.id as qid','questions.title as question_title','passage.title as passage_title','question_type.qst_type_text as question_type','questions.status as status')
				->orderby('qid')
				->get();
		//$question_list = $this->question->getQuestionFilter($institution);
				        
//dd($list);
		return $list;

	}

	public function questionadd()
	{
		//dd(Input::all());
		//dd(\Session::get('question_type'));
		if (getRole() == "student") {
     return view('permission');
    }else{
		$question_type="";
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$lessons = $this->lesson->getLesson();
		$passage = $this->passage->getPassage();
		$qtypes = $this->question_type->getQuestionTypes();

		$id = $institution_id = $subject_id = $category_id = 0;
		$name = '';

		// old answers listing
		$oldAnswers =$answerIds=$explanation=$is_correct= array(); //
		if((\Session::get('answer_textarea')))
			$oldAnswers=\Session::get('answer_textarea');
		if((\Session::get('answerIds'))){
			$answerIds=\Session::get('answerIds');}
		if((\Session::get('explanation')))
			$explanation=\Session::get('explanation');
		if((\Session::get('is_correct'))){
			$is_correct=\Session::get('is_correct');
		}
		if((\Session::get('question_type'))){
			//dd();
			$type_id = \Session::get('question_type');
			$question_type=QuestionType::find($type_id)->qst_type_text;
			
		}
		$answersListing = view('resources::question.partial.listing_answers', compact('oldAnswers','answerIds','is_correct','explanation','question_type', 'title'));
		//dd($answersListing);

		$questions = Question::get()->toArray();
		        // \Session::flash('flash_message','Information saved successfully.');

		return view('resources::question.edit',compact('id','institution_id','name','inst_arr', 'subjects','lessons','subject_id','category','passage','category_id', 'qtypes', 'answersListing','questions','question_type','title'));
	}
	}
public function questionedit($id = 0)
	{
		if (getRole() == "student") {
     return view('permission');
    }else{
		$questions = Question::where('id',$id)->get()->toArray();
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject($questions[0]['category_id']);
		$category = $this->category->getCategory($questions[0]['institute_id']);
		$lessons = $this->lesson->getLesson($questions[0]['subject_id']);
        $passage = $this->passage->getPassage1($questions[0]['passage_id']);
		$qtypes = $this->question_type->getQuestionTypes();
		if(isset($id) && $id > 0)
		{
			$obj = $this->question->find($id);
			$id = $obj->id;
			$institution_id = $obj->institution_id;
			$subject_id = $obj->subject_id;
			$category_id = $obj->category_id;
			$name = $obj->name;
			$status = $obj->status;
		}
		else
		{
			$id = $institution_id = $subject_id = $category_id = 0;
			$name = '';
		}
		$oldAnswers=QuestionAnswer::join('questions','question_answers.question_id','=','questions.id')
				->where('question_answers.question_id',$id)
				->select('questions.title','question_answers.id','question_answers.ans_text','question_answers.is_correct','question_answers.order_id','question_answers.explanation')
				->get()->toArray();
				//dd($oldAnswers);
//$question_type=Question::join('question_type','question.question_type_id','=','question_type')
				$question_type_id=Question::find($id)->question_type_id;
				$question_type=QuestionType::find($question_type_id)->qst_type_text;
				if((\Session::get('question_type'))){
					$type_id = \Session::get('question_type');
					$question_type=QuestionType::find($type_id)->qst_type_text;
				}
				//dd($question_type);
		$answersLisitng = view('resources::question.partial.edit_listing_answers', compact('oldAnswers','question_type'));
		//dd($answersLisitng);
        // \Session::flash('flash_message','Information saved successfully.');

		return view('resources::question.question_edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','passage','category_id','questions', 'lessons', 'qtypes', 'oldAnswers','answersLisitng','question_type','title','status'));
	}


//
//   		return view('resources::question.question_edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','category_id','passage','qtypes'))
//			->nest('answersLisitng', 'resources::question.partial.listing_answers', compact('oldAnswers'));
	}
	public function questionupdate($id = 0)
	{
		$post = Input::All();
		//dd($post);
		// dd($post['question_type']);
		$messages=[
				// 'answerIds.required'=>'The Answer field is required',
				'subject_id.required'=>'The Subject field is required',
				'category_id.required'=>'The Category field is required',
				'lessons_id.required'=>'The Lessons field is required',
				'institution_id.required'=>'The Institution field is required',
		];
		$rules = [
				'institution_id' => 'required|not_in:0',
				'category_id' => 'required|not_in:0',
				'subject_id' => 'required|not_in:0',
				'lessons_id' => 'required|not_in:0',
				'question_type' => 'required|not_in:0',
				'question_title' => 'required',
				// 'answerIds' => 'required',
				'question_textarea' => 'required',
				// 'answer_textarea' =>'required'
				];

		if ($post['id'] > 0)
		{
			$rules['question_title'] = 'required|min:3|unique:question,name,' . $post['id'];
		}
		$check_corret_answer = array();
		if($post['ans_flg']>0){
			$check_corret_answer = $post['is_correct'];

			// 		if (in_array("true", $check_corret_answer)<1 && $post['question_type']==2){
			//			return Redirect::back()->withInput()->withErrors('Atleast one answer is required correct.');
			// 		}
			if($post['question_type']==2){

				$counts = array_count_values($check_corret_answer);

				if(array_key_exists("true", $counts)){
					$tmp_cnt =  $counts['true'];
				}else{
					return Redirect::back()->withInput()->withErrors('Atleast one correct answer is required')->with('answer_textarea',$post['answer_textarea'])->with('answerIds',$post['answerIds'])->with('is_correct',$post['is_correct'])->with('explanation',$post['explanation']);
				}
			}
			if($post['question_type']==1){
				$counts = array_count_values($check_corret_answer);
				if(array_key_exists("true", $counts)){
					$tmp_cnt =  $counts["true"];
					//			dd($tmp_cnt);
					if($tmp_cnt>=2){

					}else{
						return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required')->with('answer_textarea',$post['answer_textarea'])->with('answerIds',$post['answerIds'])->with('is_correct',$post['is_correct'])->with('explanation',$post['explanation'])->with('question_type',$post['question_type']);
					}
				}else{
					return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required')->with('answer_textarea',$post['answer_textarea'])->with('answerIds',$post['answerIds'])->with('is_correct',$post['is_correct'])->with('explanation',$post['explanation'])->with('question_type',$post['question_type']);
				}
			}
			if ($post['question_type']==1 && count($post['answerIds']) < 2)
			{
				return Redirect::back()->withInput()->withErrors('The Atleast Two Answers are required')->with('answer_textarea',$post['answer_textarea'])->with('answerIds',$post['answerIds'])->with('is_correct',$post['is_correct'])->with('explanation',$post['explanation'])->with('question_type',$post['question_type']);
			}

			foreach ($post['answer_textarea'] as $key => $value) {
				if(trim($value)==''){
					return Redirect::back()->withInput()->withErrors('The Answers text is required')->with('answer_textarea',$post['answer_textarea'])->with('answerIds',$post['answerIds'])->with('is_correct',$post['is_correct'])->with('explanation',$post['explanation'])->with('question_type',$post['question_type']);
				}
			}
		}

		$validator=Validator::make($post,$rules,$messages);
		if(!isset($post['answer_textarea'])){
			$post['answer_textarea']=array();
			$post['answerIds']=array();
			$post['is_correct']=array();
			$post['explanation']=array();
		}

		if ($validator->fails())
		{	
			if($post['question_type']==3){
				return Redirect::back()->withInput()->withErrors($validator)->with('answer_textarea',$post['answer_textarea']);
			}else{
			return Redirect::back()->withInput()->withErrors($validator)->with('answer_textarea',$post['answer_textarea'])->with('answerIds',$post['answerIds'])->with('is_correct',$post['is_correct'])->with('explanation',$post['explanation'])->with('question_type',$post['question_type']);
			}
		} else
		{
			$params = Input::All();
			$this->question->updateQuestion($params);
        \Session::flash('flash_message','Information saved successfully.');

			return redirect('/resources/question');
		}
	}
	public function questionSubmit(){
		$post = Input::All();
		// dd($post);
		$messages=[
				// 'answerIds.required'=>'The Answer field is required',
				'subject_id.required'=>'The Subject field is required',
				'category_id.required'=>'The Category field is required',
				'institution_id.required'=>'The Institution field is required',
		];
		$rules = [
				'institution_id' => 'required|not_in:0',
				'category_id' => 'required|not_in:0',
				'subject_id' => 'required',
				'question_type' => 'required',
				'question_title' => 'required',
				'question_textarea' => 'required',
				// 'answerIds' =>'required'
		];
		if($post['question_type']==3){
			$post['answer_textarea']=array();
			$post['answerIds']=array();
			$post['is_correct']=array();
			$post['explanation']=array();
		}
		$check_corret_answer = array();
		// if($post['ans_flg']>0)
		{
			$check_corret_answer = $post['is_correct'];

			if($post['question_type']==2){

				$counts = array_count_values($check_corret_answer);

				if(array_key_exists("true", $counts)){
					$tmp_cnt =  $counts['true'];
				}else{
					return Redirect::back()->withInput()->withErrors('Atleast one correct answer is required')->with('question_type',$post['question_type']);
				}
			}
			if($post['question_type']==1){
				$counts = array_count_values($check_corret_answer);
				if(array_key_exists("true", $counts)){
					$tmp_cnt =  $counts['true'];
					if($tmp_cnt>=2){

					}else{
						return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required')->with('question_type',$post['question_type']);
					}
				}else{
					return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required')->with('question_type',$post['question_type']);
				}
			}
			if ($post['question_type']==1 && count($post['answerIds']) < 2)
			{
				return Redirect::back()->withInput()->withErrors('The Atleast Two Answers are required')->with('question_type',$post['question_type']);
			}

			foreach ($post['answer_textarea'] as $key => $value) {
				if(trim($value)==''){
					return Redirect::back()->withInput()->withErrors('The Answers text is required')->with('question_type',$post['question_type']);
				}
			}
		}

		$validator=Validator::make($post,$rules,$messages); 
		if ($post['question_type']==1 && count($post['answerIds']) < 2)
		{
			return Redirect::back()->withInput()->withErrors('The Atleast Two Answers is required')->with('question_type',$post['question_type']);
		}
		if ($validator->fails())
		{	
			if($post['question_type']==3){
				return Redirect::back()->withInput()->withErrors($validator)->with('answer_textarea',$post['answer_textarea'])->with('question_type',$post['question_type']);
			}else{
			return Redirect::back()->withInput()->withErrors($validator);
			}
		} else
		{
			$params = Input::All();
			$questions = Question::where('id',$params['id'])->get()->toArray();
			if($questions){
				if($params['question_type']==3){
					$params['answer_textarea']=array();
					$params['answerIds']=array();
					$params['is_correct']=array();
					$params['explanation']=array();
				}
				$obj=Question::find($params['id']);
				$obj->title = $params['question_title'];
				$obj->qst_text = $params['question_textarea'];
				$obj->question_type_id = $params['question_type'];
				$obj->subject_id = $params['subject_id'];
				$obj->lesson_id = $params['lessons_id'];
				$obj->passage_id = isset($params['passage'])?$params['passage']:0;
				$obj->institute_id = $params['institution_id'];
				$obj->status = $params['status'];
				if($obj->save());{
					$explanation = $params['explanation'];
					$is_correct = $params['is_correct'];
					foreach ($params['answer_textarea'] as $key => $value) {

						$answer = new QuestionAnswer();
						if (isset($params['answerIds'][$key]) && !empty($params['answerIds'][$key])) {
							$answer = QuestionAnswer::find($params['answerIds'][$key]);
							if (empty($answer)) {
								$answer = new QuestionAnswer();
							}
						}
						$last_id=$obj->id;
						$answer->question_id = $last_id;
						$answer->ans_text = $value;
						$answer->explanation = $explanation[$key];
						$answer->order_id = ($key+1);
						$answer->is_correct = (($is_correct[$key] == "true") ? "YES" : "NO");
						$answer->save();

					}
				}
			}else{

			}
			        \Session::flash('flash_message','Information updated successfully.');

			return redirect('/resources/question');
		}
	}


	public function questionview($id = 0)
	{
       if (getRole() == "student") {
     return view('permission');
    }else{
		if(isset($id) && $id > 0)
		{
			$question = $this->question->getDetails($id);
		}
		else
		{
			$question = Input::All();

		}

		$qstn=Question::where('id',$id)->get()->toArray();
		//dd($question);
		/*return view('resources::question.question_view',compact('question'));*/


		$oldAnswers=QuestionAnswer::join('questions','question_answers.question_id','=','questions.id')
				->where('question_answers.question_id',$id)
				->select('questions.title','question_answers.id','question_answers.ans_text','question_answers.is_correct','question_answers.order_id','question_answers.explanation')
				->get()->toArray();


		return view('resources::question.question_view',compact('question','oldAnswers','qstn'));
	}
	}

	
	public function questionFilter(){
		$post = Input::All();
		$institution=$post['institution'];
		$obj=Question::join('question_type','questions.question_type_id','=','question_type.id')
				->leftjoin('passage','questions.passage_id','=','passage.id');

		if($institution > 0){
			$obj->where("questions.institute_id", $institution);
		}
		/*if($category > 0){
			$obj->where("category_id", $category);
		}
		if($subject > 0){
			$obj->where("subject_id", $subject);
		}
		if($lessons > 0){
			$obj->where("lesson_id", $lessons);
		}*/

		$list=	$obj->select('questions.id as qid','questions.title as question_title','passage.title as passage_title','question_type.qst_type_text as question_type')
				->orderby('qid')
				->get();
		//$question_list = $this->question->getQuestionFilter($institution);
				        \Session::flash('flash_message','Information saved successfully.');

		return $list;
	}

	public function questiondelete($qid){
		/*if($qid > 0)
		{
			$this->question->deleteQuestions($qid);
		}*/
		$qus=AssessmentQuestion::where('question_id',$qid)->count();
		//dd($les);
		if ($qus == null ) {
			question::find($qid)->delete();
			\Session::flash('flash_message', 'delete!');

			return redirect('/resources/question');

		} else {
			\Session::flash('flash_message_failed', 'Can not Delete this question.');
			return Redirect::back();

		}
		//return redirect('/resources/question');
	}

	public function categoryList($id){
		$category=	Institution::join('category','institution.id','=','category.institution_id')
				->where('institution.id','=',$id)
				->select('category.id','category.name')
				->get();

		return $category;
	}
	public function subjectList($id){
		$id=explode(',',$id);
		$subject=	Category::join('subject','category.id','=','subject.category_id')
				->whereIn('category.id',$id)
				->select('subject.id','subject.name')
				->get();
		return $subject;
	}
	public function lessonsList($id){
		$id=explode(',',$id);
		//dd($id);
		$lessons=	Lesson::join('subject','lesson.subject_id','=','subject.id')
				->whereIn('subject.id',$id)
				->select('lesson.id','lesson.name')
				->get();
		return $lessons;
	}
	public function passageList($id){
		$id=explode(',',$id);
		//dd($id);
		$passage=DB::table('passage')->whereIn('lesson_id',$id)->where('status', '=', 1)
				->select('id','title')
				->get();
				//dd($passage);
		return $passage;
	}
	public function questiontype($idd){
		$post=Input::all();

		$id=explode(',',$idd);
		//dd($id);
		$passage_Ids=isset( $post['passages'] ) ? $post['passages'] : [0];
		$questiontype['question_type']= Question::join('lesson','questions.lesson_id','=','lesson.id')
				->join('question_type','questions.question_type_id','=','question_type.id')
				
				->select('question_type.qst_type_text','questions.question_type_id','questions.title')
				->groupBy('question_type.qst_type_text')
				->get();
						//dd($questiontype);

		$pass=Passage::join('questions','questions.passage_id','=','passage.id')->whereIn('passage.lesson_id',$id);
		//$pass->whereIn('lesson_id',$id);
		//if($passage_Ids > 0){
			$pass->wherenotin("passage.id", $passage_Ids);
		//}
		$questiontype['passages']=$pass->select('passage.id as pid','passage.title as passage_title')->where('passage.status','=',1)->groupBy('pid')->orderby('pid')->get();
		//dd($questiontype);
		//$questiontype['passages']=\DB::table('passage')->selectRaw('id as pid, title as passage_title  where lesson_id in('.$idd.') and where id not in('.implode(',',$passage_Ids).')')->get();
		return $questiontype;
	}
	function launchFileBrowser($bucket = '')
	{

		if (getenv('s3storage'))
		{
			$s3Client = \Storage::disk('s3')->getDriver()->getAdapter()->getClient();
			dd($s3Client);
			$path = 'rdia/' . env('host_name', 'dev') . '/data/' . $bucket . '/';
			// $path = 'rdia/production/data/message-attachments/';
			$objects = $s3Client->getListObjectsIterator(array(
					'Bucket' => 'aacontent',
					'Prefix' => $path
			));
			$items = [];
			foreach ($objects as $index => $object) {
				$items[$index]['item_name'] = pathinfo($object['Key'])['basename'];
				$items[$index]['item_path'] = 'https://aacontent.s3-us-west-2.amazonaws.com/' . $object['Key'];
				$items[$index]['item_size'] = $object['Size'];
			}
		}
		else {
			//$path = 'rdia/' . env('host_name', 'dev') . '/data/' . $bucket . '/';

			// $items = [];

			$directory = public_path('data/images'  . '/');
			$files = glob($directory . '*.png');
			//dd($files);
			// $fileName = '';
			$items = [];
			$index = 0;
			foreach($files as $a) {
				$items[$index]['item_name'] = basename($a);

				$items[$index]['item_path'] = asset('data/images'. '/')."/".basename($a);
				$items[$index++]['item_size'] =filesize($a);
				//dd($items);
			}
			// dd($items);

		}
		return view('general.launch_file_browser',compact('items','bucket'));
	}
	public function fileBrowserUploadFile(Request $request){
		$fileName = '';
		$file = Request::file('item');
//		dd($file);
		$bucket = Request::get('bucket', 'question_attachments');
		$bucket = $bucket == 'message-attachments'?'messages_path':'question_attachments';
		$extension = $file->getClientOriginalExtension();
		$name = $file->getClientOriginalName();
		$size = $file->getSize();
		$fileName = \Auth::user()->id.'_'.$name;
		$destinationPath = public_path('data/images/');
		//dd($destinationPath);
		if($file->move($destinationPath, $fileName)){
// Move the file to S3
			$orignalFilePath = $destinationPath.$fileName;
			//dd($orignalFilePath);

			if(getenv('s3storage'))
			{
				$s3 = new \App\Models\S3();
				$s3->uploadByPath( $orignalFilePath, $bucket);
				//dd($s3);
				$orignal_pic_url = $s3->getFileUrl($fileName, $bucket);
				//dd($orignal_pic_url);
				$response['status'] = 'success';
				$response['item_name'] = $fileName;
				$response['item_path'] = $orignal_pic_url;
				$response['item_size'] = $size;
// if(\Session::has('items-browsed')){
// $items = \Session::get('items-browsed');
// $lstIndex = count($items);
// $items[$lstIndex]['item_name'] = $fileName;
// $items[$lstIndex]['item_path'] = $orignal_pic_url;
// $items[$lstIndex]['size'] = $size;
// $items[$lstIndex]['last_modified'] = date('Y-m-d H:i:s a');
// \Session::put('items-browsed',$items);
// \Session::save();
// }
				unlink($orignalFilePath);
			}
			else
			{
				$response['status'] = 'success';
				$response['item_name'] = $fileName;
				$response['item_path'] = asset('data/images/').'/'.$fileName;
				$response['item_size'] = $size;
			}
		}else{
			$response['status'] = 'error';
		}
		return $response;
	}
	public function questionBulkUpload()
	{
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		$qtypes = $this->question_type->getQuestionTypes();
		return view('resources::question.question_bulkupload',compact('inst_arr','qtypes'));
	}

	public function questionBulkTemplate(Request $request)
	{
 		$userType = $request->input('userType');
		$institution_id = $request->input('institution_id');
		//dd($request);
		$category_id=$request->input('category_name');
		$subject_id=$request->input('subject_name');
		$lesson_id=$request->input('lesson_name');
		$question_type=$request->input('question_type');
		//dd($category_id.$subject_id.$lesson_id.$question_type);
		$data=$request->Input();
		//dd($institution_id);
 		$filename = 'question_template_' . date('Y-m-d') . '.xls';

		$save = $this->question->questionBulkTemplate($data,$filename, $userType,$institution_id, false, true);
		if ($save == null) {
 			return Response::json(array('file_name' => "../data/tmp/$filename"));
		} else {
 			return Response::json(array('file_name' => false));
		}
	}

	public function questionBulkUploadFile(Request $request) {

 		$institutionId = $request->input('institutionId');

		$userType = $request->input('userType');
		

		if (empty($institutionId)) {
			$institutionId = Auth::user()->institution_id;
		}

		$uploadSuccess = false;
		$file = $request->file('file');
		$destFileName = '';

		$fileinfo = ['file' => $file, 'extension' => strtolower($file->getClientOriginalExtension())];

		$validator = \Validator::make($fileinfo, ['file' => 'required|max:5000', 'extension' => 'required|in:xls']);

		if ($validator->fails()) {
			$errorArray = array('status' => 'error', 'msg' => 'Invalid file uploaded');
			return json_encode($errorArray);
		}

		if ($file) {
			$extension = $file->getClientOriginalExtension();
			if ($extension != 'xls') {
				$error = array('status' => 'error', 'msg' => 'Upload valid file type.');
				return json_encode($error);
			}
			// Moving the uploaded image to respective directory
			$destPath = public_path() . '/data/tmp';
			$destFileName = str_random(6) . '_' . $file->getClientOriginalName();
			$uploadSuccess = $file->move($destPath, $destFileName);
		} else {
			$errorArray = array('status' => 'error', 'msg' => 'File does not exist');
			return json_encode($errorArray);
		}

		$this->errorArray=array();
		return  $some=$this->fileupload($destPath,$destFileName, $institutionId, $userType);
		// return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
	}
	public function fileupload($destPath,$destFileName, $institutionId){
		//dd($institutionId);
		
		//dd($role_id );
		$uploadSuccess = false;
		$orignalHeaders = ['institution','category','subject','lessons','question_type','question_tittle','question_text','passage','answer_text1','order_id1','is_correct1','explanation1',
		'answer_text2','order_id2','is_correct2','explanation2',
		'answer_text3','order_id3','is_correct3','explanation3',
		'answer_text4','order_id4','is_correct4','explanation4',
		'answer_text5','order_id5','is_correct5','explanation5','status'];
		$getFirstRow = Excel::load($destPath . '/' . $destFileName)->first()->toArray();
	
		$uploadedFileHeaders = [];
		if(!empty($getFirstRow[0])){
			$uploadedFileHeaders = array_keys(array_only($getFirstRow[0], $orignalHeaders));
		}
		// dd($getFirstRow[0]);
		//dd($orignalHeaders);
		$headerDifference = array_diff($orignalHeaders, $uploadedFileHeaders);
		//dd($headerDifference);
		if(!empty($headerDifference)){
			$error = array('status' => 'error', 'msg' => 'Invalid file.');
			return $error;
		}

		//        echo '<pre>'; print_r($getFirstRow); die;
		// if ($uploadSuccess != false) {
		$errorArray = array();
		//                    try{
		$output = Excel::load($destPath . '/' . $destFileName, function($results) use ($institutionId) {
			$phpExcel = $results->setActiveSheetIndex(1);
			$fileType = $phpExcel->getCell('D1')->getValue();
			$phpExcel = $results->setActiveSheetIndex(0);
			$rowCount = $phpExcel->getHighestRow();
			$emptyFile = true;
			if ($rowCount > 1) {

				$phpExcel = $results->setActiveSheetIndex(0);
				$firstSheet = $results->get()[0];
		
				//dd($firstSheet);
				foreach ($firstSheet as $key => $row) {
					$arrayCol = $row->toArray();
					//Ceck Empty Row
					$rowSize = 0;
					foreach ($arrayCol as $cell) {
						$rowSize += strlen($cell);
					}
					//echo $rowSize;
					if ($rowSize == 0) {
						continue;
					}

					//Check Empty Row End
					$emptyFile = false;
					$error = Question::validateBulUpload($fileType, $row, $key + 2);
  					if (count($error) > 0) {

 
						array_push($this->errorArray, $error);

						// array_push($errorArray, $status);
					} else {
						//dd($row);
						Question::createBulkQuestion($row);
					}
				}
				// dd($row);

			} else {

				$this->errorArray[] = array('Row #' => '', 'Error Description' => 'File is empty');
			}
			if ($emptyFile) {
				$this->errorArray[] = array('Row #' => '', 'Error Description' => 'File is empty');
			}
		});
		//                    }catch(\Exception $e) {
		//                        $this->errorArray[] = array('Row #'=>'','Error Description'=>'You have tried to upload a file with invalid fields.');
		//                    }
		// dd($this->errorArray);
		if (count($this->errorArray) > 0) {
 			$er=$this->errorArray;
  			Excel::create('errorlog_' . explode('.', $destFileName)[0], function($excel) use($er) {
				$excel->sheet('error_log', function($sheet) use($er) {
  					foreach ($er as $key => $value) {
 					$sheet->fromArray($value);	
  					}
					 					
				});
			})->store('xls', public_path('data/tmp'), true);

			return $errorArray = array('status' => 'error', 'msg' => 'Please download error log', 'error_log' => url().'/data/tmp/errorlog_' . $destFileName);


			//return json_encode($errorArray);

		} else {

			Session::flash('success', 'File uploaded successfully.');
			return $sucessarray = array('status' => 'success', 'msg' => 'Uploaded Successfully');
			// return json_encode($sucessarray);
		}
		//}


	}
}
