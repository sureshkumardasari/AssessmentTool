@extends('default')
@section('content')
<div class="container">
	<div>
		@if(Session::has('flash_message'))
			<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! Session('flash_message') !!}</em></div>
		@endif
	</div>
	<div>
		@if(Session::has('flash_message_failed'))
			<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
		@endif
	</div>
	<div class="row">

		<div class="col-md-10 col-md-offset-1">
			<ul class="nav nav-tabs" role="tablist">
			    <li class="active"><a href="{{ url('/resources/assessment') }}">Assessment</a></li>
		        <li><a href="{{ url('/resources/assignment') }}">Assignment</a></li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">&nbsp;<!-- Lessons -->
					<a href="{{ url('/resources/assessmentcreate/') }}" class="btn btn-default btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Create Assessment</a>
				</div>

				<div class="panel-body">
					{{--<div class="form-group">--}}
						{{--<label class="col-md-4 control-label">Institution</label>--}}
						{{--<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">--}}
						{{--<div class="col-md-6">--}}
							{{--<select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">--}}
								{{--<option value="0">--Select Institution--</option>--}}
								{{--@foreach($inst_arr as $id=>$val)--}}
									{{--<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>--}}
								{{--@endforeach--}}
							{{--</select>--}}
						{{--</div>--}}
					{{--</div>--}}
					{{--<div class="form-group">--}}
						{{--<label class="col-md-4 control-label">Category</label>--}}
						{{--<div class="col-md-6">--}}
							{{--<select class="form-control" name="category_id" id="category_id" onchange="change_category()">--}}
								{{--<option value="0">--Select Category--</option>--}}
							{{--</select>--}}
						{{--</div>--}}
					{{--</div>--}}
					{{--<div class="form-group">--}}
						{{--<label class="col-md-4 control-label">Subject</label>--}}
						{{--<div class="col-md-6">--}}
							{{--<select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">--}}
								{{--<option value="0">--Select Subject--</option>--}}
							{{--</select>--}}
						{{--</div>--}}
					{{--</div>--}}
					{{--<div class="form-group">--}}
						{{--<label class="col-md-4 control-label">Lessons</label>--}}
						{{--<div class="col-md-6">--}}
							{{--<select class="form-control" name="lessons_id" id="lessons_id">--}}
								{{--<option value="0">--Select Lessons--</option>--}}
							{{--</select>--}}
						{{--</div>--}}
					{{--</div>--}}
					{{--<div class="form-group">--}}
						{{--<div class="col-md-6">--}}
							{{--<div class="move-arrow-box">--}}
								{{--<a class="btn btn-primary" onclick="filter();" href="javascript:;">Apply Filter</a>--}}
							{{--</div>--}}
						{{--</div>--}}
					{{--</div>--}}
					{{--<div class="clearfix"> </div>--}}
					<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Name</th>
				                <th></th>
				            </tr>
				        </thead>
				        <tbody id="question_list_filer">
				            @foreach( $assessment as $name )
				            <tr>				                
				                <td>{{ $name['name'] }}</td>
				                <td>
									<a href="{{ url('/resources/assessmentview/'.$name['id']) }}" class="btn btn-default btn-sm" title="Details" ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
				                	<a href="{{ url('/resources/assessmentedit/'.$name['id']) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>

				                	<!-- <a href="{{ url('/resources/assessmentpdf/'.$name['id']) }}" class="btn btn-default btn-sm" title="PDF" ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> -->
				                	{{-- */
				                	$tplId='';		
				                	if(isset($templates[$name['id']]))$tplId=$templates[$name['id']];            				                	
				                	/* --}}
				                	
				                	<a href="{{ url('/resources/template/'.$name['id'].'/'.$tplId) }}" class="btn btn-default btn-sm" title="Template" ><span class="glyphicon glyphicon-text-size" aria-hidden="true">T</span></a> 

									<a href="javascript:;" data-ref="{{ url('/resources/assessmentdel/'.$name['id']) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</td>
				            </tr>
				            @endforeach
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>
{!! HTML::script(asset('/js/custom/confirm.js')) !!}
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
		var url="filter_data_assessment_list";
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
						$('#question_list_filer').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr/>');
							tr.append("<td>" + response[i].title + "");
							tr.append("<a href='assessmentedit/"+ response[i].id +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>");
							tr.append("<a href='assessmentdel/"+ response[i].id +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-trash'' aria-hidden='true'></span></a></td>");
							$('#question_list_filer').append(tr);
						}
					}
				}
		);
	}
</script>
@endsection