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
			    <li class="active"><a href="{{ url('/resources/category') }}">Category</a></li>
		        <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
		        <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">&nbsp;<!-- Category -->
					<a href="{{ url('/resources/categoryadd/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
				</div>

				<div class="panel-body">
					<!-- filters start -->
					<div class="panel panel-default">
						<div class="panel-heading searchfilter pointer">Advanced Filters
							<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
						</div>

						<div class="panel-body searchfilter-body hide">
							<form class="form-inline" role="form">
							<div class="form-group">
								<label class="col-md-4 control-label">Institution</label>
								<div class="col-md-6">
									<input type="hidden" name="page" id="page" value="category">
									<select class="form-control" name="institution_id" id="institution_id">
										<option value="0">Select</option>
										@foreach($inst_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="button" class="btn btn-primary" id="applyFiltersBtn">
										Go
									</button>
								</div>
							</div>
							<div class="form-group" id="loadingdiv"></div>
							</form>
						</div>
					</div>
					<!-- filters start -->
					<div id="category-list" style="min-height:50px;"> {!! $categoryList !!} </div>	
				</div>
			</div>
		</div>
	</div>
</div>
<script>
  	var searchRoute = "{{URL::route('category-search')}}";
</script>
@endsection