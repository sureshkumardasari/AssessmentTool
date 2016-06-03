<?php namespace App\Modules\Grading\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Html\HtmlFacade;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use View;
use App\Modules\Admin\Models\User;
use App\Modules\Admin\Models\Institution;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;
use App\Modules\Resources\Models\Assessment;
use App\Modules\Resources\Models\AssessmentQuestion;
use App\Modules\Assessment\Models\QuestionUserAnswer;
use App\Modules\Assessment\Models\QuestionUserAnswerRetake;

class GradingController extends BaseController {

	

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');

		$obj = new Assignment();
		$this->assignment = $obj;

		$obj = new Institution();
		$this->institution = $obj;

		$obj = new User();
		$this->user = $obj;

		$obj = new AssignmentUser();
		$this->assignmentuser = $obj;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}    
}
