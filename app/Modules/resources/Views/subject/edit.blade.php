@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('/js/custom/resources.js')) !!}
@stop
@section('content')

<?php

	$institution_id = (old('institution_id') != NULL) ? old('institution_id') : $institution_id;
    $category_id =  (old('category_id') != NULL) ? old('category_id') : $category_id;
	$name =  (old('name') != NULL) ? old('name') : $name;

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">{{$title}}</div>
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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/resources/subjectupdate') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $id }}">
						@if ($id > 0)
						<div class="form-group required">
							<label class="col-md-4 control-label">Institution</label>
							<div class="col-md-6">
								<input type="hidden" name="page" id="page" value="subjectedit">
								<select class="form-control" name="institution_id" id="institution_id">
									<option value="0">--Select Institution--</option>
									@foreach($inst_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Category</label>
							<div class="col-md-6">
								<select class="form-control" name="category_id" id="category_id">
									<option value="0">--Select Category--</option>
									@if($institution_id > 0)
									@foreach($category as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $category_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $name }}">
							</div>
						</div>
                       @else
                       <div class="form-group required">
							<label class="col-md-4 control-label">Institution</label>
							<div class="col-md-6">
								<input type="hidden" name="page" id="page" value="subjectedit">
								<select class="form-control" name="institution_id" id="institution_id" >
									<option value="0">--Select Institution--</option>
									@foreach($inst_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Category</label>
							<div class="col-md-6">
								<select class="form-control" name="category_id" id="category_id">
									<option value="0">--Select Category--</option>
									@if($institution_id > 0)
									 @foreach($category as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $category_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
									@endif 
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $name }}">
							</div>
						</div>
						@endif
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Submit
								</button>
								<a type="Cancel"  class="btn btn-danger"  href="{{  url('/resources/subject/') }}">Cancel</a>
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
    //  $(document).ready(function () {
    // $("#institution_id option[value='0']").prop("selected", true);
    // });
 </script>
 <?php
$path = url()."/resources/";
?>
<script>
  	var categoryRoute = "{{URL::route('getcategory')}}";
  	/*function change_institution(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}categoryList/'+$('#institution_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
							// $('#category_id').empty();
							
							
							var opt = new Option('--Select Category--', '');
							//opt.addClass('selected','disabled','hidden');
							// $('#category_id').empty();
							$('#category_id').append(opt);
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);

								$('#category_id').append(opt);
							}
						}
					});
	}*/
</script>
@endsection
