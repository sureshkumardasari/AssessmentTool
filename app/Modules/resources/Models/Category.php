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

class Category extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category';
	protected $primaryKey = 'id';

	public function getCategory($institution_id = 0)
	{
		//$users = User::get();
		$obj = new Category();
		if($institution_id > 0)
		{
			$subjects = $obj->where("institution_id", $institution_id)->lists('name', 'id');
		}
		else
		{
			$subjects = $obj->lists('name', 'id');
		}
		
		return $subjects;
	}

	public function getCategoryInfo($id = 0)
	{
		$subject = Category::find($id);
		return $subject;
	}

	public function deleteCategory($id = 0)
	{
		$subject = Category::find($id);
		$subject->delete();
	}

	public function updateCategory($params = 0)
	{
		$obj = new Category();
		if($params['id'] > 0)
		{
			$obj = Category::find($params['id']);
			$obj->updated_by = Auth::user()->id;				
		}
		else
		{
			$obj->added_by = Auth::user()->id;				
		}
		$obj->institution_id = $params['institution_id'];
		$obj->name = $params['name'];
		$obj->save();	
	}
}
