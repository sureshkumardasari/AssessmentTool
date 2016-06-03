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

					<div class="form-group">
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
					<div class="form-group">
						<label class="col-md-4 control-label">Category</label>
						<div class="col-md-6">
							<select class="form-control" name="category_id" id="category_id" onchange="change_category()">
								<option value="0">--Select Category--</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Subject</label>
						<div class="col-md-6">
							<select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">
								<option value="0">--Select Subject--</option>
							</select>
						</div>
					</div>
					<div class="form-group">
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
 		question_Ids=[];
		var csrf=$('Input#csrf_token').val();
		var question_id=document.getElementsByName('QuestionIds[]');
		for (var i = 0; i < question_id.length; i++) {
			question_Ids.push(question_id[i].value);
		}
		var institution_id=$('#institution_id').val();
		var category_id=$('#category_id').val();
		var subject_id=$('#subject_id').val();
		var lessons_id=$('#lessons_id').val();
		if(subject_id=='')subject_id=0;
		if(institution_id=='')institution_id=0;
		if(category_id=='')category_id=0;
		if(lessons_id=='')lessons_id=0;
		if(question_id=='')question_id=0;
		var data={'institution':institution_id,'category':category_id,'subject':subject_id,'lessons':lessons_id,'questions':question_Ids};
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
							tr.append("<td><input type='checkbox' value='' class='assess_qst check-question' data-group-cls='btn-group-sm'></td>");
							tr.append("<td>" + response[i].title + "</td>");
							$('#questions-list').append(tr);
						}
					}
				}
		);
	}
</script>
<?php
if (count($errors) > 0){?>
<script>
	var oldvalues = '{{old('institution_id')}}';
	var catoldvalues = '{{old('category_id')}}';
	var suboldvalues = '{{old('subject_id')}}';
	var lessonoldvalues = '{{old('lessons_id')}}';
	var question_type = '{{old('question_type')}}';
	var question_textarea = '{{old('question_textarea')}}';
	var passage = '{{old('passage')}}';
 	var QuestionIds=$('#QuestionIds').val();
 	filter();
	addOrRemoveInGrid('', "add");
   	$('#institution_id').val(oldvalues);
	if(oldvalues!=null){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{
					headers: {"X-CSRF-Token": csrf},
					url:'categoryList/'+$('#institution_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#category_id').empty();
						var opt=new Option('--Select Category--','');
						$('#category_id').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#category_id').append(opt);
						}

						//category
						if(catoldvalues!=null)
						{
							$('#category_id').val(catoldvalues);
							$.ajax(
									{

										headers: {"X-CSRF-Token": csrf},
										url:'subjectList/'+$('#category_id').val(),
										type:'post',
										success:function(response){
											var a=response.length;
											$('#subject_id').empty();
											var opt=new Option('--Select Subject--','');
											$('#subject_id').append(opt);
											for(i=0;i<a;i++){
												var opt=new Option(response[i].name,response[i].id);
												$('#subject_id').append(opt);
											}

											//sub
											if(suboldvalues!=null){
												$('#subject_id').val(suboldvalues);
												$.ajax(
														{
															headers: {"X-CSRF-Token": csrf},
															url:'lessonsList/'+$('#subject_id').val(),
															type:'post',
															success:function(response){
																var a=response.length;
																$('#lessons_id').empty();
																var opt=new Option('--Select Lesson--','');
																$('#lessons_id').append(opt);
																for(i=0;i<a;i++){
																	var opt=new Option(response[i].name,response[i].id);
																	$('#lessons_id').append(opt);
																}
																$('#lessons_id').val(lessonoldvalues);
															}
														}
												)
											}//sub end
										}
									}
							)
						}//end category


					}
				}
		)


		$('#question_type').val(question_type);
		$('#question_textarea').val(question_textarea);
		$('#passage').val(passage);
	}

</script>
<?php }?>

<script>

	function change_institution(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'categoryList/'+$('#institution_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#category_id').empty();
						var opt=new Option('--Select Category--','');
						//opt.addClass('selected','disabled','hidden');
						$('#category_id').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#category_id').append(opt);
						}
					}
				}
		)
	}
	function change_category(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'subjectList/'+$('#category_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#subject_id').empty();
						var opt=new Option('--Select Subject--','');
						$('#subject_id').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#subject_id').append(opt);
						}
					}
				}
		)
	}
	function change_lessons(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'lessonsList/'+$('#subject_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#lessons_id').empty();
						var opt=new Option('--Select Lesson--','');
						$('#lessons_id').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#lessons_id').append(opt);
						}
					}
				}
		)
	}
</script>
@endsection