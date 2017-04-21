@extends('default')

@section('header-assets')
@parent
{!! HTML::script(asset('/js/jquery.Jcrop.js')) !!}
<link href="{{ asset('/css/jquery.Jcrop.css') }}" rel='stylesheet' type='text/css'>
@stop

@section('content')

<?php
	$user_id = $id;
	$role_id = (old('role_id') != NULL) ? old('role_id') : $role_id; 
	$institution_id = (old('institution_id') != NULL) ? old('institution_id') : $institution_id; 
	$first_name =  (old('first_name') != NULL) ? old('first_name') : $first_name;
	$last_name =  (old('last_name') != NULL) ? old('last_name') : $last_name; 
	$email = (old('email') != NULL) ? old('email') : $email; 
	$password = (old('password') != NULL) ? old('password') : $password; 
	$enrollno = (old('enrollno') != NULL) ? old('enrollno') : $enrollno; 
	$address1 = (old('address1') != NULL) ? old('address1') : $address1; 
	$city = (old('city') != NULL) ? old('city') : $city; 
	$state = (old('state') != NULL) ? old('state') : $state; 
	$pincode=(old('pincode') != NULL)? old('pincode') : $pincode;
	$phoneno = (old('phoneno') != NULL)? old('phoneno') : $phoneno;
	$country_id=(old('country_id')!=NULL)? old('country_id'):$country_id;
	$status = (old('status') != NULL) ? old('status') : $status; 
	$gender = (old('gender') != NULL) ? old('gender') : $gender;  

	/*if($profile_picture != NULL)
	{
		$profile_picture = asset('/data/uploaded_images/128x128/'.$profile_picture);
	}
	else
	{
		$profile_picture = asset('/images/profile_pic.jpg');	
	}*/	
