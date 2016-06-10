<?php namespace App\Modules\Resources\Controllers;

use App\Modules\Resources\Models\QuestionAnswer;
use Illuminate\Support\Facades\Auth;

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

		$obj = new QuestionType();
		$this->question_type = $obj;

		$obj = new Passage();
		$this->passage = $obj;

		$obj = new Lesson();
		$this->lesson = $obj;
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
		$inst_arr = $this->institution->getInstitutions();	
		$subjects = $this->subject->getSubject();	
		$category = $this->category->getCategory();
		$questions = $this->question->getQuestions();
		$questions_type=$this->question_type->getQuestionTypes();
		$passages=$this->passage->getPassage();
		$lessons = $this->lesson->getLesson();
		$list=Question::join('question_type','questions.question_type_id','=','question_type.id')
			->leftjoin('passage','questions.passage_id','=','passage.id')
			->select('questions.id as qid','questions.title as question_title','passage.title as passage_title','question_type.qst_type_text as question_type')
			->orderby('qid')
			->get();
			//dd($list);
        return view('resources::question.list',compact('inst_arr', 'questions','subjects','category','lessons','questions_type','passages','list'));
	}

	public function questionadd()
	{		
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$lessons = $this->lesson->getLesson();
		$passage = $this->passage->getPassage();
		$qtypes = $this->question_type->getQuestionTypes();

		$id = $institution_id = $subject_id = $category_id = 0;
		$name = '';

		// old answers listing
		$oldAnswers = array(); //$question->answers->toArray();
        $answersLisitng = view('resources::question.partial.listing_answers', compact('oldAnswers'));
		$questions = Question::get()->toArray();
 		return view('resources::question.edit',compact('id','institution_id','name','inst_arr', 'subjects','lessons','subject_id','category','passage','category_id', 'qtypes', 'answersLisitng','questions'));
	}

	public function questionupdate($id = 0)
	{
		$post = Input::All();
		$messages=[
			'answerIds.required'=>'The Answer field is required',
			'subject_id.required'=>'The Subject field is required',
			'category_id.required'=>'The Category field is required',
			'lessons_id.required'=>'The Lessons field is required',
			'institution_id.required'=>'The Institution field is required',
      		];
		$rules = [
			'institution_id' => 'required|not_in:0',
			'category_id' => 'required|not_in:0',
			'subject_id' => 'required',
			'lessons_id' => 'required',
			'question_type' => 'required',
			'question_title' => 'required',
 			'answerIds' => 'required',
 			'question_textarea' => 'required',];

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
					return Redirect::back()->withInput()->withErrors('Atleast one correct answer is required');
				}
			}
			if($post['question_type']==1){
				$counts = array_count_values($check_corret_answer);				
				if(array_key_exists("true", $counts)){
					$tmp_cnt =  $counts['true'];
					if($tmp_cnt>=2){

					}else{
						return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required');
					}
	 			}else{
					return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required');
				}
			}
			if ($post['question_type']==1 && count($post['answerIds']) < 2)
			{
				return Redirect::back()->withInput()->withErrors('The Atleast Two Answers are required');
	 		}

	 		foreach ($post['answer_textarea'] as $key => $value) {
				if(trim($value)==''){
					return Redirect::back()->withInput()->withErrors('The Answers text is required');
		 		}
		 	}
	 	}

		$validator=Validator::make($post,$rules,$messages);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		} else
		{
			$params = Input::All();
			$this->question->updateQuestion($params);

			return redirect('/resources/question');
		}
 	}
	public function questionSubmit(){
		$post = Input::All();
		$messages=[
			'answerIds.required'=>'The Answer field is required',
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
			'question_textarea' => 'required',];

		$check_corret_answer = array();
		//if($post['ans_flg']>0)
		{
				$check_corret_answer = $post['is_correct'];				
	
			if($post['question_type']==2){

				$counts = array_count_values($check_corret_answer);
				
				if(array_key_exists("true", $counts)){
					$tmp_cnt =  $counts['true'];
				}else{
					return Redirect::back()->withInput()->withErrors('Atleast one correct answer is required');
				}
			}
			if($post['question_type']==1){
				$counts = array_count_values($check_corret_answer);				
				if(array_key_exists("true", $counts)){
					$tmp_cnt =  $counts['true'];
					if($tmp_cnt>=2){

					}else{
						return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required');
					}
	 			}else{
					return Redirect::back()->withInput()->withErrors('Atleast two correct answers are required');
				}
			}
			if ($post['question_type']==1 && count($post['answerIds']) < 2)
			{
				return Redirect::back()->withInput()->withErrors('The Atleast Two Answers are required');
	 		}

	 		foreach ($post['answer_textarea'] as $key => $value) {
				if(trim($value)==''){
					return Redirect::back()->withInput()->withErrors('The Answers text is required');
		 		}
		 	}
	 	}

		$validator=Validator::make($post,$rules,$messages);
		if ($post['question_type']==1 && count($post['answerIds']) < 2)
		{
			return Redirect::back()->withInput()->withErrors('The Atleast Two Answers is required');
		}
		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		} else
		{
			$params = Input::All();
  			$questions = Question::where('id',$params['id'])->get()->toArray();
			if($questions){
				$obj=Question::find($params['id']);
 				$obj->title = $params['question_title'];
				$obj->qst_text = $params['question_textarea'];
				$obj->question_type_id = $params['question_type'];
				$obj->subject_id = $params['subject_id'];
				$obj->lesson_id = $params['institution_id'];
				$obj->passage_id = $params['passage'];
				$obj->institute_id = $params['institution_id'];
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
			return redirect('/resources/question');
		}
	}

	public function questionedit($id = 0)
	{
		$questions = Question::where('id',$id)->get()->toArray();
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject($questions[0]['category_id']);
		$category = $this->category->getCategory($questions[0]['institute_id']);
		$lessons = $this->lesson->getLesson($questions[0]['subject_id']);
		$passage = $this->passage->getPassage();
		$qtypes = $this->question_type->getQuestionTypes();
		if(isset($id) && $id > 0)
		{
			$obj = $this->question->find($id);
			$id = $obj->id;
			$institution_id = $obj->institution_id;
			$subject_id = $obj->subject_id; 
			$category_id = $obj->category_id; 
			$name = $obj->name; 
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
 		
		$answersLisitng = view('resources::question.partial.edit_listing_answers', compact('oldAnswers'));

		return view('resources::question.question_edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','passage','category_id','questions', 'lessons', 'qtypes', 'oldAnswers','answersLisitng'));


//
//   		return view('resources::question.question_edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','category_id','passage','qtypes'))
//			->nest('answersLisitng', 'resources::question.partial.listing_answers', compact('oldAnswers'));
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
		return $list;
	}
	
	public function questiondelete($qid=0){
		if($qid > 0)
		{
			$this->question->deleteQuestions($qid);
		}
		return redirect('/resources/question');
	}

	public function categoryList($id){
		$category=	Institution::join('category','institution.id','=','category.institution_id')
			->where('institution.id','=',$id)
			->select('category.id','category.name')
			->get();
 		return $category;
 	}
	public function subjectList($id){
		$subject=	Category::join('subject','category.id','=','subject.category_id')
			->where('category.id','=',$id)
			->select('subject.id','subject.name')
			->get();
		return $subject;
	}
	public function lessonsList($id){
 		$lessons=	Lesson::join('subject','lesson.subject_id','=','subject.id')
			->where('subject.id','=',$id)
			->select('lesson.id','lesson.name')
			->get();
 		return $lessons;
	}
	
}
