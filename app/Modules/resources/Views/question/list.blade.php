@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<ul class="nav nav-tabs" role="tablist">
			    <li><a href="{{ url('/resources/category') }}">Category</a></li>
		        <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
		        <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
		        <li class="active"><a href="{{ url('/resources/question') }}">Questions</a></li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">&nbsp;<!-- Lessons -->
					<a href="{{ url('/resources/questionadd/') }}" class="btn btn-primary btn-sm right" ><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
				</div>
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
				<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-4 control-label">Institution</label>
						<div class="col-md-6">
							<select class="form-control" name="institution_id" id="institution_id">
								<option value="0">Select</option>
								@foreach($inst_arr as $id=>$val)
								<option name="institution_id" value="{{Input::old('institution_id')}}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Category</label>
						<div class="col-md-6">
							<select class="form-control" name="category_id" id="category_id">
								<option value="0">Select</option>
								@foreach($category as $id=>$val)
								<option value="{{ $id }}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Subject</label>
						<div class="col-md-6">
							<select class="form-control" name="subject_id" id="subject_id">
								<option value="0">Select</option>
								@foreach($subjects as $id=>$val)
								<option value="{{ $id }}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Lessons</label>
						<div class="col-md-6">
							<select class="form-control" name="lessons_id" id="lessons_id">
								<option value="0">Select</option>
								@foreach($lessons as $id=>$val)
									<option value="{{ $id }}">{{ $val }}</option>
								@endforeach
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
					<div class="clearfix"></div>
					<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Name</th>
				                <th></th>
				            </tr>
				        </thead>
				        <tbody id="question_list_filer">
				            @foreach( $questions as $id => $name )
				            <tr>
				                <td>{{ $name }}</td>
				                <td>
				                	<a href="{{ url('/resources/questionedit/'.$id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="{{ url('/resources/questiondel/'.$id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
		var url="filter_data_question";
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
							tr.append("<a href='questionedit/"+ response[i].id +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>");
							tr.append("<a href='questionedit/"+ response[i].id +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-trash'' aria-hidden='true'></span></a></td>");
 							$('#question_list_filer').append(tr);
						}
					}
				}
		);
	}
</script>
@endsection