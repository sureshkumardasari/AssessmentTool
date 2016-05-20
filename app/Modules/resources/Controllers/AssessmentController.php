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
        return view('resources::assessment.list',compact('inst_arr', 'questions','subjects','category'));
	}

	public function assessmentcreate(){
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;		
		$inst_arr = $this->institution->getInstitutions();	
		$subjects = $this->subject->getSubject();	
		$category = $this->category->getCategory();
		$questions = $this->question->getQuestions();
        return view('resources::assessment.add',compact('inst_arr', 'questions','subjects','category'));
	}

    
}
