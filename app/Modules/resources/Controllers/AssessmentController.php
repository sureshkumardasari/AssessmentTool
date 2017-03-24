<?php namespace App\Modules\Resources\Controllers;

use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\QuestionAnswer;
use Illuminate\Support\Facades\Auth;
// use Request;
use Illuminate\Http\Request;
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
use App\Modules\Resources\Models\Template;
use App\Modules\Admin\Models\User;
use DB;
use mikehaertl\wkhtmlto\Pdf;
use Exception;
use App\Modules\Resources\Controllers\QuestionController;


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

		$obj = new Template();
		$this->template = $obj;
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
		$lesson = $this->lesson->getLesson();
		$questions = $this->question->getQuestions();
		$templates = $this->template->getTemplates();

		if(getRole()!= "administrator") {
			$uid= \Auth::user()->institution_id;
			//dd($uid);
		$assessment=Assessment::where('institution_id','=',$uid)->get();
		}
		else {
			$assessment = Assessment::get();
		}
			$institution_id='';
        return view('resources::assessment.list'  ,compact('assessment','institution_id','inst_arr', 'questions','subjects','category', 'templates'));
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
		$lesson = $this->lesson->getLesson ();
		$questions = $this->question->getQuestions();
		$question_type=$this->question_type->getQuestionType();
		
		$inst_questions_list=Question::where('institute_id',$user_institution_id)->get();
		// $inst_passages_list=Passage::where('institute_id',$user_institution_id)->get();
		$inst_passages_list=Passage::join('questions','questions.passage_id','=','passage.id')
			// ->whereNotNull('questions.passage_id')
			->where('questions.institute_id',$user_institution_id)
			->groupBy('questions.passage_id')
			->select('passage.title as title','passage.id as id')
 			->get();
