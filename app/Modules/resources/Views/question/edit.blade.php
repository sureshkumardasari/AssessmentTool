@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('assets/js/common.js')) !!}
{!! HTML::script(asset('plugins/tinymce/plugins/tiny_mce_wiris/core/display.js')) !!}
{!! HTML::script(asset('plugins/tinymce/tinymce.min.js')) !!}    
{!! HTML::script(asset('assets/js/question.js')) !!}
@stop
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Question Details</div>
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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/resources/questionupdate') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $id }}">
						<div class="form-group">
							<label class="col-md-2 control-label">Institution</label>
							<div class="col-md-10">
								<select class="form-control" name="institution_id" id="institution_id">
									<option value="0">Select</option>
									@foreach($inst_arr as $id=>$val)
									<option value="{{$val}}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Category</label>
							<div class="col-md-10">
								<select class="form-control" name="category_id" id="category_id">
									<option value="0">Select</option>
									@foreach($category as $id=>$val)
									<option value="{{ $val }}" {{ ($id == $category_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Subject</label>
							<div class="col-md-10">
								<select class="form-control" name="subject_id" id="subject_id">
									<option value="0">Select</option>
									@foreach($subjects as $id=>$val)
									<option value="{{ $val}}" {{ ($id == $subject_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Question Type</label>
							<div class="col-md-10">
								<select class="form-control" name="question_type" id="question_type">
									<option value="0">Select</option>
									@foreach($qtypes as $id=>$val)
									<option value="{{ $val }}" {{ ($id == $subject_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label">Passage</label>
							<div class="col-md-10">
								<select class="form-control" name="passage" id="passage">
									<option value="0">Select</option>
									@foreach($passage as $id=>$val)
										<option value="{{ $val }}" {{ ($id == $subject_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label">Question Title</label>
							<div class="col-md-10">
								<input type="text" class="form-control" name="question_title" value="{{ $name }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Question Text</label>
							<div class="col-md-10">
								<textarea class="form-control" id="question_textarea" name="question_textarea"></textarea>
							</div>
						</div>

						<div class="form-group">
						<p class="mb18 mr0">
			            	<label class="mr20 mt8 w200"></label>
			            </p>
			            <p class="w815 fltL">
			                <a href="javascript:void(0)" class="upload_btn clr btn btn btn-primary col-md-offset-10 create_answer">Add New Answer</a>
			            </p>
						<div class="clr"></div>
						<div class="answers mt20 col-md-12">
							@if (isset($answersLisitng) && !empty($answersLisitng))
								{!! $answersLisitng !!}
							@endif
						</div>

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
<?php
if (count($errors) > 0){?>
<script>
	var oldvalues = '{{old('institution_id')}}';
	var catoldvalues = '{{old('category_id')}}';
	var suboldvalues = '{{old('subject_id')}}';
	var question_type = '{{old('question_type')}}';
	var question_textarea = '{{old('question_textarea')}}';
	var passage = '{{old('passage')}}';
 	$('#institution_id').val(oldvalues);
	$('#category_id').val(catoldvalues);
	$('#subject_id').val(suboldvalues);
	$('#question_type').val(question_type);
	$('#question_textarea').val(question_textarea);
	$('#passage').val(passage);
</script>
<?php }?>
 @endsection
