@extends('default')
@section('content')

	<div class="container">
	
		<div class="row">
			<div class="col-md-10 col-md-offset-1"> 
				<div class="panel panel-default">
					<div class="panel-heading">Branding</div>
					<div class="userSuccMSG"></div>
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
									<select class="form-control" name="institution_id" id="institution_id" >
										@if(getRole()=="administrator")
										<option value="0" data-id="0">--Select--</option>
										@endif
										@foreach($inst_arr as $id => $name)
											<option value="{{$id}}" data-id="{{(isset($brandingIds[$id]))? $brandingIds[$id] : 0 }}">{{$name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4" style="padding-left: 15em;">Header Logo</label>
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
								<a type="Cancel"  class="btn btn-danger"  href="{{  url('/user/brandings/') }}">Cancel</a>

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
			showMsg($('#institution_id').data('foo'));
			if($.inArray($('#institution_id').val(),branding_institutions)>-1)
			{
				//alert("already entered");
				location.href="{{  url('/user/brandingedit/') }}";
				$('#institution_id').val(0);

			}

		}
		$('#institution_id').change(function(){
			var selected = $(this).find('option:selected');
			var brandingid = selected.data('id');

			showMsg("Create Branding");
			if($.inArray($(this).val(),branding_institutions)>-1)
			{
				setInterval( showMsg("Branding has done for this Institute.You are redirected to Edit page"), 4000 );

				location.href="{{  url('/user/brandingedit/') }}/"+brandingid;
				$('#institution_id').val(0);

			}

		});
		function showMsg(msg) {

		    $('.userSuccMSG').html(msg);
		    $('.userSuccMSG').css('display', 'red');
		    $('.userSuccMSG').css("top", 320 + "px");
		    $('.userSuccMSG').css("left", (($(window).width() / 2 - $('.userSuccMSG').width() / 2) - 38) + "px");
		   // alert("rvtbtyu");
		    $('.userSuccMSG').fadeOut(4000);

		}
	</script>
	<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 10000);
 })
 </script>
	
@endsection