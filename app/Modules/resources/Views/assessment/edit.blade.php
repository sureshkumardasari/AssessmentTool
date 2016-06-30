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
					<div class="panel-heading">Edit Assessment
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
						<form class="form-horizontal" name="assessment_form" id="assessment_form" role="form" method="POST" action="{{ url('/resources/assessmentupdate') }}">
							<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
							<input type="hidden" name="id" value="{{ $assessment_details['id'] }}">


							

							<div class="form-group required">
								<label class="col-md-4 control-label">Title</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="name" value="{{ $assessment_details['name'] }}">
								</div>
							</div>
						<div class="form-group required">
						<label class="col-md-4 control-label">Header</label>
						<div class="col-md-6">
							<textarea class="form-control textarea"   name="header" >{{$assessment_details['header']}}</textarea>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-md-4 control-label">Footer</label>
						<div class="col-md-6">
							<textarea class="form-control textarea"  name="footer" >{{$assessment_details['footer']}}</textarea>
						</div>
					</div>

					<div class="form-group required">
						<label class="col-md-4 control-label">Begin Instruction</label>
						<div class="col-md-6">
							<textarea class="form-control textarea"  name="begin_instruction"  value="">{{$assessment_details['begin_instruction']}}</textarea>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-md-4 control-label">End Instruction</label>
						<div class="col-md-6">
							<textarea class="form-control textarea" name="end_instruction"  value="">{{$assessment_details['end_instruction']}}</textarea>
						</div>
					</div>
							<?php
							$path = url()."/resources/";?>
							<input type="hidden" name="url" id="url" value="<?php echo $path;?>">
							<div class="col-md-12">
								@include('resources::assessment.partial.questions_edit')
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">Guessing_Penality</label>
								<div class="col-md-6">
									<select class="form-control" name="guessing_penality" id="guessing_penality"  value="">{{$assessment_details['guessing_panality']}}
										<option value="1">0</option>
										<option value="2">0.25</option>
									</select>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label">MC SingleAnswerPoint</label>
								<div class="col-md-6">
									<textarea class="form-control numeric" name="mcsingleanswerpoint"  value="">{{$assessment_details['mcsingleanswerpoint']}}</textarea>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-md-4 control-label"> Essay AnswerPoint</label>
								<div class="col-md-6">
									<textarea class="form-control numeric" name="essayanswerpoint"  value="">{{$assessment_details['essayanswerpoint']}}</textarea>
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

	{!! HTML::script(asset('/js/custom/confirm.js')) !!}
<script>
$( document ).ready(function() {
	$('.searchfilter').on("click",function(e){    	
    	//console.log('searchfilter ');
    	$(".searchfilter span")
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        $('.searchfilter-body').toggleClass('hide show');
    });
});    
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