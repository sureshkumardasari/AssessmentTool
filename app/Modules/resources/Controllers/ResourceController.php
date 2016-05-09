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

class ResourceController extends BaseController {

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
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}

    public function subject($parent_id = 0)
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;		
		$inst_arr = $this->institution->getInstitutions();	
		$category = $this->subject->getCategory();

		$subjects = $this->subject->getSubject();
        return view('resources::subject.list',compact('inst_arr', 'subjects','category'));
	}

	public function subjectadd()
	{		
		$inst_arr = $this->institution->getInstitutions();
		$category = $this->subject->getCategory();

		$id = $institution_id = $category_id = 0;
		$name = '';
		return view('resources::subject.edit',compact('id','institution_id','name','inst_arr','category','category_id'));
	}

	public function subjectedit($id = 0)
	{		
		$inst_arr = $this->institution->getInstitutions();
		$category = $this->subject->getCategory();

		if(isset($id) && $id > 0)
		{
			$obj = $this->subject->find($id);
			$id = $obj->id; 
			$institution_id = $obj->institution_id; 
			$name = $obj->name; 
			$category_id = $obj->category_id; 
		}
		else
		{
			$id = $institution_id = $category_id = 0;
			$name = '';
		}
		return view('resources::subject.edit',compact('id','institution_id','name','inst_arr','category','category_id'));
	}

	public function subjectupdate($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->subject->updateSubject($params);

		return redirect('/resources/subject');
	}

	public function subjectdelete($id = 0)
	{
		if($id > 0)
		{
			$this->subject->deleteSubject($id);
		}
		return redirect('/resources/subject');
	}

	public function lesson($parent_id = 0)
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;		
		$inst_arr = $this->institution->getInstitutions();	
		$subjects = $this->subject->getSubject();	
		$category = $this->subject->getCategory();

		$lessons = $this->lesson->getLesson();
        return view('resources::lesson.list',compact('inst_arr', 'lessons','subjects','category'));
	}

	public function lessonadd()
	{		
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->subject->getCategory();

		$id = $institution_id = $subject_id = $category_id = 0;
		$name = '';
		return view('resources::lesson.edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','category_id'));
	}

	public function lessonedit($id = 0)
	{		
		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		$category = $this->subject->getCategory();

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
}
