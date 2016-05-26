<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */


namespace App\Modules\Admin\Models;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Institution extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'institution';
	protected $primaryKey = 'id';

	public function getInstitutions($parent_id = 0)
	{
		//$users = User::get();
		$obj = new Institution();
		if($parent_id > 0)
		{
			$institutions = $obj->where("id", $parent_id)->orWhere('parent_id', $parent_id)->lists('name', 'id');
		}
		else
		{
			$institutions = $obj->lists('name', 'id');
		}
		
		return $institutions;
	}

	public function getInstitutionInfo($id = 0)
	{
		$institution = Institution::find($id);
		return $institution;
	}

	public function deleteInstitution($id = 0)
	{
		$institution = Institution::find($id);
		$institution->delete();
	}
	public  function getcountries()
	{
		$countries = DB::table('countries')->lists('country_name', 'id');
		return $countries;
	}
	public function updateInstitution($params = 0)
	{
		$obj = new Institution();
		if($params['id'] > 0)
		{
			$obj = Institution::find($params['id']);				
			$obj->updated_by = Auth::user()->id;	
		}
		else
		{
			$obj->added_by = Auth::user()->id;
		}
		
		$obj->name = $params['name'];
		$obj->parent_id = $params['parent_id'];
		
		$obj->address1 = $params['address1'];
		$obj->address2 = $params['address2'];
		$obj->address3 = $params['address3'];
		$obj->city = $params['city'];
		$obj->state = $params['state'];
		$obj->country_id = $params['country_id'];
		$obj->phoneno = $params['phoneno'];
		$obj->pincode = $params['pincode'];

		$obj->save();	
	}
}
