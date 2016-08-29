<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Resources\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Modules\Resources\Models\QuestionAnswer;
use \PHPExcel,
    //\PHPExcel_Style_Fill,
    \PHPExcel_IOFactory,
    \PHPExcel_Style_NumberFormat;
    //\PHPExcel_Reader_Excel5,
    //\PHPExcel_Shared_Date,
    //\PHPExcel_Cell,
    //\PHPExcel_Style_Alignment
    //\PHPExcel_Cell_DataType;
use App\Modules\Admin\Models\Institution;
use App\Modules\Admin\Models\User;
use \Validator;
use Redirect;


class Question extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'questions';
	protected $primaryKey = 'id';

	public function getQuestions($institution_id = 0, $subject_id = 0, $category_id = 0 ,$lesson_id =0)
	{
		//$users = User::get();
		$obj = new Question();
		if($institution_id > 0 || $subject_id > 0 || $subject_id > 0)
		{
			$questions = $obj->where("subject_id", $subject_id)->orWhere('institution_id', $institution_id)->orWhere('category_id', $category_id)->orwhere('lesson_id',$lesson_id)->lists('title', 'id');
		}
		else
		{
			$questions = $obj->lists('title', 'id');
		}
		
		return $questions;
	}

	public function getcategory()
	{
		$states  = DB::table('category')->lists('name','id');
		return $states;
	}

	public function getsubject()
	{
		$states  = DB::table('subject')->lists('name','id');
		return $states;
	}
	public function getlesson()
	{
		$states  = DB::table('lesson')->lists('id');
		return $states;
	}
	public function getquestiontype()
	{
		$states  = DB::table('question_type')->lists('qst_type_text','id');
		return $states;
	}
	public function getpassages($data)
	{
		$lesson_id  = DB::table('lesson')->where('name','=',$data['lesson_name'])->lists('id');
		$passages  = DB::table('passage')->where('lesson_id','=',$lesson_id)->lists('title','id');
		return $passages;
	}
	public function getDetails($id=0){

        $question=DB::table('questions')
            ->join('category', 'category.id', '=', 'questions.category_id')
            ->join('subject', 'subject.id', '=', 'questions.subject_id')
            ->join('lesson','lesson.id','=','questions.lesson_id')
			->join('question_answers','question_answers.question_id','=','questions.id')
            ->join('institution', 'institution.id', '=', 'questions.institute_id')
            ->join('question_type','question_type.id','=','questions.question_type_id')
            ->leftjoin('passage','passage.id','=','questions.passage_id')
            ->where('questions.id', $id)
			//->where('question_answers.is_correct','like','yes')
            ->select('questions.id as id','question_answers.ans_text','lesson.name as lesson_name','questions.title as qstn_title','questions.qst_text','category.name as category_name','subject.name as subject_name','institution.name as inst_name','question_type.qst_type_text','passage.title as psg_title')
            ->first();
		//dd($question);
        return $question;
	}

	public function getassessmentQst($questions=0)
	{
 		$obj = DB::table('questions'); ;
		
		if($questions > 0){
 			$obj->wherein("id", $questions);
		}
 		$questions = $obj->get();
		return $questions;
	}public function getassessmentOldPassage($passage=0)
	{
 		$obj = DB::table('passage'); ;

		if($passage > 0){
 			$obj->wherein("id", $passage);
		}
		$passage = $obj->get();
		return $passage;
	}
	public function getassessmentRemoveOldPassage($passage=0)
	{
		$obj = DB::table('passage'); ;

		if($passage > 0){
			$obj->wherenotin("id", $passage);
		}
		$passage = $obj->get();
		return $passage;
	}
	public function getassessmentAppendQst($questions=0,$flag=0,$passages=0)
	{
		$obj = DB::table('questions');

		if($passages > 0){
			$obj->whereIn("passage_id", $passages);
		}
		if($flag==1){
 			$obj->wherein("id", $questions);
		}else{
 			$obj->wherenotin("id", $questions);
		}
		$questions = $obj->get();
		return $questions;
	}
	public function getPassageQst($passage_id=0,$flag=0,$question_Ids=[0])
	{
 		//$obj = DB::table('questions');
	//	$sql = "select * from `questions` where `id` in (\'7\', \'8\', \'9\', \'10\') and (`passage_id` not in (\'1\') OR passage_id IS NULL OR passage_id=0)";
		//$obj->whereIn('id' , $question_Ids);
		if($flag == 1){
			$obj=DB::select(' select * from questions where id in ('.implode(',',$question_Ids).') and (passage_id  not in ('.implode(',',$passage_id).') OR passage_id IS NULL OR passage_id = 0)');
			//$obj->wherenotIn("passage_id", $passage_id);
			//$obj->Where('passage_id','IS','NULL');
		}else{
			$obj=DB::select('select * from questions where id in ('.implode(',',$question_Ids).') and (passage_id  in ('.implode(',',$passage_id).') OR passage_id IS NULL OR passage_id = 0)');
			//$obj->whereIn("passage_id", $passage_id);
		}
		//dd($obj);
		$questions = $obj; //->get();
 		return $questions;
	}
	public function getPassageByQuestions($question_Ids=0)
	{
  		$obj = DB::table('questions as q');
 		$obj->join('passage as p', 'p.id', '=', 'q.passage_id');
		if($question_Ids > 0){
			$obj->wherein("q.id", $question_Ids);
		}
		$passages = $obj->groupBy('p.title')->get();
   		return $passages;
	}public function getPassageByPassId($passage_Ids=0, $lessons = 0)
	{ 
 		$obj = DB::table('passage as p')->join('questions as q','q.passage_id','=','p.id');
 		//$obj->join('passage as p', 'p.id', '=', 'q.passage_id');
		if($passage_Ids > 0){
			$obj->wherenotin("p.id", $passage_Ids);
		}
		if($lessons > 0){
			$obj->whereIn("p.lesson_id", $lessons);
		}
		$passages = $obj->select('p.id as id','p.passage_text as passage_text','p.title as title')->groupby('id')->get();
  		return $passages;
	}
	public function getAddingPassage($passageIds=0)
	{
   		$obj = DB::table('questions');
		// if($question_Ids > 0){
		// 	$obj->wherein("id", $passageIds);
		// }
 			$obj->wherein("passage_id", $passageIds); 
 		$questions = $obj->get();
 		return $questions;
	}
	public function getAddingPassageSelected($question_Ids=0, $lessons = 0, $qtype = 0)
	{
		$obj = DB::table('questions');
		$questions=[];
		if($qtype > 0){
			if($question_Ids > 0){
				$obj->wherenotin("id", $question_Ids);
			}
			if($lessons > 0){
				$obj->whereIn("lesson_id", $lessons);
			}
			if( $qtype >0 ){
				$obj->where("question_type_id", $qtype);
			}
			$questions = $obj->get();
		}

 		return $questions;
	}
	public function getRemainQuestionsAfterSelected($question_ids=0)
	{
   		$obj = DB::table('questions');
		if($question_ids > 0){
			$obj->wherenotin("id", $question_ids); 
		} 
 		$questions = $obj->get();
 		return $questions;
	}
	public function getassessmentFilter($institution = 0, $category = 0, $subject = 0,$lessons=0,$questions=0)
	{
 		$obj = DB::table('questions'); ;
		if($institution > 0){
 			$obj->where("institute_id", $institution);
		}
		if($category > 0){
			$obj->where("category_id", $category);
		}
		if($subject > 0){
			$obj->where("subject_id", $subject);
		}
		if($lessons > 0){
			$obj->where("lesson_id", $lessons);
		}
		if($questions > 0){
 			$obj->wherenotin("id", $questions);
		}
 		$questions = $obj->get();
		return $questions;
	}
	public function getQuestionFilter($institution = 0, $category = 0, $subject = 0,$lessons=0)
	{
		$obj = DB::table('questions'); ;
		
		if($institution > 0){
			$obj->where("institute_id", $institution);
		}
		if($category > 0){
			$obj->where("category_id", $category);
		}
		if($subject > 0){
			$obj->where("subject_id", $subject);
		}
		if($lessons > 0){
			$obj->where("lesson_id", $lessons);
		}
		$questions = $obj->get();
		return $questions;
	}

	public function getQuestionTypes(){
		
	}

	public function getLessonInfo($id = 0)
	{
		$lesson = Lesson::find($id);
		return $lesson;
	}

	public function deleteLesson($id = 0)
	{
		$lesson = Lesson::find($id);
		$lesson->delete();
	}

	public function updateLesson($params = 0)
	{
		$obj = new Lesson();
		if($params['id'] > 0)
		{
			$obj = Lesson::find($params['id']);	
			$obj->updated_by = Auth::user()->id;			
		}
		else
		{
			$obj->added_by = Auth::user()->id;				
		}
		
		$obj->name = $params['name'];
		$obj->subject_id = $params['subject_id'];
		$obj->institution_id = $params['institution_id'];
		$obj->category_id = $params['category_id'];
		$obj->save();	
	}
	public function updateQuestion($params = 0)
	{
   		$obj = new Question();
		if($params['id'] > 0)
		{
			$obj = Question::find($params['id']);
			$obj->updated_by = Auth::user()->id;
		}
		else
		{
			$obj->added_by = Auth::user()->id;
		}
 		$obj->title = $params['question_title'];
		$obj->qst_text = $params['question_textarea'];
		$obj->question_type_id = $params['question_type'];
		$obj->subject_id = $params['subject_id'];
		$obj->category_id = $params['category_id'];
		$obj->lesson_id = $params['lessons_id'];
		$obj->passage_id = $params['passage'];
		$obj->institute_id = $params['institution_id'];
 		$obj->status =  $params['status'];
		$obj->difficulty_id ='';
 		if($obj->save()){

			if($params['question_type']==3){
				$params['explanation']=[];
				$params['is_correct']=[];
				$params['answer_textarea']=[];
			}
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

	}

	public function deleteQuestions($id = 0){
		$question = Question::find($id);
		$question->delete();
	}
	public function getRoleIdByRole($userType = '')
	{
		if($userType != '')
		{
			$roles = DB::table('roles')->where("name", $userType)->get();
			return $roles[0]->id;
		}
		else
			return 0;
	}
	public function questionBulkTemplate($data,$filename, $userType, $institution_id, $addSubjects = false, $findInstituteId = false) {
//dd($institution_id);
		$cat_data=$data['category_name'];
	    $sub_data=$data['subject_name'];
	    $les_data=$data['lesson_name'];
	    $qt_data=$data['question_type'];
	    $objPHPExcel = new PHPExcel();

	    //$states = ['AndhraPradesh','Telangana'];

	    $institue = new Institution();
	    $madeDataValidationColumn = array();
	    if ($institution_id == null) {
	        $institues =$institue::orderby('id', 'desc')->take(100)->lists('id');
	    } else {
	        if($findInstituteId){
	            $institues = $institue->where('id', $institution_id)->lists('id');
	            $madeDataValidationColumn = array();
	        }else{
	            $institues = $institue->where('id', $institution_id)->lists('id');
	        }
	    }
	    $lesson=$this->getlesson();
	    $category=$this->getcategory();
	    $subject=$this->getsubject();
	    $question_type=$this->getquestiontype();
	    $passage=$this->getpassages($data);
	  //dd($passage);
	//Create Validation for School and State
	    $objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
	    $indexSchool = 1;
	    $indexState = 1;

	//     foreach ($states as $row) {
	//         $objWorkSheet->setCellValue('A' . $indexState, $row);
	//         $indexState++;
	//     }

	//     foreach ($institues as $row) {
	//         $objWorkSheet->setCellValue('B' . $indexSchool, $row);
	//         $indexSchool++;
	//     }

	//     $objWorkSheet->setCellValue('D1', $userType);
	// // Rename sheet
	//     $objWorkSheet->setTitle("options");
	// //Set Protection
	//     $objWorkSheet->getProtection()->setSheet(true);
	//     $objWorkSheet->getProtection()->setSort(true);
	//     $objWorkSheet->getProtection()->setInsertRows(true);
	//     $objWorkSheet->getProtection()->setFormatCells(true);
	//     $objWorkSheet->getProtection()->setPassword('password');
	    //dd($sub_data);
	    $exportFields = array(
	    	'Institution' => array('value'=>[$institution_id]),
	    	'Category' => array('value'=>[$cat_data]),
	        'Subject' => array('value'=>[$sub_data]),
	        'Lessons' => array('value'=>[$les_data]),
	        'Question Type' =>array('value'=>[$qt_data]),
 	    	'Question Tittle' => array(),      
	    	'Question Text' => array(),
	     	'Passage' =>array('options'=>$passage),

	     'Answer Text1' => array(),
	     'Order Id1' =>array(),
	     'Is correct1' => array('options' => ['YES','NO']),
	     'Explanation1' => array(),
	          'Answer Text2' => array(),
	          'Order Id2' =>array(),
    	      'Is correct2' => array('options' => ['YES','NO']),
	          'Explanation2' => array(),
	      'Answer Text3' => array(),
	      'Order Id3' =>array(),
	      'Is correct3' => array('options' => ['YES','NO']),
	      'Explanation3' => array(),
	          'Answer Text4' => array(),
	          'Order Id4' =>array(),
	          'Is correct4' => array('options' => ['YES','NO']),
	          'Explanation4' => array(),
	      'Answer Text5' => array(),
	      'Order Id5' =>array(),
	      'Is correct5' => array('options' => ['YES','NO']),
	      'Explanation5' => array(),
	        'Status' => array('options' => array('Active', 'Inactive'))
 	    );

 	    //dd($exportFields);

	    $firstRow = false;
	    $celli = 'A';
	    $rowsToFill = 100;
	    foreach ($exportFields as $field => $options) {
	        $objPHPExcel->getActiveSheet()->setCellValue($celli . '1', $field);
	        $objPHPExcel->getActiveSheet()->getStyle($celli . '1:' . $celli . $rowsToFill)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

	        if (is_array($options) && isset($options['options'])) {
	            if (isset($options['multiselect']) && $options['multiselect'] == true) {
	                for ($j = 0; $j < count($options['options']); $j++) {
	                    $objPHPExcel->getActiveSheet()->setCellValue($celli . '1', $field . '-' . $options['options'][$j]);

	                    for ($i = 2; $i <= $rowsToFill; $i++) {
	                        $objValidation = $objPHPExcel->getActiveSheet()->getCell($celli . $i)->getDataValidation();
	                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
	                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
	                        $objValidation->setAllowBlank(false);
	                        $objValidation->setShowInputMessage(true);
	                        $objValidation->setShowErrorMessage(true);
	                        $objValidation->setShowDropDown(true);
	                        $objValidation->setErrorTitle('Input error');
	                        $objValidation->setError('Value is not in list.');
	                        $objValidation->setPromptTitle('Pick ' . $field);
	                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
	                        $objValidation->setFormula1('"X"');
	                    }
	                    if ($j != count($options['options']) - 1)
	                        $celli++;
	                }
	            }else {

	                for ($i = 2; $i <= $rowsToFill; $i++) {
	                    $objValidation = $objPHPExcel->getActiveSheet()->getCell($celli . $i)->getDataValidation();
	                    $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
	                    $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
	                    $objValidation->setAllowBlank(false);
	                    $objValidation->setShowInputMessage(true);
	                    $objValidation->setShowErrorMessage(true);
	                    $objValidation->setShowDropDown(true);
	                    $objValidation->setErrorTitle('Input error');
	                    $objValidation->setError('Value is not in list.');
	                    $objValidation->setPromptTitle('Pick ' . $field);
	                    $objValidation->setPrompt('Please pick a value from the drop-down list.');
	                    $objValidation->setFormula1('"' . implode(',', $options['options']) . '"');

	                    if (isset($options['validation'])) {
	                        if (($options['validation'] == 'state') && $indexState > 1) {
	                            $objValidation->setFormula1('options!$A$1:$A$' . ($indexState - 1));
	                        }
	                        if (($options['validation'] == 'school') && $indexSchool > 1) {
	                            $objValidation->setFormula1('options!$B$1:$B$' . ($indexSchool - 1));
	                        }
	                    }
	                }
	            }
	        }

	        $celli++;
	    }
	    if($findInstituteId && !empty($institues[0])){
	        $objPHPExcel->getActiveSheet()->setCellValueExplicit('A2', $institues[0], \
	        	
	        	PHPExcel_Cell_DataType::TYPE_STRING);
	    }
	      if(!empty($cat_data)){
	        $objPHPExcel->getActiveSheet()->setCellValueExplicit(
	        	'B2', $cat_data, \
	  
	        	PHPExcel_Cell_DataType::TYPE_STRING);
	    }
	      if(!empty($sub_data)){
	        $objPHPExcel->getActiveSheet()->setCellValueExplicit(
	        	'C2', $sub_data, \
	        	
	        	PHPExcel_Cell_DataType::TYPE_STRING);
	    }
	      if(!empty($les_data)){
	        $objPHPExcel->getActiveSheet()->setCellValueExplicit(
	        	'D2', $les_data, \
	        	PHPExcel_Cell_DataType::TYPE_STRING);
	    }
	     if(!empty($qt_data)){
	        $objPHPExcel->getActiveSheet()->setCellValueExplicit(
	        	'E2', $qt_data, \
	        	PHPExcel_Cell_DataType::TYPE_STRING);
	    }
	    $highestColumn = User::createColumnsArray($objPHPExcel->getActiveSheet()->getHighestColumn());
	    foreach ($highestColumn as $columnID) {
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    }

	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		if (!is_dir(public_path() . '/data/tmp')) {
			mkdir(public_path() . '/data/tmp', 0777);
			chmod(public_path() . '/data/tmp', 0777);
		}

	    $save = $objWriter->save(public_path() . '/data/tmp/' . $filename);
	    return $save;
	}
	public static function createBulkQuestion($row)
	{	
	//dd($row);
		$institution_id = 0;
		if(isset($row->institution)){
			$institution_id=Institution::where('id',$row->institution)->first()->id;
		}
		$category_id = 0;
		if(isset($row->category)){
			$category_id=Category::where('name',$row->category)->first()->id;
		}
		$subject_id = 0;
		if(isset($row->subject)){
			$subject_id=Subject::where('name',$row->subject)->first()->id;
		}
	$lesson_id = 0;
		if(isset($row->lessons)){
			$lesson_id=Lesson::where('name',$row->lessons)->first()->id;
		}
	$question_type_id = 0;
		if(isset($row->question_type)){
			$question_type_id=QuestionType::where('qst_type_text',$row->question_type)->first()->id;
		}
		//$passage_id = null;
		if(empty($row->passage)){
			$passage_id = null;
			/*$passage_id=Passage::where('title',$row->passage)->first()->id; //dd($passage_id);*/
		}
		else{
			$passage_id=Passage::where('title',$row->passage)->first()->id; //dd($passage_id);
		}
		$status=$row->status;
		 $status = ($status=='Active') ? 1: 0;
			
		$obj = new self;
		//dd($question_type_id);
		
		
				$obj->title = $row->question_tittle;
				$obj->qst_text =$row->question_text;
				$obj->question_type_id = $question_type_id;
				$obj->subject_id = $subject_id;
				$obj->lesson_id = $lesson_id;
				$obj->passage_id = $passage_id;
				$obj->category_id = $category_id;
				$obj->institute_id = $institution_id;
				$obj->status=$status;
				if($obj->save()){
				
						$answer = new QuestionAnswer();

						$last_id=$obj->id;
						$answer->question_id = $last_id;
	
						$answer_text_result=[];
				
						$data=['answer_text','order_id','is_correct','explanation'];
 						
 						for($i=1;$i<=5;$i++){
 							if($row->{'answer_text'.$i} != NULL){
 							$answer = new QuestionAnswer();
 							//$last_id=$obj->id;
 							$answer->question_id = $last_id;
 							$answer->ans_text = $row->{'answer_text'.$i}; 	
 							$answer->explanation = $row->{'explanation'.$i};
							$answer->order_id = $row->{'order_id'.$i};
							$answer->is_correct = $row->{'is_correct'.$i};
							$answer->save();
						}
						}

					}
	
		}
	
	public static function validateBulUpload($fileType, $data, $index) {
	    $error = array();
 	    $dataArr = $data->toArray();
		    
	    $validationRule = [
	        'institution' => 'required|numeric',
 	        'category'=> 'required',
	        'subject'=> 'required',
	        'lessons'=> 'required',
	        'question_tittle' => 'required',
	        'question_text' => 'required',
	        'question_type'=> 'required',
 			'status' => 'required',  
	        						
	    ];	
	    $check_corret_answer = array();
	    $order= array();
	    $ans_text = array();
	    $counts = array();
	    //dd($counts);
	   // dd($check_corret_answer);
	   
	      $messages = [];

	  for($i=1;$i<=5;$i++)
	 { 		
 	 	if((
 	 		($data->{'answer_text'.$i} == "") and ($data->{'order_id'.$i} == "") and ($data->{'is_correct'.$i} == "") ) or (($data->{'answer_text'.$i})  and ($data->{'order_id'.$i}) and ($data->{'is_correct'.$i})))
 	 	{

	 	}else{

	 		$error[]=array('Row #' => $index, 'Error Description' =>  'Answer Text'.$i.'is required');
	 		$error[]=array('Row #' => $index, 'Error Description' =>  'The Order Id'.$i.'is required');
	 		$error[]=array('Row #' => $index, 'Error Description' =>  'The Is Correct'.$i.'is required');
 	 		// $error[] = array('The Answer Text'.$i.'is required');
	 		// $validationRule[$data->{'answer_text'.$i}] = 'required';
	 	}
	 	/*if($data->{'order_id'.$i}){

	 	}else{
	 		$error[]=array('Row #' => $index, 'Error Description' =>  'The Order Id'.$i.'is required');
	 		// $error[] = array('The Order Id'.$i.'is required');
	 		// $validationRule[$data->{'order_id'.$i}] = 'required';
	 	}
	 	if($data->{'is_correct'.$i}){

	 	}else{
	 		$error[]=array('Row #' => $index, 'Error Description' =>  'The Is Correct'.$i.'is required');
			// $error[] = array('The Is correct'.$i.'is required');

	 		// $validationRule[$data->{'is_correct'.$i}] = 'required';
	 	}*/
						
			if($data->{'is_correct'.$i} != ""){
			$check_corret_answer[] = $data->{'is_correct'.$i};
			
			}
			if($data->{'answer_text'.$i} != "")
			{
				$ans_text[]=$data->{'answer_text'.$i};
			}
			if($data->{'order_id'.$i} != "")
			{
				$order[]=$data->{'order_id'.$i};
			}

			//dd($check_corret_answer);
		}	
		// $records[]=array($check_corret_answer,$order,$ans_text);
		//dd($records);
		/*if (isset($ans_text) != "") {
						
					}	*/		
	    $question_type_id=QuestionType::where('qst_type_text',$data->question_type)->first()->id;
	     //  dd($question_type_id);
	  if($question_type_id==3)
	   {
			$data->{'answer_text'.$i}=array();
			 $data->{'explanation'.$i}=array();
			$data->{'order_id'.$i}=array();
			$data->{'is_correct'.$i}=array();
		}
		
		// if($post['ans_flg']>0)
		
		//	$check_corret_answer[] = $data->{'is_correct'.$i};
		//dd($check_corret_answer);
			if($question_type_id==2)
			{ 
				$counts = array_count_values($check_corret_answer);
					//dd($counts);
				if(array_key_exists("YES", $counts))
				{
					$tmp_cnt =  $counts['YES'];//dd($tmp_cnt);
					if($tmp_cnt != 1 )
					{
						$error[]=array('Row #' => $index, 'Error Description' =>  'Only one correct answer is required');
						// $error[] = array('Only one correct answer is required');
					}
				}
			else
				{
					$error[]=array('Row #' => $index, 'Error Description' =>  'Atleast one correct answer is required');
					// $error[] = array('Atleast one correct answer is required');
				}
			}
			if($question_type_id==1)
			{ 
				$counts = array_count_values($check_corret_answer);
				if(array_key_exists("YES", $counts))
				{
					$tmp_cnt =  $counts['YES'];
					if($tmp_cnt>=2)
					{

					}else
					{
						$error[]=array('Row #' => $index, 'Error Description' =>  'Atleast two correct answer is required');
						// $error[] = array('Atleast two correct answers are required');
					}
				}else
				{
					$error[]=array('Row #' => $index, 'Error Description' =>  'Atleast two correct answer is required');
					// $error[] = array('Atleast two correct answers are required');
				}
			}
			/*if ($question_type_id==1 && count($tmp_cnt) < 2)
			{
				$error[]=array('Row #' => $index, 'Error Description' =>  'Atleast two correct answer is required');
				// $error[] = array('The Atleast Two Answers are required');
			}*/

			
			
		
	
	//	dd($check_corret_answer);

	    $validator = Validator::make($dataArr, $validationRule, $messages);

	    if ($validator->fails()) {
	        $messages = $validator->messages();
	        foreach ($messages->all() as $row) {
	            $error[] = array('Row #' => $index, 'Error Description' => $row);
	        }
	    }
	    
	  
	     return $error;
	}



		
}
