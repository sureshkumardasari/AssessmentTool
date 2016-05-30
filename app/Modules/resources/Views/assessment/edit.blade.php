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
					<div class="panel-heading">Edit Assessment</div>
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
									<input type="text" class="form-control" name="title" value="{{$assessment_details['name']}}">
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


							<div class="col-md-12">
								@include('resources::assessment.partial.questions_edit')
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
	@include('resources::question.qst_js_validation')
@endsection