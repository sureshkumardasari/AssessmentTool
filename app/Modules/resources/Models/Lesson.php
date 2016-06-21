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

class Lesson extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'lesson';
	protected $primaryKey = 'id';

	public function getLesson($institution_id = 0, $category_id = 0, $subject_id = 0)
	{
		//$users = User::get();
		$obj = DB::table('lesson'); //new Lesson();
		if($institution_id > 0 || $category_id > 0 || $subject_id > 0)
		{
			//$lessons = $obj->where("subject_id", $subject_id)->where('institution_id', $institution_id)->where('category_id', $category_id)->lists('name', 'id');
			if($institution_id > 0)
			{
				$obj->where('institution_id', $institution_id);
				if($category_id > 0)
				{
					$obj->where('category_id', $category_id);
					if($subject_id > 0)
						$obj->where('subject_id', $subject_id);
				}	
			}
			$lessons = $obj->lists('name', 'id');
		}
		else
		{
			$lessons = $obj->lists('name', 'id');
		}
		
		return $lessons;
	}public function getSubjectCategoryLesson($institution_id = 0, $category_id = 0, $subject_id = 0)
	{
		//$users = User::get();
 		$obj = DB::table('lesson as l'); //new Lesson();
		$obj->join('category as c', 'c.id', '=', 'l.category_id');
		$obj->join('subject as s', 's.id', '=', 'l.subject_id');
		if($institution_id > 0 || $category_id > 0 || $subject_id > 0)
		{
			//$lessons = $obj->where("subject_id", $subject_id)->where('institution_id', $institution_id)->where('category_id', $category_id)->lists('name', 'id');
			if($institution_id > 0)
			{
				$obj->where('l.institution_id', $institution_id);
				if($category_id > 0)
				{
					$obj->where('l.category_id', $category_id);
					if($subject_id > 0)
						$obj->where('l.subject_id', $subject_id);
				}
			}
			$lessons  = $obj->select('l.category_id as l_cat_id', 's.category_id as s_cat_id','l.name as l_name', 's.name as subject_name','c.name as cat_name', 's.id','c.id')->get();
		}
		else
		{
			$lessons  = $obj->select('l.category_id as l_cat_id', 's.category_id as s_cat_id','l.name as l_name', 's.name as subject_name','c.name as cat_name', 's.id','c.id')->get();
		}

		return $lessons;
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
}
