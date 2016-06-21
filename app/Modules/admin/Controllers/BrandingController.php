<?php namespace App\Modules\Admin\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Admin\Models\Branding;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Image;
class BrandingController extends Controller {



	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	}
	public function add()
	{
		$brandingInstitutions=Branding::lists('institution_id');
		//dd($brandingInstitutions);
		$brandingIds=Branding::lists('id','institution_id');
		//dd($brandingIds);
		$inst_arr=Institution::whereNotIn('id',$brandingInstitutions)->lists('id');
		// dd($inst_arr);
		$InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		//$inst_arr=Institution::get();
		return view('admin::branding.brand',compact('inst_arr','brandingInstitutions','brandingIds'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create( Request $request)
	{
		$post = Input::All();
		$messages = [
			'institution_id.required' => ' Institution Name  is required',
			//'title.required' => 'Enter Name of the Title',
			'hbcolor.required' => 'BackGround color is required  ',
			'headertc.required' => ' HeadTextColor is required',
			'boxhbc.required' => ' BoxTextColor is required',
			'boxhtcolor.required' => ' BoxHeaderColor is required',
			'btextc.required' => ' BoxTextColor is required',
			'buttonc.required' => ' ButtonColor is required',
			'buttontc.required' => ' ButtonTextColor is required',
		];
		$rules = [
			'institution_id' => 'required',
			//'title'=>'required|unique:title',
			'hbcolor' => 'required',
			'headertc' => 'required',
			'boxhbc' => 'required',
			'boxhtcolor' => 'required',
			'btextc' => 'required',
			'buttonc' => 'required',
			'buttontc' => 'required',
		];
		$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$filename = '';
		if($request->hasFile('image')) {
			// $file = Input::file('image');
			// $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
			// $name = $timestamp . '-' . $file->getClientOriginalName();
			// $image->filePath = $name;
			// $file->move(public_path() . '/brandingimages/', $name);
			//dd($file);


			$file = Input::file('image');
			$filename = time() . '.' . $file->getClientOriginalExtension();
			if (!is_dir(public_path('/data/brandingimages/'))) {
				@mkdir(public_path('/data/brandingimages/', 0777, true));
			}
			$path = public_path('/data/brandingimages/' . $filename);


			Image::make($file->getRealPath())->resize(200, 200)->save($path);
		}
		$createArr = [
			//'title' => $post['title'],
			'filepath' => $filename,
			'header_bg_color' => $post['hbcolor'],
			'header_text_color' => $post['headertc'],
			'box_header_bg_color' => $post['boxhbc'],
			'box_header_text_color' => $post['boxhtcolor'],
			'box_text_color' => $post['btextc'],
			'button_bg_color' => $post['buttonc'],
			'button_text_color' => $post['buttontc'],
			'institution_id' => $post['institution_id'],
		];
		
		$branding = Branding::create($createArr);
		\Session::flash('success', 'Successfully added.');
		return Redirect::route('branding-view');
	}



	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	/*public function store(Request $request)
	{
        $image = new Branding();
        $this->validate($request, [
//            'title' => 'required',
            'image' => 'required'
        ]);
        $image->title = $request->title;
        $image->description = $request->description;
		if($request->hasFile('image')) {
            $file = Input::file('image');
            //getting timestamp
            $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());

            $name = $timestamp. '-' .$file->getClientOriginalName();

            $image->filePath = $name;

            $file->move(public_path().'/images/', $name);
        }
        $image->save();
        return $this->create()->with('success', 'Image Uploaded Successfully');
	}*/
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function display()
	{
//		$branding=Branding::join('institution as i','brandings.institution_id','=','i.id')
//		->select('i.id as institution_id','brandings.id','i.name as institution_name','brandings.title')->get();
//		return view('admin::branding.brandview',compact('branding'));
		$branding = Branding::join('institution as i', 'brandings.institution_id', '=', 'i.id')
			->select('i.id as institution_id', 'brandings.id', 'i.name as institution_name')->get();
		return view('admin::branding.brandview', compact('branding'));

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$InstitutionObj = new Institution();
		//$inst_arr = $InstitutionObj->getInstitutions();
		$branding=Branding::find($id);
        $institution=Institution::select('name','id')->where('id',$branding['institution_id'])->first();
		//$branding = Branding::find($id);
        //dd($branding->institution_id);
		return view('admin::branding.brandedit', compact('branding', 'institution'));

	}
	/*public function brandingscript($id)
	{
		$id=Branding::find($id)->get();
        dd($id);
	}*/

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{

		$post = Input::All();
		$messages = [
			'institution_id.required' => ' Institution Name  is required',
			//'title.required' => 'Enter Name of the Title',
			'hbcolor.required' => 'BackGround color is required  ',
			'headertc.required' => ' HeadTextColor is required',
			'boxhbc.required' => ' BoxTextColor is required',
			'boxhtcolor.required' => ' BoxHeaderColor is required',
			'btextc.required' => ' BoxTextColor is required',
			'buttonc.required' => ' ButtonColor is required',
			'buttontc.required' => ' ButtonTextColor is required',
		];
		$rules = [
			'institution_id' => 'required',
			//'title'=>'required|unique:title',
			'hbcolor' => 'required',
			'headertc' => 'required',
			'boxhbc' => 'required',
			'boxhtcolor' => 'required',
			'btextc' => 'required',
			'buttonc' => 'required',
			'buttontc' => 'required',
		];
		$validator = Validator::make($post, $rules);
		if ($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		$filename = '';
		if($request->hasFile('image')) {

			$file = Input::file('image');
			$filename = time() . '.' . $file->getClientOriginalExtension();
			if (!is_dir(public_path('/data/brandingimages/'))) {
				@mkdir(public_path('/data/brandingimages/', 0777, true));
			}
			$path = public_path('/data/brandingimages/' . $filename);


			Image::make($file->getRealPath())->resize(200, 200)->save($path);
		}

		$branding = Branding::join('institution as i', 'brandings.institution_id', '=', 'i.id')
			->select('i.id as institution_id', 'brandings.id', 'i.name as institution_name')->get();
		$post = Input::all();

		unset($post['_token']);
		$updateArr = [
			//'filepath' => $filename,
			'institution_id' => $post['institution_id'],
			//'title' => $post['title'],
			'header_bg_color' => $post['hbcolor'],
			'header_text_color' => $post['headertc'],
			'box_header_bg_color' => $post['boxhbc'],
			'box_header_text_color' => $post['boxhtcolor'],
			'box_text_color' => $post['btextc'],
			'button_bg_color' => $post['buttonc'],
			'button_text_color' => $post['buttontc'],
		];
		if($filename != '')
			$updateArr['filepath'] = $filename;

		$record = Branding::where('id', $id)->update($updateArr);

		//dd($record);
		return Redirect::route('branding-view');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function delete($id)
	{
			Branding::find($id)->delete();
			return Redirect::route('branding-view');
	}


}