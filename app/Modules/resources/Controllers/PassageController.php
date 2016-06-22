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
        return view('resources::passage.list',compact('passages'));
	}

	public function passageadd()
	{		
		$institution_id=0;
		//dd("123");
		$inst_arr = $this->institution->getInstitutions();

		$id = 0;
		$passage = new passage();
		//dd($inst_arr);
		return view('resources::passage.edit',compact('passage','inst_arr','institution_id'));
	}
	public function passageview($id = 0)
	{
		$passages=Passage::where('id','=',$id)->get();
		//dd($passages);

		return view('resources::passage.view',compact('passages'));
	}

	public function passageedit($id = 0)
	{		
		$passages = Passage::where('id',$id)->get()->toArray();
		$inst_arr = $this->institution->getInstitutions();
		$institution_id=$passages[0]['institute_id'];
		$subjects = $this->subject->getSubject($passages[0]['category_id']);
		$category = $this->category->getCategory($passages[0]['institute_id']);
		$lessons = $this->lesson->getLesson($passages[0]['subject_id']);
		$passage = $this->passage->getPassage();
		//dd($category);
		if(isset($id) && $id > 0)
		{
			$passage = $this->passage->find($id);
			//$obj = $this->passage->find($id);
			$id = $passage->id;
		    //$institution_id = $passage->institution_id;
			$subject_id = $passage->subject_id; 
			$category_id = $passage->category_id; 
		}
		else
		{
			$id = $institution_id = $subject_id = $category_id = 0;
			$name = '';
		}
		//dd($passages);
		return view('resources::passage.edit',compact('id','passage','inst_arr','institution_id','category','lessons','subjects','passages','category_id','subject_id'));
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
			'passage_textarea.required'=>'The Passage Text is required'
      		];
		$rules = [
			'institution_id' => 'required|not_in:0',
			'subject_id' => 'required',
			'lessons_id' => 'required',
 			'passage_textarea' => 'required',
 				];


		if ($post['id'] > 0)
		{
			$rules['passage_title'] = 'required|min:3|unique:question,name,' . $post['id'];
		}
		
		
		$this->passage->updatepassage($post);

		return redirect('/resources/passage');
	}

	public function passagedelete($id = 0)
	{
		if($id > 0)
		{
			$this->passage->deletepassage($id);
		}
		return redirect('/resources/passage');
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
}