//		$inst_questions_list=[];
 			return view('resources::assessment.add',compact('inst_passages_list','inst_questions_list','inst_arr', 'id','institution_id','questions','subjects','category','lesson','question_type'));
	}
	public function assessmentInsert(){

		$post = Input::All();
		//dd($post);
		if(!isset($post['passageIds'])){
			$post['passageIds']="";
		}
		if(!isset($post['QuestionIds'])){
			$post['QuestionIds']="";
		}

		//dd($post);
  		$messages=[
			'subjects_list.required'=>'The Subject field is required',
			'category_id.required'=>'The Category field is required',
		    'lessons_list.required'=>'The Lessons field is required',
			'institution_id.required'=>'The Institution field is required',
			'QuestionIds.required'=>'The Questions is required',
			'total_time.required' => 'Please add the total time of an Assessment'
		];
		$rules = [
			'name' => 'required|unique:assessment,name',
			'header'=>'required',
			'footer'=>'required',
			'begin_instruction'=>'required',
			'end_instruction'=>'required',
			'institution_id' => 'required|not_in:0',
			'category_id' => 'required|not_in:0',
            'subjects_list' => 'required',
			'lessons_list' => 'required',
 			'QuestionIds' => 'required',
		];
		if(!isset($post['never_expires'])){
			$rules['total_time'] = 'required';
		}

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
			$sub=implode(',',$post['subjects_list']);
			$less=implode(',',$post['lessons_list']);
  				$assessment_insert = new Assessment();
 				$assessment_insert->name = $post['name'] ;
            $assessment_insert->institution_id = $post['institution_id'] ;
            $assessment_insert->category_id = $post['category_id'] ;
           // $assessment_insert->subject_id = $post['subject_id'] ;
			$assessment_insert->subject_id = $sub ;
			$assessment_insert->lesson_id = $less ;
          // $assessment_insert->lesson_id = $post['lessons_id'] ;
            $assessment_insert->questiontype_id = isset($post['question_type'])?$post['question_type']:0 ;
			$assessment_insert->header = $post['header'];
			$assessment_insert->footer = $post['footer'];
			$assessment_insert->begin_instruction = $post['begin_instruction'];
			$assessment_insert->end_instruction = $post['end_instruction'];
			$assessment_insert->guessing_panality = $post['guessing_penality'] ;
			$assessment_insert->mcsingleanswerpoint = $post['mcsingleanswerpoint'] ;
			$assessment_insert->essayanswerpoint = $post['essayanswerpoint'] ;
			if(!isset($post['never_expires'])){
				$assessment_insert->totaltime = $post['total_time'];
				$assessment_insert->unlimitedtime = 0;
			}
			else{
				$assessment_insert->unlimitedtime = 1;
			}

			//$assessment_insert->lessons_id = $post['lessons_id'] ;
  				if($assessment_insert->save()){
				
						foreach ($questions as $value) {
						if($value=='')continue;
						$assessment_id=$assessment_insert->id;
						$assessment_question=new AssessmentQuestion();
						$assessment_question->assessment_id=$assessment_id;
						$assessment_question->question_id=$value['id'];
						$assessment_question->passage_id=isset( $value['passage_id'] ) ? $value['passage_id'] : 0;
						$assessment_question->save();
						}
//					
 				}
 						       \Session::flash('flash_message','Information saved successfully.');

 				return Redirect::route('template', ['id' =>  $assessment_id /*, 'tplId' => $newOrder*/]);
 			//return redirect('/resources/assessment');
		}
 	}


 	public function savePdf(Request $request){
        $assessment_Id = $request->get('Id',140); //for testing
        $template_Id = $request->get('tplId',140); //for testing
        $preview = $request->get('perview', '0'); //for testing
        // $subsectionId = $request->get('Id');
        $tpl = Template::find($template_Id);
        		        \Session::flash('flash_message','Information saved successfully.');

        return $this->_savePdf($assessment_Id, $template_Id, $preview);
    }

    private function _savePdf($assessment_Id, $template_Id, $preview){

        $template = Template::find($template_Id);
		//dd($template);
		$assessment = Assessment::find($template->assessment_id);
		//dd($assessment);
        $s3 = ''; //new \App\Models\S3;
        
        $_pdf       = $this->_generatePdf($template, $assessment, $s3, 'PdfContent');

        if ($preview == '0') {

            $_imagesPdf = $this->_generatePdf($template, $assessment, $s3, 'Template');

            // PDF TO IMAGE PROCESS FOR TEST TAKING
            $pdf_path = $_imagesPdf['pdfPath'];
            $id = $assessment_Id;
            $pdf_image_dir=public_path("/data/assessment_pdf_images");
            if(!is_dir($pdf_image_dir)){
            	 $oldmask = umask(0);
                mkdir($pdf_image_dir, 0777);
                umask($oldmask);
            }
            // make images
            $dirPath = public_path('/data/assessment_pdf_images/assessment_'.$id);
            // $s3->deleteDirectory('assessment_'.$id, 'assessment_pdf_images');
            // $s3->makeDirectory('assessment_'.$id, 'assessment_pdf_images');

            if(!is_dir($dirPath)){
                $oldmask = umask(0);
                mkdir($dirPath, 0777);
                umask($oldmask);
            }

            $dirPath = public_path('/data/assessment_pdf_images/assessment_'.$id);
			//$dirPath = public_path('/data/assessment_pdf_images/assessment_'.$id.'/templatge_'.$template_Id);

			// $s3->makeDirectory('assessment_'.$id.'/subsection_'.$template_Id, 'assessment_pdf_images');

            if(!is_dir($dirPath)){
                $oldmask = umask(0);
                mkdir($dirPath, 0777);
                umask($oldmask);
            }
            $files = glob($dirPath.'/*'); // get all file names
            foreach($files as $file){ // iterate files
              if(is_file($file))
                unlink($file); // delete file
            }

            $pdf_path = $_imagesPdf['pdfPath'];
           
            exec('convert  -density 380x380 -quality 40 "'. $pdf_path .'" "'. $dirPath .'/%d.jpg"');
            
            // Returns array of files
            $files = scandir($dirPath);
            // Count number of files
            $images = range(0, count($files)-3);

            ////////////////////////////
            // Upload the image to S3 //
            ////////////////////////////
            foreach($images as $i=>$image) {
                $image_path = $dirPath.'/'. $i .'.jpg';
                // Upload to the path in "assessment_pdf_images" directory
                // $s3->uploadByPathToPath($image_path, 'assessment_'.$id.'/subsection_'.$subsectionId, 'assessment_pdf_images');
                //unlink( $image_path );
            }

            // unlink( $_imagesPdf['pdfPath'] );
        }
        else{

        }
        // unlink( $_pdf['pdfPath'] );
        if ($preview == '1') {
	        return $_pdf['pdfPath'];
	    }else{
	    	return '1';
	    }
    }

    private function _generatePdf($template, $assessment, $s3, $field) {
    	$pdf = new Pdf;
    	$globalOptions = array(
			'margin-bottom'    => 20,
		);
		$pdf->binary = 'wkhtmltopdf';

		// $pdf->setOptions($globalOptions);
		$options = array(
			'javascript-delay' => 2000,
			'encoding'         => 'UTF-8',
			'footer-line',
			'footer-font-size' => 10,
			'footer-spacing'   => 10,
			'margin-bottom' => '25mm',
			'header-spacing' => 15,
		);        

        $pages = '';
        //dd($pages);
        $splitOn = '<div class="page">';
        //dd($splitOn);
        $temps = explode($splitOn, $template->pdf_content);
       // dd($temps);
        if ($field == 'PdfContent') {
            // Add Header And Footer
            $header = $template->header;
            $footer = $template->footer;
            // update options
            $options['header-html'] = view('resources::assessment.partial.pdf.header', compact('header'))->render();
            $options['footer-html'] = view('resources::assessment.partial.pdf.footer', compact('footer'))->render();   
          //dd($options['header-html']);         
        }
        $pdf->setOptions($options);
        // init wkhtmltopdf       

        // Add Pages        
        $tempsCount = count($temps);
       //dd($tempsCount);
        foreach($temps as $key => $temp){
            $temp = trim($temp);
            if (empty($temp)) {
                continue;
            }
            $temp = substr(trim($temp), 0,-6);     // to remove closing div'
           //dd($temp);
            $content = '<div class="page">'.$temp.'</div>';
            //dd($content);
            $parentId = 1;
            //dd($pages);
            $pages = view('resources::assessment.partial.pdf.page', compact('content', 'parentId'))->render();
     
            $pdf->addPage($pages);
            //dd($pdf);
        }
        
        if ($field == 'PdfContent') {  
            $fullPath = public_path('data/assessment_pdf/assessment_'. $assessment->id .'.pdf');
        } else {
            $fullPath = public_path('data/assessment_pdf/assessment_images_'. $assessment->id .'.pdf');
        }

        if (!$pdf->saveAs($fullPath)) {
			echo $pdf->getCommand();
			throw new Exception('Could not create PDF: '.$pdf->getError());
		}
		
        // check if file is created        
        if (!file_exists($fullPath)) {
            return 'Error: ' . $pdf->getError();
        }
        
        $s3Path = ''; //$s3->uploadByPath($fullPath, 'subsection_pdf');
        if ($field == 'PdfContent') {  
        	$fullPath = url().'/data/assessment_pdf/assessment_'. $assessment->id .'.pdf';
        }	
        return array('s3Path' => $s3Path, 'pdfPath' => $fullPath);
    }
    
 	public function savePrintOnlineView(Request $request) {
 		$html = $request->input('html');  
        $originalTemplate = $request->input('html_orginal');
        $originalTemplate_2 = $request->input('html_orginal2');
        //dd($originalTemplate_2);
        $pdfContent = $request->input('pdf_content');
        $headerHtml = $request->input('header');
    	$footerHtml = $request->input('footer');
    	$asmt_id = $request->input('assessment_id');
    	$tpl_id = $request->input('template_id');
        // dd($tpl_id);
        // delete old template
    	$_templateId = '';
		$temp=Template::find($tpl_id);
		if(count($temp)>0){
			$a=$temp->id;
		}
		else{
			$a="";
		}
    	if($tpl_id>0) $_templateId = $a;
        if (!empty($_templateId)) {
            $template = Template::find($_templateId);
            if ($template) {
                $template->delete();
            }
        }
		
        $template = new Template();
        $templateId = $template->saveIt('Custom', $asmt_id, $pdfContent, $headerHtml, $footerHtml, $originalTemplate, $originalTemplate_2);
        return $templateId;
    }

 	private function renderTemplate($blade, $questions, $beginInstructions, $endInstructions, $titlePage) {
        return view('resources::assessment._template_1', compact('questions', 'beginInstructions', 'endInstructions', 'titlePage'));
    }

 	public function getTemplate($id=0, $tplId=0){
 		$assessment=DB::table('assessment')->where('id','=',$id)->select('name', 'begin_instruction', 'end_instruction', 'header', 'footer', 'titlepage')->get();
 		$title = $assessment[0]->name;
		$beginInstructions = '';// $assessment[0]->begin_instruction;
		$endInstructions = '';// $assessment[0]->end_instruction;
		$header = $assessment[0]->header;
		$footer = $assessment[0]->footer;
		$titlePage = $assessment[0]->titlepage;
		if(isset($id) && $id > 0)
		{
			$questions = $this->assessment->getDetails($id);

		}

		//dd($assessments);
		$html = '';
		$templateId = 1;
		
		$mode = '';
		$type = '';
		$old = '';
		$html2 = '';
		$blade = $templateId;
		$html = $html2 = $this->renderTemplate($blade, $questions, $beginInstructions, $endInstructions, $titlePage);
		     // \Session::flash('flash_message','Information saved successfully.');

		return view('resources::assessment._template_customize_popup', compact('title', 'html', 'templateId', 'header', 'footer', 'mode',  'type', 'old', 'html2','endInstructions', 'beginInstructions', 'id', 'tplId'));

		// return $view('resources::assessment.partial._template_1', compact('html', 'templateId', 'header', 'footer', 'mode',  'type', 'old', 'html2','endInstructions','beginInstructions'));

		// return view('resources::assessment.view',compact('assessments','title'));
 	}

 	public function pdftest(){
 		$pdf = new Pdf;
		$globalOptions = array(
			'margin-bottom'    => 20,
		);
		$pdf->binary = 'wkhtmltopdf';

		$pdf->setOptions($globalOptions);
		$pageOptions = array(
			'javascript-delay' => 2000,
			'encoding'         => 'UTF-8',
			'footer-line',
			'footer-font-size' => 10,
			'footer-spacing'   => 10
		);

		// $pdf->addPage('<html><body>PDF</body></html>', $pageOptions);
		$content = '<div class="page">TEST</div>';
		$parentId = 1;
		$pages = view('resources::assessment.partial.pdf.page', compact('content', 'parentId'))->render();
		$pdf->addPage($pages);
		$filename = public_path('data/pdf/testNEW.pdf');
		if (!$pdf->saveAs($filename)) {
			echo $pdf->getCommand();
			throw new Exception('Could not create PDF: '.$pdf->getError());
		}
		echo $pdf->getCommand();
		echo "<br>created test PDF :  ".$filename;
 	}

 	public function assessmentpdf($assessmentId=0){

 		$_pdf = $this->_generatePdfTest($assessmentId, 'PdfContent');
 	}


 	private function _generatePdfTest($assessment, $field) {

        $options = array(
            'encoding' => 'UTF-8',
            'page-size' => 'A3',
                      // 'margin-right' => '15mm',
            'margin-bottom' => '25mm',
            // 'margin-left' => '14mm',
            'header-spacing' => 15,
            // 'footer-spacing' => 5,
            'disable-smart-shrinking',
            'no-outline'
        );
        
        $pages = '';
        // $template = Template::find($subsection->TemplateId);
        // $parentId = $template->ParentId;

        // $splitOn = '<div class="page">';
        // $temps = explode($splitOn, $template->{$field});

        // if ($field == 'PdfContent') {
        //     // Add Header And Footer
        //     $header = $template->Header;
        //     $footer = $template->Footer;
        //     // update options
        //     $options['header-html'] = view('assessment::partials.pdf.header', compact('header'))->render();
        //     $options['footer-html'] = view('assessment::partials.pdf.footer', compact('footer'))->render();            
        // }

        // init wkhtmltopdf
        $pdf = new Pdf($options);
        $pdf->binary = 'wkhtmltopdf';   
       
        $content = '<div class="page">TEST</div>';
        $parentId = 1;
        
        $pages = view('resources::assessment.partial.pdf.page', compact('content', 'parentId'))->render();
        $pdf->addPage($pages);
        $fullPath = public_path('data/pdf/test_.pdf');
        $pdf->saveAs( $fullPath );

        // check if file is created        
        if (!file_exists($fullPath)) {
            return 'Error: ' . $pdf->getError();
        }
        
        // $s3Path = $s3->uploadByPath($fullPath, 'subsection_pdf');

        // return array('s3Path' => $s3Path, 'pdfPath' => $fullPath);

        return array('pdfPath' => $fullPath);
    }

	public function assessmentview($id=0)
	{
		$title=DB::table('assessment')->where('id','=',$id)->select('assessment.name')->get();
		//dd($title);
		if(isset($id) && $id > 0)
		{
			$assessments = $this->assessment->getDetails($id);

		}
		else
		{
			$assessments = Input::All();

		}
		return view('resources::assessment.view',compact('assessments','title'));
	}
	public function assessmentedit($id=0){
		$question_id_passage=$id;
   		$inst_arr = $this->institution->getInstitutions();
		$subjects = $this->subject->getSubject();
		//var_dump($subjects);exit;

		$lesson=$this->lesson->getLesson();
		//$questions = $this->question->getQuestions();
		$questiontype=$this->question_type->getQuestionType();
		//dd($questiontype);
		$assessment_details = Assessment::find($id);
		//$obj = $this->assessment->find($id);
		//dd($assessment_details);
			
			
 		$question_selected_list=AssessmentQuestion::join('assessment','assessment_question.assessment_id','=','assessment.id')
			->where('assessment_question.assessment_id',$id)
 			->get();
 			//$questionids=AssessmentQuestion::where('assessment_id',$id)->lists('question_id');
 			$question=new QuestionController();
 	
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
		//$passages_list_not=Passage::wherenotin('id',$passages_list)->get();

		$id = $assessment_details->id; 
			$institution_id = $assessment_details->institution_id; 
			$category_id=$assessment_details->category_id;
			$subject_id=$assessment_details->subject_id;
			$lessons_id=$assessment_details->lesson_id;
			$question_type_id=$assessment_details->questiontype_id;
		$subjects=Subject::where('category_id',$category_id)->lists('name','id');
		$lesson = $this->lesson->getLesson($subject_id);
		$category = $this->category->getCategory($institution_id);
		//dd($institution_id);
				       // \Session::flash('flash_message','Information saved successfully.');

		return view('resources::assessment.edit',compact('passages_list_not','lesson','questions_lists','passages_lists','question_title_remove_ids','passages_list','question_tilte_details','assessment_details','inst_arr','id','institution_id', 'questions','subjects','category','category_id','subject_id','lesson','lessons_id','question_type_id','questiontype'));
 	}
	public function assessmentupdate($id=0){
 		$post = Input::All();

 	//dd($post);
 		$passage_id=[];
  		$messages=[
  			'QuestionIds.required'=>'The Questions is required',
		];
		$rules = [
			'name' => 'required',
			'mcsingleanswerpoint'=>array('required','numeric'),
			'essayanswerpoint'=>array('required','numeric'),
 			'QuestionIds' => 'required',];
		if(!isset($post['never_expires'])){
			$rules['total_time'] = 'required';
		}
		$validator=Validator::make($post,$rules,$messages);
		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		} else
		{
			$params = Input::All();
			//dd($params);
			$questions = Question::wherein('id',$post['QuestionIds'])->get();
			if(!isset($post['never_expires'])){
				$totaltime = $post['total_time'];
				$unlimitedtime = 0;
			}
			else{
				$totaltime = null;
				$unlimitedtime = 1;
			}
			//dd($questions);
			$sub=implode(',',$post['subjects_list']);
			$less=implode(',',$post['lessons_list']);
  			$assessment_details = Assessment::where('id',$post['id'])->update([
  				'name'=>$post['name'],
  				'institution_id'=>$post['institution_id'],
				'category_id'=>$post['category_id'],
				'subject_id'=>$sub,
				'lesson_id'=>$less,
				'questiontype_id'=>isset($post['question_type'])?$post['question_type']:0,
				//'lesson_id'=>$post['lessons_id'],
				'header'=>$post['header'],
				'footer'=>$post['footer'],
				'begin_instruction'=>$post['begin_instruction'],
				'end_instruction'=>$post['end_instruction'],
				'guessing_panality'=>$post['guessing_penality'],
				'mcsingleanswerpoint'=>$post['mcsingleanswerpoint'],
				'essayanswerpoint'=>$post['essayanswerpoint'],
				'totaltime' =>$totaltime,
				'unlimitedtime' =>$unlimitedtime
  			]);
 			//$this->assessment->assessmentupdate($params);
 			//delete previous questions-answers
 			$assessment_question=AssessmentQuestion::where('assessment_id',$post['id'])->delete();
 			//dd($assessment_details);
 				if($assessment_details){
			foreach ($questions as $value) {
				if($value=='')continue;
				$assessment_question=new AssessmentQuestion();
				$assessment_question->assessment_id=$post['id'];
				$assessment_question->question_id=$value['id'];
				$assessment_question->passage_id=isset( $value['passage_id'] ) ? $value['passage_id'] : 0;
				$assessment_question->save();
			}}
					        \Session::flash('flash_message','Information saved successfully.');

			return Redirect::route('template', ['id' =>  $post['id'] /*, 'tplId' => $newOrder*/]);
			//return redirect('/resources/assessment');
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
		$passage=isset( $post['id'] ) ? $post['id'] : 0;
		$flag=$post['flag'];
		$subjects = $this->question->getassessmentAppendQst($question,$flag,$passage);
		return $subjects;
	}
	public function assessmentQstPassage(){
 		$post = Input::All();
		//dd($post);
  		$passage_id=isset( $post['id'] ) ? $post['id'] : 0;
		$flag= isset($post['flag']) ? $post['flag'] : 0;
		$question_Ids=isset( $post['QuestionIds'] ) ? $post['QuestionIds'] : [0];
   		$subjects = $this->question->getPassageQst($passage_id,$flag,$question_Ids);
		return $subjects;
	}
	public function getPassageByQuestion(){
  		$post = Input::All();
 		$question_Ids=isset( $post['QuestionIds'] ) ? $post['QuestionIds'] : 0;
   		$subjects = $this->question->getPassageByQuestions($question_Ids);
		return $subjects;
	}
	public function getPassageByPassId(){
  		$post = Input::All();
 		$passage_Ids=isset( $post['passage_Ids'] ) ? $post['passage_Ids'] : 0;
		$lessons=isset($post['lessons']) ? $post['lessons'] : 0;
   		$subjects = $this->question->getPassageByPassId($passage_Ids,$lessons);
		return $subjects;
	}
	public function getAddingPassage(){ 
		$post = Input::All();
 		$passageIds=isset( $post['passageIds'] ) ? $post['passageIds'] : 0;
   		$subjects = $this->question->getAddingPassage($passageIds);
		return $subjects;
	}
	public function getAddingPassageSelected(){
  		 $post = Input::All();
		//dd($post);
  		 // dd($post['question_Ids']);
 		$question_Ids=isset( $post['question_Ids'] ) ? $post['question_Ids'] : 0;
		$lessons = isset( $post['lessons'] ) ? $post['lessons'] : 0;
		$qtype= isset( $post['qtype'] ) ? $post['qtype'] : 0;
   		$subjects = $this->question->getAddingPassageSelected($question_Ids,$lessons , $qtype);
		return $subjects;
	}
	public function getRemainQuestionsAfterSelected(){
  		 $post = Input::All();
 		$question_Ids=isset( $post['question_Ids'] ) ? $post['question_Ids'] : 0;
   		$subjects = $this->question->getRemainQuestionsAfterSelected($question_Ids);
		return $subjects;
	}
	public function assessmentFilter(){
		$post = Input::All();
		//dd($post);
 		// $passage_id=$post['id'];
		// $flag=$post['flag'];
		$list=[];
		if($post['question_type']>0) {

			$question_Ids = isset($post['questions']) ? $post['questions'] : 0;
			$institution = $post['institution'];
			$obj = Question::join('question_type', 'questions.question_type_id', '=', 'question_type.id')
				->leftjoin('passage', 'questions.passage_id', '=', 'passage.id');


			if ($institution > 0) {
				$obj->where("questions.institute_id", (int)$institution);
				//$pass->where("questions.institute_id", (int)$institution);
			}
			if ($post['category'] > 0) {
				$obj->where("questions.category_id", (int)$post['category']);
				//$pass->where("questions.category_id", (int)$post['category']);
			}
			if (isset($post['subject'])) {
				//$obj->where("questions.subject_id", $post['subject']);
				if (is_array($post['subject'])) {
					$obj->whereIn("questions.subject_id", $post['subject']);
					//$pass->whereIn("questions.subject_id", $post['subject']);
				} else {
					$obj->where("questions.subject_id", $post['subject']);
					//$pass->where("questions.subject_id", $post['subject']);
				}
			}
			if (isset($post['lessons'])) {
				if (is_array($post['lessons'])) {
					$obj->whereIn("questions.lesson_id", $post['lessons']);
					//$pass->whereIn("questions.lesson_id", $post['lessons']);
				} else {
					$obj->where("questions.lesson_id", $post['lessons']);
					//$pass->where("questions.lesson_id", $post['lessons']);
				}
			}
			if ($question_Ids > 0) {
				$obj->wherenotin("questions.id", $post['questions']);

			}

			//if ($post['question_type'] > 0) {
				$obj->where("question_type.id", (int)$post['question_type']);
			//}
			$list['questions'] = $obj->select('questions.id as qid', 'questions.title as question_title','questions.subject_id as sub_id','questions.lesson_id as less_id', 'passage.id as pid', 'passage.title as passage_title', 'question_type.qst_type_text as question_type')
				->orderby('qid')
				->get();
		}
		//dd($list);
		//$question_list = $this->question->getQuestionFilter($institution);
		return $list;
 	}
 	public function passageAssessmentFilter(){
		$post = Input::All();
		$passageIds=isset( $post['passageIds'] ) ? $post['passageIds'] : 0;
		$institution=$post['institution']; 
		$obj=Passage::join('questions','questions.passage_id','=','passage.id');
 		$obj->where("passage.institute_id", $institution);
 		 
		if($post['category'] > 0){
			$obj->where("passage.category_id", $post['category']);
		}
		if($post['subject'] > 0){
			$obj->where("passage.subject_id", $post['subject']);
		}
		if($post['lessons'] > 0){
			$obj->where("passage.lesson_id", $post['lessons']);
		}
		if($passageIds > 0){
			$obj->wherenotin("passage.id", $post['passageIds']);
		}
		$obj->groupBy('questions.passage_id');
		$list=	$obj->select('passage.id as pass_id', 'passage.title as passage_title')
			// ->orderby('qid')
			->get();
		//$question_list = $this->question->getQuestionFilter($institution);
		return $list;
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
		//dd('question grid');
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
		//dd('passage grid');
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
    public function assessmentdel($aid)
	{
		$ass = Assignment::where('assessment_id', $aid)->count();
		if ($ass == 0) {
		$assessment_question=AssessmentQuestion::where('assessment_id',$aid)->delete();
			Assessment::find($aid)->delete();
			\Session::flash('flash_message', 'delete!');
			return redirect('/resources/assessment');
		}
		else {
			\Session::flash('flash_message_failed', 'Can not Delete this Assessment.');
			return Redirect::back();
		}
	}

}
