<?php namespace App\Modules\Resources\Controllers;

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
        return view('resources::question.list',compact('inst_arr', 'questions','subjects','category'));
	}

	public function questionadd()
	{		
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$qtypes = $this->question_type->getQuestionTypes();
		
		$id = $institution_id = $subject_id = $category_id = 0;
		$name = '';

		// old answers listing
		$oldAnswers = array(); //$question->answers->toArray();
        $answersLisitng = view('resources::question.partial.listing_answers', compact('oldAnswers'));

		return view('resources::question.edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','category_id', 'qtypes', 'answersLisitng'));
	}

	public function questionedit($id = 0)
	{		
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();

		if(isset($id) && $id > 0)
		{
			$obj = $this->lesson->find($id);
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
		return view('resources::lesson.edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','category_id'));
	}

	public function lessonupdate($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->lesson->updatelesson($params);

		return redirect('/resources/lesson');
	}

	public function lessondelete($id = 0)
	{
		if($id > 0)
		{
			$this->lesson->deletelesson($id);
		}
		return redirect('/resources/lesson');
	}

	public function category($parent_id = 0)
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;		
		$inst_arr = $this->institution->getInstitutions();
		$category = $this->category->getCategory();
        return view('resources::category.list',compact('inst_arr','category'));
	}

	public function categoryadd()
	{		
		$inst_arr = $this->institution->getInstitutions();

		$id = $institution_id = 0;
		$name = '';
		return view('resources::category.edit',compact('id','institution_id','name','inst_arr'));
	}

	public function categoryedit($id = 0)
	{		
		$inst_arr = $this->institution->getInstitutions();

		if(isset($id) && $id > 0)
		{
			$obj = $this->category->find($id);
			$id = $obj->id; 
			$institution_id = $obj->institution_id; 
			$name = $obj->name; 
		}
		else
		{
			$id = $institution_id = 0;
			$name = '';
		}
		return view('resources::category.edit',compact('id','institution_id','name','inst_arr'));
	}

	public function categoryupdate($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->category->updatecategory($params);

		return redirect('/resources/category');
	}

	public function categorydelete($id = 0)
	{
		if($id > 0)
		{
			$this->category->deletecategory($id);
		}
		return redirect('/resources/category');
	}
}
