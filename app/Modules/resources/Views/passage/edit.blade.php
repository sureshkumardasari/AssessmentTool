@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('plugins/tinymce/plugins/tiny_mce_wiris/core/display.js')) !!}
{!! HTML::script(asset('plugins/tinymce/tinymce.min.js')) !!}    
{!! HTML::script(asset('js/custom/passage.js')) !!}
@stop
@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			<?php 
				$operation = ($passage->id) ? "Update" : "Create";
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
						<input type="hidden" name="id" value="{{ $passage->id }}">
						<div class="form-group">
							<label class="col-md-3 control-label">Title</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $passage->title }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Passage Text</label>
							<div class="col-md-6">
								<textarea class="form-control" id="passage_text" name="passage_text">{{ $passage->passage_text }}</textarea>
							</div>
						</div>
							<div class="form-group">
							<label class="col-md-3 control-label" >Passage Lines</label>
							<div class="col-md-6">
								<textarea class="form-control" id="passage_lines" name="passage_lines">{{ $passage->passage_lines }}</textarea>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-3 control-label">Status</label>
							<div class="col-md-6">
								<input type="radio" class="" name="status" id="status_yes" value="1" {{ ($passage->status == 1 ) ? 'checked="checked"' : '' }}> Active 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="0" {{ ($passage->status == 0) ? 'checked="checked"' : '' }}> Inactive 
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
