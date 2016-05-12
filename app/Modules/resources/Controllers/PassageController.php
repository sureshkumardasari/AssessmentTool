<?php namespace App\Modules\Resources\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Html\HtmlFacade;

use Zizaco\Entrust\EntrustFacade;

use Zizaco\Entrust\Entrust;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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
		
	}

    

	public function passage($parent_id = 0)
	{
		$passages = $this->passage->getPassage();
        return view('resources::passage.list',compact('passages'));
	}

	public function passageadd()
	{		
		
		//dd("123");
		//$inst_arr = $this->institution->getInstitutions();

		$id = 0;
		$passage = new passage();
		return view('resources::passage.edit',compact('passage'));
	}

	public function passageedit($id = 0)
	{		
		//$inst_arr = $this->passage->getPassages();

		if(isset($id) && $id > 0)
		{
			$passage = $this->passage->find($id);		
		}
		else
		{
			$passage = Input::All();			
		}
		
		return view('resources::passage.edit',compact('passage'));
	}

	public function passageupdate($id = 0)
	{
		$params = Input::All();
		
		$this->passage->updatepassage($params);

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
