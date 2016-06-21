@extends('default')
@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">Branding</div>
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
						<form class="form-horizontal" enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="{{ url('user/brandingcreate') }}" >
							<input type="hidden" name="_token" value="{{ csrf_token()}}" id="csrf_token">
							<div class="form-group required">
								<label class="col-md-4 control-label">Select Institution</label>
								<div class="col-md-6">
									<select class="form-control" name="institution_id" id="institution_id" onchange="check_already_entered()" >
										<option value="0">-- Select --</option>
										@foreach($inst_arr as $id => $name)
											<option value="{{$id}}">{{$name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Header Logo</label>
								<div class="col-md-5">
									<input type="file" name="image" accept="image/*"/>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Header Background Color</label>
								<div class="col-md-4">
									<div id="" class="input-group colorpicker-component jscolor"> 
										<input type="text" name="hbcolor" value="#ffffff" class="form-control" /> <span class="input-group-addon"><i></i></span> 
									</div>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Header Text Color</label>
								<div class="col-md-4">
									<div id="" class="input-group colorpicker-component jscolor"> 
										<input type="text" name="headertc" value="#000000" class="form-control" /> <span class="input-group-addon"><i></i></span> 
									</div>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Box Header Background Color</label>
								<div class="col-md-4">
									<div id="" class="input-group colorpicker-component jscolor"> 
										<input type="text" name="boxhbc" value="#ffffff" class="form-control" /> <span class="input-group-addon"><i></i></span> 
									</div>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Box Header Text Color</label>
								<div class="col-md-4">
									<div id="" class="input-group colorpicker-component jscolor"> 
										<input type="text" name="boxhtcolor" value="#000000" class="form-control" /> <span class="input-group-addon"><i></i></span> 
									</div>
								</div>
							</div>
							<div class="form-group required hidden">
								<label class="col-md-4 control-label">Box Text Color</label>
								<div class="col-md-4">
									<div id="" class="input-group colorpicker-component jscolor"> 
										<input type="text" name="btextc" value="#000000" class="form-control" /> <span class="input-group-addon"><i></i></span> 
									</div>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Button Color</label>
								<div class="col-md-4">
									<div id="" class="input-group colorpicker-component jscolor"> 
										<input type="text" name="buttonc" value="#ffffff" class="form-control" /> <span class="input-group-addon"><i></i></span> 
									</div>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Button Text Color</label>
								<div class="col-md-4">
									<div id="" class="input-group colorpicker-component jscolor"> 
										<input type="text" name="buttontc" value="#ffffff" class="form-control" /> <span class="input-group-addon"><i></i></span> 
									</div>
								</div>
							</div>
							<div class="col-md-6 col-md-offset-4" >
								<button type="submit"  class="btn btn-primary"  class="addbtn">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js')}}"></script>
	<link href="{{ asset('css/bootstrap-colorpicker.min.css') }}" rel='stylesheet' type='text/css'>	
<script> $(function() {
    $('.jscolor').colorpicker({ color: '#000000', format: 'hex' }); });
	var branding_institutions=[];
	@foreach($brandingInstitutions as $brandIns)
	branding_institutions.push("{{$brandIns}}");
	@endforeach
	function check_already_entered(){
		if($.inArray($('#institution_id').val(),branding_institutions)>-1){
			alert("already entered");
            $('#institution_id').val(0);
		}

	}
</script>
@endsection