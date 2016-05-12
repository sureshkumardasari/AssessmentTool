@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('plugins/tinymce/plugins/tiny_mce_wiris/core/display.js')) !!}
{!! HTML::script(asset('plugins/tinymce/tinymce.min.js')) !!}    
{!! HTML::script(asset('js/custom/passage.js')) !!}
@stop
@section('content')

<style type="text/css">	
	/* This below given two lines are for file brower fancybox*/
	.fancybox-overlay{z-index: 99999;}
	#fancybox-loading{z-index: 99999 !important;}
	</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			<?php 
				$operation = ($id) ? "Update" : "Create";
			?>
				<div class="panel-heading">{{$operation}} Passage</div>
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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/resources/passageupdate') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $id }}">
						<div class="form-group">
							<label class="col-md-3 control-label">Title</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $name }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Passage Text</label>
							<div class="col-md-6">
								<textarea class="form-control" id="passage_text" name="passage_text">{{ $passage_text }}</textarea>
							</div>
						</div>
							<div class="form-group">
							<label class="col-md-3 control-label" >Passage Lines</label>
							<div class="col-md-6">
								<textarea class="form-control" id="passage_lines" name="passage_lines">{{ $passage_lines }}</textarea>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-3 control-label">Status</label>
							<div class="col-md-6">
								<input type="radio" class="" name="status" id="status_yes" value="Yes" {{ ($status == "" || $status == "Yes") ? 'checked="checked"' : '' }}> Active 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="No" {{ ($status == "No") ? 'checked="checked"' : '' }}> Inactive 
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
							<a href="{{URL::route('viewPassage') }}" class="btn btn-primary btn-sm  fancybox fancybox.ajax"> Preview </a>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
@endsection
