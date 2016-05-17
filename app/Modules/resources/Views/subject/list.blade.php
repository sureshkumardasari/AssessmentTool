@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('/js/custom/resources.js')) !!}
@stop
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<ul class="nav nav-tabs" role="tablist">
			    <li><a href="{{ url('/resources/category') }}">Category</a></li>
		        <li class="active"><a href="{{ url('/resources/subject') }}">Subjects</a></li>
		        <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">&nbsp;<!-- Subjects -->
					<a href="{{ url('/resources/subjectadd/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
				</div>
				
				<div class="panel-body">
					<!-- filters start -->
					<div class="panel panel-default">
						<div class="panel-heading searchfilter pointer">Advanced Filters
							<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
						</div>

						<div class="panel-body searchfilter-body hide">

							<div class="form-group">
								<label class="col-md-4 control-label">Institution</label>
								<div class="col-md-6">
									<select class="form-control" name="institution_id">
										<option value="0">Select</option>
										@foreach($inst_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Category</label>
								<div class="col-md-6">
									<select class="form-control" name="category_id">
										<option value="0">Select</option>
										@foreach($category as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>							
						</div>
					</div>
					<!-- filters start -->
					<div id="subject-list"> {!! $subjectsList !!} </div>	
				</div>
			</div>
		</div>
	</div>
</div>
@endsection