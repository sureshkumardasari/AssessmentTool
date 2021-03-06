<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\state;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth\routes;
use Input;
use Validator;
use Redirect;
use Mail;
use DB;
class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email', 'password' => 'required',
		]);
		Session::set('starttestT', 0);

		$credentials = $request->only('email', 'password');
		$credentials['status'] = 'Active';
		
		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
            if (!is_dir(public_path('data/'))) {
                @mkdir(public_path('data/'), 0777 ,true);
                @chmod(public_path('data/'), 0777 ,true);
            }
            Session::set('starttestT', 0);
            return redirect()->intended($this->redirectPath());
		}

		return redirect($this->loginPath())
					->withInput($request->only('email', 'remember'))
					->withErrors([
						'email' => $this->getFailedLoginMessage(),
					]);
	}

	/**
	 * Get the failed login message.
	 *
	 * @return string
	 */
	protected function getFailedLoginMessage()
	{
		return 'These credentials do not match our records.';
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postRegister(Request $request)
	{
		/*$validator = $this->registrar->validator($request->all());

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}

		//$this->auth->login($this->registrar->create($request->all()));
		//$this->redirectTo = '/user/profile';
		$this->registrar->create($request->all());*/
		ini_set('max_execution_time', 0);	
		$post = Input::All();
		$rules = [
			//'institution_id' =>'required|not_in:0',
			//'role_id' =>'required|not_in:0',
			// 'name' => 'required|min:3|unique:users',
			'first_name' =>'required|min:3',
			'last_name' =>'required',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
			 // 'confirm password' => 'required',
			'enrollno' => 'required',
			'address1' =>'required',
			'city' =>'required',
			'state' =>'required|not_in:0',
			'pincode' => 'required|regex:/\b\d{6}\b/',
			'phoneno' =>array('required','numeric','regex: /^\d{10}$/'),
			'country_id' =>'required|not_in:0',
			//'status' => 'required',
			'gender' => 'required'];


		$validator = Validator::make($post, $rules);
		if ($validator->fails())
		{
			return Redirect::back()->withInput()->withErrors($validator);
		}
		else {
			$params = Input::All();
			$userObj = new User();
			$params['id']=0;
			$params['role_id']=0;
			$params['institution_id']=0;
			$params['profile_picture']=0;
			$params['pic_coords']=0;
			$params['status']='Inactive';
			//var_dump($params);
			$userObj->updateUser($params);
			            $create = array(
                            'name'=>$params['first_name'],
                            'email'=>$params['email'],
                            );
                    $data = array(         
                            'email' =>$params['email'],
                           'name' =>$params['first_name'],
 
                    );

            if(getenv('mail_send')=='yes')
            {
                Mail::send('emails.user_mail', $data, function ($message) use ($create) {
                    $message->to($create['email'], $create['name'])->subject('Appstek Team');
                });
            }

		}
        return view('auth.registersuccessmsg');
	}
public function permission()
 {
  return view('grading::permission');

 }
	public function getRegister()
	{
		$stateObj =new User();
		$countryObj =new User();
		/*$state_arr = $stateObj->getstates();*/
		$state_arr = [];
		$country_arr = $countryObj->getcountries();
		$country_id = 0;
		$state = 0;
		return view('auth.register' , compact('country_arr','country_id','state_arr','state'));
	}
	 function state($country_id=0)
    {
    	//dd($country_id);
    	
        $state_arr=DB::table('states')->where('country_id','=',$country_id)->select('id','state_name as name')->get();
          return $state_arr;    
    }
}