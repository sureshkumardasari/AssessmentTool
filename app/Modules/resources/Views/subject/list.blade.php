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
					<a href="{{ route('subjectBulkUpload') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>Bulk Upload </a>
				</div>
				
				<div class="panel-body">
					<!-- filters start -->
					<div class="panel panel-default">
						<div class="panel-heading searchfilter pointer">Advanced Filters
							<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
						</div>

						<div class="panel-body searchfilter-body hide">
							<form class="form-inline" role="form">
								<!-- <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, '','Select'); ?> -->
								<input type="hidden" name="page" id="page" value="subject">
							<div class="form-group required col-md-12">
								<label class="col-md-4 control-label">Institution</label>
								<div class="col-md-6">
									<input type="hidden" name="page" id="page" value="subject">
								<select class="form-control" name="institution_id" id="institution_id">
										<option value="0">--Select--</option>
										@foreach($inst_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="form-group required col-md-12">
								<label class="col-md-4 control-label">Category</label>
								<div class="col-md-6">
									<select class="form-control" name="category_id" id="category_id">
										<option value="0">--Select--</option>

										{{--<option value="0">--Select--</option>--}}
										@if(getRole()!="administrator")
										@foreach($category as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
											@endif
									</select>
								</div>
							</div>	
							<div class="form-group col-md-12">
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
					<div id="subject-list" style="min-height:50px;"> {!! $subjectsList !!} </div>	
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>
<script>
  	var searchRoute = "{{URL::route('subject-search')}}";
  	var categoryRoute = "{{URL::route('getcategory')}}";
</script>
@endsection