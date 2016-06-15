@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('assets/js/common.js')) !!}
{!! HTML::script(asset('plugins/tinymce/plugins/tiny_mce_wiris/core/display.js')) !!}
{!! HTML::script(asset('plugins/tinymce/tinymce.min.js')) !!}
{!! HTML::script(asset('assets/js/question.js')) !!}
{!! HTML::script(asset('assets/js/bootstrap-checkbox.min.js')) !!}
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">

			<div class="panel panel-default">
			<div class="panel-heading">Create Assessment
			<a href="{{ url('/resources/assessment/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> BACK</a>
			</div>
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
									<a class="btn btn-primary" onclick="filter('0');" href="javascript:;">Apply Filter</a>
								</div>
 						</div>
					</div>

					<div class="form-group required">
						<label class="col-md-4 control-label">Title</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="{{old('title')}}">
							<?php
							$path = url()."/resources/";?>
							<input type="hidden" name="url" id="url" value="<?php echo $path;?>">
						</div>
					</div>
					<div class="form-group required">
						<label class="col-md-4 control-label">Header</label>
						<div class="col-md-6">
							<textarea class="form-control textarea"   name="header" >{{old('header')}}</textarea>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-md-4 control-label">Footer</label>
						<div class="col-md-6">
							<textarea class="form-control textarea"  name="footer">{{old('footer')}}</textarea>
						</div>
					</div>

					<div class="form-group required">
						<label class="col-md-4 control-label">Begin Instruction</label>
						<div class="col-md-6">
							<textarea class="form-control textarea"  name="begin_instruction">{{old('begin_instruction')}}</textarea>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-md-4 control-label">End Instruction</label>
						<div class="col-md-6">
							<textarea class="form-control textarea" name="end_instruction">{{old('end_instruction')}}</textarea>
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
	tinymce.init({
		selector: '.textarea',
		width : 450,
		height : 135,
		auto_focus:false,
		statusbar : false,
		menubar : false,
		paste_as_text: true,
		toolbar: 'bold,italic,underline',
	});
</script>
@include('resources::assessment.assessment_js_validation')
@endsection