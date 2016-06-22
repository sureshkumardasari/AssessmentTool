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

class Passage extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'passage';
	protected $primaryKey = 'id';

	public function getpassage($passage_id = 0)
	{
		//$users = User::get();
		$obj = new Passage();
		if($passage_id > 0)
		{
			$passages = $obj->where("id", $passage_id)->lists('title', 'id');
		}
		else
		{
			$passages = $obj->lists('title', 'id');
		}
		
		return $passages;
	}

	public function getpassageInfo($id = 0)
	{
		$passage = Passage::find($id);
		return $passage;
	}

	public function deletepassage($id = 0)
	{
		$passage = Passage::find($id);
		$passage->delete();
	}

	public function updatepassage($params = 0)
	{
		$obj = new Passage();
		if($params['id'] > 0)
		{
			$obj = Passage::find($params['id']);
			$obj->updated_by = Auth::user()->id;				
		}
		else
		{
			$obj->added_by = Auth::user()->id;				
		}
		$obj->id = $params['id'];
		$obj->title = $params['passage_tite'];
		$obj->passage_text = $params['passage_text'];
		$obj->passage_lines = $params['passage_lines'];
		$obj->status = $params['status'];
		$obj->subject_id = $params['subject_id'];
		$obj->lesson_id = $params['lessons_id'];
		$obj->institute_id = $params['institution_id'];
		$obj->save();	
	}
}
