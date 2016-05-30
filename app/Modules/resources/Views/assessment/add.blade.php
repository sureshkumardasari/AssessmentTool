@extends('default')
@section('header-assets')
@parent
 	{!! HTML::script(asset('assets/js/question.js')) !!}
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
		
			<div class="panel panel-default">			
			<div class="panel-heading">Create Assessment</div>
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
				<form class="form-horizontal" name="assessment_form" id="assessment_form" role="form" method="POST" action="{{ url('/resources/assessmentinsert') }}">
				<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
					<div class="form-group required">
						<label class="col-md-4 control-label">Title</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="">
						</div>
					</div>

					<div class="form-group required">
						<label class="col-md-4 control-label">Institution</label>
						<div class="col-md-6">
							<select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">
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
							<select class="form-control" name="category_id" id="category_id" onchange="change_category()">
								<option value="0">--Select Category--</option>
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-md-4 control-label">Subject</label>
						<div class="col-md-6">
							<select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">
								<option value="0">--Select Subject--</option>
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-md-4 control-label">Lessons</label>
						<div class="col-md-6">
							<select class="form-control" name="lessons_id" id="lessons_id">
								<option value="0">--Select Lessons--</option>
							</select>
						</div>
					</div>


					<div class="form-group">
						<div class="col-md-6">
 								<div class="move-arrow-box">
									<a class="btn btn-primary" onclick="filter();" href="javascript:;">Apply Filter</a>
								</div>
 						</div>
					</div>


					<div class="col-md-12">
						@include('resources::assessment.partial.questions')
                    </div>
				<div class="form-group">
					<div class="col-md-6 col-md-offset-6">
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
<script>
	function filter(){
 		var csrf=$('Input#csrf_token').val();
		var institution_id=$('#institution_id').val();
		var category_id=$('#category_id').val();
		var subject_id=$('#subject_id').val();
		var lessons_id=$('#lessons_id').val();
		if(subject_id=='')subject_id=0;
		if(institution_id=='')institution_id=0;
		if(category_id=='')category_id=0;
		if(lessons_id=='')lessons_id=0;
		var data={'institution':institution_id,'category':category_id,'subject':subject_id,'lessons':lessons_id};
			var url="filter_data_assessment";
			ajax(url,data,csrf);
 	}
	function ajax(url,data,csrf){
		$.ajax(
				{
					url:url,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data,
					success:function(response){
  						$('#questions-list').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr/>');
							tr.append("<td><input type='checkbox' value='' class='assess_qst check-question' data-group-cls='btn-group-sm'><td>");
							tr.append("<td>" + response[i].title + "</td>");
							$('#questions-list').append(tr);
 						}
 					}
				}
		);
	}
</script>
@include('resources::question.qst_js_validation')
@endsection