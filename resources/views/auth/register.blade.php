@extends('default')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Register</div>
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

						<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="form-group required">
								<label class="col-md-4 control-label">First Name</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
								</div>
							</div>

							<div class="form-group required">
								<label class="col-md-4 control-label">Last Name</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
								</div>
							</div>

							<div class="form-group required">
								<label class="col-md-4 control-label">E-Mail Address</label>
								<div class="col-md-6">
									<input type="email" class="form-control" name="email" value="{{ old('email') }}">
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
								<label class="col-md-4 control-label">Enrollment No</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="enrollno" value="{{ old('enrollno') }}">
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Address1</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="address1" value="{{ old('address1') }}">
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">City</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="city" value="{{ old('city') }}">
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">State</label>
								<div class="col-md-6">
									<select class="form-control" name="state">
										<option value="0">Select</option>
										@foreach($state_arr as $id=>$val)
											<option value="{{ $id }}" {{ ($id == $state) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
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
									<input type="text" class="form-control" name="pincode" value="{{ old('pincode') }}">
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Phone No.</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="phoneno" value="{{ old('phoneno') }}" >
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Status</label>
								<div class="col-md-6">
									<input type="radio" class="" name="status" id="status_yes" value="Active" >Active
									<input type="radio" class="" name="status" id="status_no" value="Inactive">Inactive
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Gender</label>
								<div class="col-md-6">
									<input type="radio" name="gender"
										   <?php if (isset($users->gender) && $users->gender=="male") echo "checked='checked'";?>
										   value="male">Male
									<input type="radio" name="gender"
										   <?php if (isset($users->gender) && $users->gender=="female") echo "checked='checked'";?>
										   value="female">Female
								</div>
								<div class="form-group ">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Register
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
