<?php namespace App\Modules\Resources\Controllers;

use App\Modules\Resources\Models\Passage;
use Illuminate\Support\Facades\Auth;

//use PhpSpec\Console\Prompter\Question;
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
use App\Modules\Resources\Models\Assessment;

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

		$obj = new Category();
		$this->category = $obj;
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
		$category = $this->category->getCategory();
//		$categorylist = $this->category->getCategoryList();
 		$subjects = $this->subject->getSubject();
		$subjectcategory = $this->subject->getSubjectCategory(); 
        return view('resources::subject.list',compact('inst_arr','category','subjectcategory'))
        ->nest("subjectsList", 'resources::subject._list', compact('subjects','category','subjectcategory'));
	}

	public function subjectadd()
	{		
		$id = $institution_id = $category_id = 0;
		$name = '';

		$institution_id = (old('institution_id') != NULL && old('institution_id') > 0) ? old('institution_id') : 0;
		$category_id =  (old('category_id') != NULL && old('category_id') > 0) ? old('category_id') : 0;

		$inst_arr = $this->institution->getInstitutions();
		$category = $this->category->getCategory($institution_id);
		
		return view('resources::subject.edit',compact('id','institution_id','name','inst_arr','category','category_id'));
	}

	public function subjectedit($id = 0)
	{		
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

		$institution_id = (old('institution_id') != NULL && old('institution_id') > 0) ? old('institution_id') : $institution_id;
		$category_id =  (old('category_id') != NULL && old('category_id') > 0) ? old('category_id') : $category_id;

		$inst_arr = $this->institution->getInstitutions();
		$category = $this->category->getCategory($institution_id);

		return view('resources::subject.edit',compact('id','institution_id','name','inst_arr','category','category_id'));
	}

	public function subjectupdate($id = 0)
	{
		$post = Input::All();

		$rules = [
			'institution_id' => 'required|not_in:0',
			'category_id' => 'required|not_in:0',
			'name' => 'required|min:3|'];

		if ($post['id'] > 0)
		{
			$rules['name'] = 'required|min:3|' . $post['id'];
		}
		$validator = Validator::make($post, $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		} else
		{

		$params = Input::All();
	     $number=Subject::where('institution_id',$params['institution_id'])
		 ->where('category_id',$params['category_id'])->where('name',$params['name'])->count();
        if($number>0){
	        return Redirect::back()->withInput()->withErrors("subject already entered");
          }
			//var_dump($params);
			$this->subject->updateSubject($params);

			return redirect('/resources/subject');
		}
	}

	public function subjectupdateold($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->subject->updateSubject($params);

		return redirect('/resources/subject');
	}

	public function subjectdelete($id)
	{

		$les=Lesson::where('subject_id',$id)->count();
		$ass=Assessment::where('subject_id',$id)->count();
		//dd($les);
		if ($les == null && $ass == null ) {
			Subject::find($id)->delete();
			\Session::flash('flash_message', 'delete!');

			return redirect('/resources/subject');

		} else {
			\Session::flash('flash_message_failed', 'Can not Delete this subject.');
			return Redirect::back();

		}

	}

	public function lesson($parent_id = 0)
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;		
		$inst_arr = $this->institution->getInstitutions();	
		$subjects = $this->subject->getSubject();	
		$category = $this->category->getCategory();
		$lessons = $this->lesson->getSubjectCategoryLesson();
 //		$lessons = $this->lesson->getLesson();
        //return view('resources::lesson.list',compact('inst_arr', 'lessons','subjects','category'));
        return view('resources::lesson.list',compact('inst_arr','subjects','category'))
        ->nest("lessonsList", 'resources::lesson._list', compact('lessons'));
	}

	public function lessonadd()
	{	
		$institution_id = (old('institution_id') != NULL && old('institution_id') > 0) ? old('institution_id') : 0;
		$category_id =  (old('category_id') != NULL && old('category_id') > 0) ? old('category_id') : 0;
		$subject_id = (old('subject_id') != NULL && old('subject_id') > 0) ? old('subject_id') : 0;



		$inst_arr = $this->institution->getInstitutions();
		$category = $this->category->getCategory($institution_id);
		$subjects = $this->subject->getSubject($institution_id, $category_id);		

		$id = $institution_id = $subject_id = $category_id = 0;
		$name ='';
		return view('resources::lesson.edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','category_id'));
	}

	public function lessonedit($id = 0)
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

		$institution_id = (old('institution_id') != NULL && old('institution_id') > 0) ? old('institution_id') : $institution_id;
		$category_id =  (old('category_id') != NULL && old('category_id') > 0) ? old('category_id') : $category_id;
		$subject_id = (old('subject_id') != NULL && old('subject_id') > 0) ? old('subject_id') : $subject_id;

		$inst_arr = $this->institution->getInstitutions();
		$category = $this->category->getCategory($institution_id);
		$subjects = $this->subject->getSubject($institution_id, $category_id);		

		return view('resources::lesson.edit',compact('id','institution_id','name','inst_arr', 'subjects','subject_id','category','category_id'));
	}

	public function lessonupdate($id = 0)
	{
		$post = Input::All();

		$rules = [
			'institution_id' => 'required|not_in:0',
			'category_id' => 'required|not_in:0',
			'subject_id' => 'required|not_in:0',
			'name' => 'required|min:3'];

		if ($post['id'] > 0)
		{
			$rules['name'] = 'required|min:3|unique:lesson,name,' . $post['id'];
		}
		$validator = Validator::make($post, $rules);

		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		else

		{
			$params = Input::All();
			$num = Lesson::where('institution_id', $params['institution_id'])
				->where('category_id', $params['category_id'])
				->where('subject_id', $params['subject_id'])->where('name', $params['name'])->count();
			if ($num > 0) {
				return Redirect::back()->withInput()->withErrors("lesson already entered");
			}
				$this->lesson->updatelesson($params);

				return redirect('/resources/lesson');
			}
		}

	public function lessonupdateold($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->lesson->updatelesson($params);

		return redirect('/resources/lesson');
	}

	public function lessondelete($id)
	{
		$qus=Question::where('lesson_id',$id)->count();
		$pas=Passage::where('lesson_id',$id)->count();
		//dd($qus);
		if ($qus == null && $pas == null ) {
			Lesson::find($id)->delete();
			\Session::flash('flash_message', 'delete!');

			return redirect('/resources/lesson');

		} else {
			\Session::flash('flash_message_failed', 'Can not Delete this lesson.');
			return Redirect::back();

		}

	}

	public function category($parent_id = 0)
	{
		//$parent_id = ($parent_id > 0) ? $parent_id : Auth::user()->institution_id;		
		$inst_arr = $this->institution->getInstitutions();
		$category = $this->category->getCategory();
        //return view('resources::category.list',compact('inst_arr','category'));
        return view('resources::category.list',compact('inst_arr'))
        ->nest("categoryList", 'resources::category._list', compact('category'));
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
		$post = Input::All();

		$rules = [
			'institution_id' => 'required|not_in:0',
			'name' => 'required|min:3'];

		/*if ($post['id'] > 0) {
			$rules['name'] = 'required|min:3|unique:category,name,' . $post['id'];
		}*/
		$validator = Validator::make($post, $rules);

		if ($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		} else {
			$params = Input::All();
			$num = category::where('institution_id', $params['institution_id'])->where('name', $params['name'])->count();
			if ($num > 0) {
				return Redirect::back()->withInput()->withErrors("category already entered");
			}
			//var_dump($params);
			$this->category->updatecategory($params);

			return redirect('/resources/category');
		}
	}

	public function categoryupdateold($id = 0)
	{
		$params = Input::All();
		//var_dump($params);
		$this->category->updatecategory($params);

		return redirect('/resources/category');
	}

	public function categorydelete($id)
	{
		$sub=Subject::where('category_id',$id)->count();
		if ($sub == null) {
			Category::find($id)->delete();
			\Session::flash('flash_message', 'delete!');

			return redirect('/resources/category');

		} else {
			\Session::flash('flash_message_failed', 'Can not Delete this category.');
			return Redirect::back();

		}
	}

	public function lessonsearch($institution_id = 0, $category_id = 0, $subject_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$category_id = (isset($params['category_id'])) ? $params['category_id'] : $category_id;
		$subject_id = (isset($params['subject_id'])) ? $params['subject_id'] : $subject_id;

//		$lessons=$this->lesson->getLesson($institution_id, $category_id, $subject_id);
		$lessons=$this->lesson->getSubjectCategoryLesson($institution_id, $category_id, $subject_id);
         
        $from = 'search';
        return view('resources::lesson._list', compact('lessons', 'from'));
	}

	public function subjectsearch($institution_id = 0, $category_id = 0, $subject_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$category_id = (isset($params['category_id'])) ? $params['category_id'] : $category_id;
		$subject_id = (isset($params['subject_id'])) ? $params['subject_id'] : $subject_id;

//		$subjects=$this->subject->getSubject($institution_id, $category_id);
		$subjectcategory = $this->subject->getSubjectCategory($institution_id, $category_id);

        $from = 'search';
        return view('resources::subject._list', compact('subjectcategory', 'from'));
	}

	public function categorysearch($institution_id = 0, $category_id = 0, $subject_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$category_id = (isset($params['category_id'])) ? $params['category_id'] : $category_id;
		$subject_id = (isset($params['subject_id'])) ? $params['subject_id'] : $subject_id;

		$category=$this->category->getCategory($institution_id);
		//dd($category);
        
        $from = 'search';
        return view('resources::category._list', compact('category', 'from'));
	}	

	public function getcategory($institution_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$category=$this->category->getCategory($institution_id);
		return json_encode($category);
	}	

	public function getsubject($institution_id = 0, $category_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$category_id = (isset($params['category_id'])) ? $params['category_id'] : $category_id;
		$subjects=$this->subject->getSubject($category_id);
		return json_encode($subjects);
	}

	public function getlesson($institution_id = 0, $category_id = 0, $subject_id = 0)
	{
		$params = Input::All();
		$institution_id = (isset($params['institution_id'])) ? $params['institution_id'] : $institution_id;
		$category_id = (isset($params['category_id'])) ? $params['category_id'] : $category_id;
		$subject_id = (isset($params['subject_id'])) ? $params['subject_id'] : $subject_id;

		$lesson=$this->lesson->getLesson($institution_id, $category_id, $subject_id);
		return json_encode($lesson);
	}		
}
