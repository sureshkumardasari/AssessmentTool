<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Modules\Resources\Models\Assignment;
use App\Modules\Resources\Models\AssignmentUser;
use Input;
use Auth;
class ProctorDashboard extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$proctor= Auth::user();
		//dd($proctor);
		if (Auth::user()->role_id == 4) {
			$proctor_assignments=Assignment::where('institution_id','=',$proctor->institution_id)->where('proctor_user_id',$proctor->role_id)->where('status','<>',"completed")->get();
			//dd($proctor_assignments);
		}else
		{

		//$ass=Assignment::where('proctor_user_id',$proctor->id)->lists('id');//->get();

		$proctor_assignments=Assignment::where('proctor_user_id',$proctor->id)->where('status','<>',"completed")->get();
//        if(count($proctor_assignments)!=0){
		//$now=date("Y-m-d H:i:s");
		//dd($proctor_assignments.",".$now);
//        }
		}
		   if (Auth::user()->role_id == 2) {
         $message = "You don't have permission";
           \Session::put("success", $message);
            return redirect()->route('home');
        }
		return view('dashboard::proctor_dashboard.view',compact('proctor_assignments'));
	}

	public function launchAssgnmentSetInstructions($assignment_id=0){
       if(Auth::user()->role_id != 2){
		if($assignment_id!=0){
			Assignment::where('id',$assignment_id)->update(['status'=>"instructions"]);
			AssignmentUser::where('assignment_id',$assignment_id)->update(['status'=>'instructions']);
		}
		return redirect('launch_test_by_proctor/start_test/'.$assignment_id);//->with('assignment_users',$assignment_users);
		//    return view("dashboard::proctor_dashboard.test_start",compact('assignment_users'));
	}
		 else
    {
          return view('permission');
    }
	
	}
	public function startTestT($assignment_id){
		if(Auth::user()->role_id == 3){
		$status=['inprogress','test'];
		$assignment_not_started_users=AssignmentUser::join('users',"assignment_user.user_id",'=',"users.id")->where("assignment_id",$assignment_id)->where('assignment_user.status',"instructions")->select('user_id','assignment_user.status','users.name as user_name')->get();
		$assignment_started_users=AssignmentUser::join('users',"assignment_user.user_id",'=',"users.id")->where("assignment_id",$assignment_id)->whereIn('assignment_user.status',$status)->select('user_id','assignment_user.status','users.name as user_name')->get();
		$assignment_completed_users=AssignmentUser::join('users',"assignment_user.user_id",'=',"users.id")->where("assignment_id",$assignment_id)->where('assignment_user.status',"completed")->select('user_id','assignment_user.status','users.name as user_name')->get();
		return view("dashboard::proctor_dashboard.test_start",compact('assignment_not_started_users','assignment_started_users','assignment_completed_users','assignment_id'));
	}
		 else
    {
   return view('permission');
    }
	}
	

	public function updateStatus(){
		$post=Input::all();
		if(isset($post['to_start_students'])){
			foreach($post['to_start_students'] as $user){
				AssignmentUser::where('assignment_id',$post['assignment_id'])->where('user_id',$user)->update(['status'=>"test"]);
			}
			if($post['already_started_students']==0){
				Assignment::where('id',$post['assignment_id'])->update(['status'=>'inprogress']);
			}

			return "ok";
		}
		//else return "no";
		elseif(isset($post['to_stop_students'])){
			foreach($post['to_stop_students'] as $user){
				AssignmentUser::where('assignment_id',$post['assignment_id'])->where('user_id',$user)->update(['status'=>"completed"]);
			}
			$all_users=AssignmentUser::where('assignment_id',$post['assignment_id'])->get()->count();
			$completed_users=AssignmentUser::where('assignment_id',$post['assignment_id'])->where('status','completed')->get()->count();
			if($all_users==$completed_users){
				Assignment::where('id',$post['assignment_id'])->update(['status'=>"completed"]);
				//return "Assignment Completed";
			}
			return "ok";
		}
		else return "no";

	}

}
