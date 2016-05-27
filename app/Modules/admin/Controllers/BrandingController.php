<?php namespace App\Modules\Admin\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Admin\Models\Branding;

class BrandingController extends Controller {



	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{	

        
        $InstitutionObj = new Institution();
		$inst_arr = $InstitutionObj->getInstitutions();
		/*$inst_arr=Institution::get();*/
		return view('admin::branding.brand',compact('inst_arr'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
	
		$post=Input::All(); 
		$branding = Branding::create([
			
			'title'=>$post['title'],
			/*'filepath'=>$post['filepath'],*/
			'header_bg_color'=>$post['hbc'],
			'header_text_color'=>$post['htc'],
			'box_header_bg_color'=>$post['bhbc'],
			'box_header_text_color'=>$post['bhtc'],
			'box_text_color'=>$post['btc'],
			'button_color'=>$post['bc'],
			'institution_id'=> $post['institution_id'],
			]);//dd($branding);
		$institution_name=Institution::select('name')->where('id',$branding->institution_id)->first();
		//dd($institution_name);
		\Session::flash('success', 'Successfully added.');
			return Redirect::route('brandview');
				//return view('admin::branding.brandview',compact('branding','institution_name'));
	}

		

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	
	public function store(Request $request)
	{
        $image = new Branding();
        $this->validate($request, [
            'title' => 'required',
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
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function display()
	{
		$branding=Branding::join('institution as i','brandings.institution_id','=','i.id')
		->select('i.id as institution_id','brandings.id','i.name as institution_name','brandings.title')->get();
		return view('admin::branding.brandview',compact('branding'));
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
		$inst_arr = $InstitutionObj->getInstitutions();
			$branding=Branding::find($id);
		
		return view('admin::branding.brandedit',compact('branding','inst_arr'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{		
		
		$branding=Branding::join('institution as i','brandings.institution_id','=','i.id')
		->select('i.id as institution_id','brandings.id','i.name as institution_name','brandings.title')->get();
		$post=Input::all();
		unset($post['_token']);
		$record = Branding::where('id',$id)->update([
				'title'=>$post['title'],
			/*'filepath'=>$post['filepath'],*/
			'header_bg_color'=>$post['hbc'],
			'header_text_color'=>$post['htc'],
			'box_header_bg_color'=>$post['bhbc'],
			'box_header_text_color'=>$post['bhtc'],
			'box_text_color'=>$post['btc'],
			'button_color'=>$post['bc'],
			'institution_id'=> $post['institution_id'],
	
				
		]);
		
		/*return Redirect::to('user/branding/brandview');*/
		return view('admin::branding.brandview',compact('branding','record'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function destroy($id)
	{
		
		Branding::find($id)->delete();
		$branding=Branding::join('institution as i','brandings.institution_id','=','i.id')
		->select('i.id as institution_id','brandings.id','i.name as institution_name','brandings.title')->get();
		return view('admin::branding.brandview',compact('branding'));
	}

}
