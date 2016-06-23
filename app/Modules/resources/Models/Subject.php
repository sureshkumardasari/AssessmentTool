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

class Subject extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'subject';
	protected $primaryKey = 'id';

	public function getSubject($category_id = 0)
	{		
		//$obj = new Subject();
		//dd($institution_id ."-----". $category_id);
		$obj = DB::table('subject');
		if($category_id > 0)
		{
			//$subjects = $obj->where("institution_id", $institution_id)->where("category_id", $category_id)->lists('name', 'id');
			
					$obj->where('category_id', $category_id);					

			  $subjects = $obj->lists('name', 'id');
			//dd($subjects);

		}
		else
		{
			$subjects = $obj->lists('name', 'id');
		}

		return $subjects;
	}

  public function getSubjectCategory($institution_id = 0, $category_id = 0)
      {
           //$obj = new Subject();
         $obj = DB::table('subject as s');
          $obj->join('category as c', 'c.id', '=', 's.category_id');
          if($institution_id > 0 || $category_id > 0)
          {
               //$subjects = $obj->where("institution_id", $institution_id)->where("category_id", $category_id)->lists('name', 'id');
              if($institution_id > 0)
             {
                  $obj->where('s.institution_id', $institution_id);
                 if($category_id > 0)
                  {
                       $obj->where('category_id', $category_id);
                  }
              }
              $subjects = $obj->select('s.name as subject_name','c.name as cat_name', 's.id as s_id','c.id','category_id')->get();
 
          }
          else
          {
              $subjects = $obj->select('s.name as subject_name','c.name as cat_name', 's.id as s_id','c.id','category_id')->get();
          }
            return $subjects;
       }
   
    


	public function getSubjectInfo($id = 0)
	{
		$subject = Subject::find($id);
		return $subject;
	}

	public function deleteSubject($id = 0)
	{
		$subject = Subject::find($id);
		$subject->delete();
	}

	public function updateSubject($params = 0)
	{
		$obj = new Subject();
		if($params['id'] > 0)
		{
			$obj = Subject::find($params['id']);
			$obj->updated_by = Auth::user()->id;				
		}
		else
		{
			$obj->added_by = Auth::user()->id;				
		}
		$obj->institution_id = $params['institution_id'];
		$obj->category_id = $params['category_id'];
		$obj->name = $params['name'];
		$obj->save();	
	}

	public function getCategory()
	{
		$category = ['1' => 'Compititive'];
		return $category;
	}
}
