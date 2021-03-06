<?php

/**
 * Report Model
 * 
 * Hoses all the business logic relevant to the reports
 */

namespace App\Modules\Admin\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'role_user';
	protected $primaryKey = 'id';
	public  $timestamps = false;
}
