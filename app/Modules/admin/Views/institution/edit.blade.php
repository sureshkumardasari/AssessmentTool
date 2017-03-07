@extends('default')

@section('content')

<?php
	$name =  (old('name') != NULL) ? old('name') : $name;
	$address1 =  (old('address1') != NULL) ? old('address1') : $address1;
	$city =  (old('city') != NULL) ? old('city') : $city;
	$state = (old('state') != NULL) ? old('state') : $state; 
	$country_id =  (old('country_id') != NULL) ? old('country_id') : $country_id;
	$pincode =  (old('pincode') != NULL) ? old('pincode') : $pincode;
	$phoneno =  (old('phoneno') != NULL) ? old('phoneno') : $phoneno;
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Institution Details</div>
				
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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/user/institutionupdate') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $id }}">
						@if($id == 0)
						<div class="form-group">
							<label class="col-md-4 control-label">Parent Institution</label>
							<div class="col-md-6">
								<select class="form-control" name="parent_id">
									<option value="0">--Select--</option>
									@foreach($inst_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $parent_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						@else
						<input type="hidden" name="parent_id" value="{{ $parent_id }}">
						@endif

						<div class="form-group required">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $name }}">
							</div>
						</div>

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
								<input type="text" class="form-control" name="pincode" value="{{ $pincode }}">
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Phone No.</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="phoneno" value="{{ $phoneno }}" maxlength="10">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Submit
								</button>
								<a type="Cancel"  class="btn btn-danger"  href="{{  url('/user/institution') }}">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>
@endsection
