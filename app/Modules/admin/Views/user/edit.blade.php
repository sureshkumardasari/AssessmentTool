@extends('default')

@section('content')

<?php
	$role_id = (old('role_id') != NULL) ? old('role_id') : $role_id; 
	$institution_id = (old('institution_id') != NULL) ? old('institution_id') : $institution_id; 
	$name =  (old('name') != NULL) ? old('name') : $name; 
	$email = (old('email') != NULL) ? old('email') : $email; 
	$enrollno = (old('enrollno') != NULL) ? old('enrollno') : $enrollno; 
	$status = (old('status') != NULL) ? old('status') : $status; 
	$password = (old('password') != NULL) ? old('password') : $password; 
?>

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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/user/update') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $id }}">

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

						<div class="form-group required">
							<label class="col-md-4 control-label">Role</label>
							<div class="col-md-6">
								<select class="form-control" name="role_id">
									<option value="0">Select</option>
									@foreach($roles_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $role_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

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

						<div class="form-group required">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-4 control-label">Enrollment No.</label>
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
							<label class="col-md-4 control-label">City</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="city" value="{{ $city }}">
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">State</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="state" value="{{ $state }}">
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Country</label>
							<div class="col-md-6">
								<select class="form-control" name="country_id">
									<option value="0">Select</option>
									@foreach($country_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $country_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Pincode</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="pincode" value="{{ $pincode }}">
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Phone No.</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="phoneno" value="{{ $phoneno }}">
							</div>
						</div>
						<!--  -->
						<div class="form-group required">
							<label class="col-md-4 control-label">Status</label>
							<div class="col-md-6">
								<input type="radio" class="" name="status" id="status_yes" value="Active" {{ ($status == "" || $status == "Active") ? 'checked="checked"' : '' }}>Active 
								<input type="radio" class="" name="status" id="status_no" value="Inactive" {{ ($status == "Inactive") ? 'checked="checked"' : '' }}>Inactive 
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Submit
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
