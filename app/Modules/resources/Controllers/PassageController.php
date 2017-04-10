<?php namespace App\Modules\Resources\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Html\HtmlFacade;

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


class PassageController extends BaseController {

	

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

    

	public function passage($parent_id = 0)
	{
		$passages = $this->passage->getPassage();
		if (getRole() == "student") {
     return view('permission');
    }else{
        return view('resources::passage.list',compact('passages'));
    }
	}

	public function passageadd()
	{	$passages = $this->passage->getPassage();
		if (getRole() == "student") {
     return view('permission');
    }else{	
		$institution_id=0;
		$subjects = $this->subject->getSubject();
		$category = $this->category->getCategory();
		$lessons = $this->lesson->getLesson();
		$inst_arr = $this->institution->getInstitutions();
		$id = 0;
		$sCategory=0;
		$sSubject=0;
		$sLesson=0;
		// $passages[0]['category_id ']=[];
		$passages = Passage::where('id',$id)->get()->toArray();
		//dd($passages);
		$passage = new passage();
		//dd($passages);
		//dd($inst_arr);
           // \Session::flash('flash_message','Information saved successfully.');

		return view('resources::passage.edit',compact('passage','sCategory','sLesson','sSubject','passages','inst_arr','institution_id','category','lessons','subjects'));
	}
	}
	public function passageview($id = 0)
	{
		if (getRole() == "student") {
     return view('permission');
    }else{
		$passages=Passage::where('id','=',$id)->get();
		//dd($passages);

		return view('resources::passage.view',compact('passages'));
	}
	}

	public function passageedit($id = 0)
	{		$passages = $this->passage->getPassage();
		if (getRole() == "student") {
     return view('permission');
    }else{
		$passages = Passage::where('id',$id)->get()->toArray();
		$sCategory=$passages[0]['category_id'];
		$sSubject=$passages[0]['subject_id'];
		$sLesson=$passages[0]['lesson_id'];
		$inst_arr = $this->institution->getInstitutions();
		$institution_id=$passages[0]['institute_id'];
		$subjects = $this->subject->getSubject($passages[0]['category_id']);
		$category = $this->category->getCategory($passages[0]['institute_id']);
		$lessons = $this->lesson->getLesson($passages[0]['subject_id']);
		$passage = $this->passage->getPassage();
		//dd($passage);
		if(isset($id) && $id > 0)
		{
			$passage = $this->passage->find($id);
			//dd($passage);
			//$obj = $this->passage->find($id);
			$id = $passage->id;
		    //$institution_id = $passage->institution_id;
			$subject_id = $passage->subject_id; 
			$category_id = $passage->category_id;
			$lessons_id=$passage->lesson_id;
		}
		else
		{
			$id = $institution_id = $subject_id = $category_id = 0;
			$name = '';
		}
		//dd($passages);
				      // \Session::flash('flash_message','Information saved successfully.');

		return view('resources::passage.edit',compact('id','sCategory','sLesson','sSubject','passage','inst_arr','institution_id','category','lessons','subjects','passages','category_id','subject_id','lessons_id'));
	}
	}

	public function passageupdate($id = 0)
	{
		$post = Input::All();
		//dd($post);
		$messages=[
			'subject_id.required'=>'The Subject field is required',
			'category_id.required'=>'The Category field is required',
			'lessons_id.required'=>'The Lessons field is required',
			'institution_id.required'=>'The Institution field is required',
			'passage_text.required'=>'The Passage Text is required',
			'passage_title.required'=>'The Passage Title is required',
			'passage_lines.required'=>'The Passage Lines is required'
      		];
		$rules = [
			'institution_id' => 'required|not_in:0',
			'subject_id' => 'required|not_in:0',
			'lessons_id' => 'required|not_in:0',
			'category_id'=>'required|not_in:0',
 			'passage_text' => 'required',
 			'passage_title'=>'required',
 			'passage_lines'=>'required'
 				];


		if ($post['id'] > 0)
		{
			$rules['passage_title'] = 'required';
		}
		if($post['passage_title']){
			$passage=Passage::where('lesson_id','=',$post['lessons_id'])->where('title',$post['passage_title'])->whereNotIn('id',[$post['id']])->first();
			if($passage){
		 	return Redirect::back()->withInput()->withErrors(['The Passage Title is Already Entered']);
   			} 
  		}
		
		$validator=Validator::make($post,$rules,$messages);
		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		else{
		$this->passage->updatepassage($post);
		        \Session::flash('flash_message','Information saved successfully.');

		return redirect('/resources/passage');
	    }
	}

	public function passagedelete($id)
	{
		$pas=AssessmentQuestion::where('passage_id',$id)->count();
		$qus=Question::where('passage_id',$id)->count();
		//dd($les);
		if ($pas == null && $qus == null) {
			passage::find($id)->delete();
			\Session::flash('flash_message', 'delete!');
			return redirect('/resources/passage');
		} else {
			\Session::flash('flash_message_failed', 'Can not Delete this passage.');
			return Redirect::back();
		}
	}

	public function view($id = null) {       
       $name = $passage_text = $passage_lines = $status = '';
        if (empty($id)) {
            return view('resources::passage.view',compact('id','name','passage_text','passage_lines','status'));
        } else {
            $passage = Passage::find($id);
            //        dd($passage->subjects);
            return view('resources::passage.view', compact('passage'));
        }
    }


    public function passagepopup()
    {
    	$passage = Input::All(); 
    	//dd($passage);

    	$institution=Institution::where('id',$passage['institution'])->first();
    	$category=Category::where('id',$passage['category'])->first();
    	$subject=Subject::where('id',$passage['subject'])->first();
    	$lessons=Lesson::where('id',$passage['lessons'])->first();
    	
    	
    	return view('resources::passage.popup',compact('passage','institution','category','subject','lessons'));
	}

}
