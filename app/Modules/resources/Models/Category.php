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
		//$obj = new Category();
		$obj = DB::table('category'); 
		
		if($institution_id > 0)
		{
			$obj->where('institution_id', $institution_id);								
		}			
		$category = $obj->lists('name', 'id');
		
		return $category;
	}

	public function getCategoryInfo($id = 0)
	{
		$category = Category::find($id);
		return $category;
	}

	public function deleteCategory($id = 0)
	{
		$category = Category::find($id);
		$category->delete();
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