?>
<style>
		.image_add{
			width: 86px;
			cursor: pointer;
			position: absolute;
			top: 215px;
			left: 93px;
			margin-left: -23px;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 25px;
			z-index: 1;
			-webkit-box-shadow: 0px 3px 0px 0px #dddddd;
			-moz-box-shadow: 0px 3px 0px 0px #dddddd;
			box-shadow: 0px 3px 0px 0px #dddddd;
			font: 17px "droid_sansbold";
			color: #6d6f75;
			line-height: 30px;
			height: 30px;
			text-align: center;
			background-color: #dddddd;
		}
	</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			
			<div class="panel panel-default">
				<div class="panel-heading">User Information</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<div class="col-md-3">
						<div class="form-group">
							<img src="{{ $profile_picture }}" width="128" height="128" alt="profile image" id="photo" />
							<span class="dsply_b pt16"><br>You can upload a JPG, JPEG, GIF or PNG file(File size limit  is 4MB)<br/>
                			</span>
						</div>
						<div class="form-group">
							{!! cropUploadedImage($pic_data) !!}
							{!! imageUpload($pic_data) !!}	
						</div>										
					</div>					
					<div class="col-md-6">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/user/update') }}">
						<input type="hidden" name="_token" class="hidden-token" value="{{ csrf_token() }}">
						<input type="hidden" name="role_name" value="{{$role_name}}"/>
						<input type="hidden" name="id" value="{{ $user_id }}">
						<input type="hidden" value="{{ !empty($pic_data['coords'])?$pic_data['coords']:'' }}" name="pic_coords" id="pic_coords">
						<input type="hidden" value="{{ !empty($pic_data['image'])?$pic_data['image']:'' }}" name="profile_picture" id="profile_picture">
						<input type="hidden" value="{{ !empty($pic_data['id'])?$pic_data['id'] : 0 }}" name="image_user_id" id="image_user_id">
						<div class="form-group required">
							<label class="col-md-4 control-label">Institution</label>
							<div class="col-md-6">
								<select class="form-control" name="institution_id">
									<option value="0">Select</option>
									@foreach($inst_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>								
							</div>
						</div>
						
						
						@if( (Auth::user()->id != $user_id) || $user_id == 1)
						<div class="form-group required">
							<label class="col-md-4 control-label">Role</label>
							<div class="col-md-6">
								<select class="form-control" name="role_id">
									<option value="0">--Select--</option>
									@foreach($roles_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $role_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						@endif

						<div class="form-group required">
							<label class="col-md-4 control-label">First Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="first_name" value="{{ $first_name }}">
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-4 control-label">Last Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="last_name" value="{{ $last_name }}">
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ $email }}">
							</div>
						</div>
						@if ($user_id == 0)			
						<div class="form-group required">
							<label class="col-md-4 control-label" id="password">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-4 control-label" id="password">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>
						@else
						<div class="form-group ">
							<label class="col-md-4 control-label" id="password">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group ">
							<label class="col-md-4 control-label" id="password">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>
						@endif
						<div class="form-group required">
							<label class="col-md-4 control-label">Gender</label>
							<div class="col-md-6">
								<label class="radio-inline"><input type="radio" class="" name="gender" id="gender_male"  value="male" {{ ($gender == "" || $gender == "Male") ? 'checked="checked"' : ''}}> Male</label>
								<label class="radio-inline"><input type="radio" class="" name="gender" id="gender_female" value="female"  {{ ($gender == "Female") ? 'checked="checked"' : ''}}> Female</label>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Enrollno</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="enrollno" value="{{ $enrollno }}">
							</div>
						</div>
						<!--  -->
						<div class="form-group required">
							<label class="col-md-4 control-label">Address1</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="address1" value="{{ $address1 }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Address2</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="address2" value="{{ $address2 }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Address3</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="address3" value="{{ $address3 }}">
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Country</label>
							<div class="col-md-6">
							<select class="form-control" id="country_id" name="country_id" onchange="change_user()">

									<option value="0">--Select--</option>
									@foreach($country_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $country_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group required">
							<label class="col-md-4 control-label">State</label>
							<div class="col-md-6">
                                <select class="form-control" id="state" name="state">
									<option value="0">--Select--</option>
									   @foreach($state_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $state) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									   @endforeach
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">City</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="city" value="{{ $city }}">
							</div>
						</div>
						
						<div class="form-group required">
							<label class="col-md-4 control-label">Pincode</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="pincode" value="{{ $pincode }}" maxlength="6">
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Phoneno</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="phoneno" value="{{ $phoneno }}" maxlength="10">
							</div>
						</div>
						<!--  -->
						@if(Auth::user()->id != $user_id)
						<div class="form-group required">
							<label class="col-md-4 control-label">Status</label>
							<div class="col-md-6">
								<label class="radio-inline"><input type="radio" class="" name="status" id="status_yes" value="Active" {{ ($status == "" || $status == "Active") ? 'checked="checked"' : '' }}> Active </label>
								<label class="radio-inline"><input type="radio" class="" name="status" id="status_no" value="Inactive" {{ ($status == "Inactive") ? 'checked="checked"' : '' }}> Inactive </label>
							</div>
						</div>
						@else	
						<input type="hidden" name="status" value="Active">
						@endif					
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Submit
								</button>

								<a type="Cancel"  class="btn btn-danger"  href="{{  url('/home') }}">Cancel</a>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var gender="";

	<?php if($gender=="female"){
			?>
			gender="gender_female";
	<?php }
			else if($gender=="male"){
			?>
			gender="gender_male";
	<?php } ?>
	if(gender!=""){
		$('#'+gender).prop('checked',true);
	}
</script>
    <script>
        function change_user(){
            //alert('hai');
        var csrf=$('Input#csrf_token').val();
        var loadurl = "{{  url('/user/state') }}/" ;
        $.ajax(
                {

                    headers: {"X-CSRF-Token": csrf},
                    
                    url:loadurl+$('#country_id').val(),
                    type:'get',
                    success:function(response) {
                        var a = response.length;
                        $('#state').empty();
                        var opt = new Option('--Select--', '0');
                        $('#state').append(opt);
                        for (i = 0; i < a; i++) {
                            var opt = new Option(response[i].name, response[i].id);
                            $('#state').append(opt);
                        }
                    }
                }
        )

    }

</script>
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>
@endsection
