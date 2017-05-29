@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('/js/custom/resources.js')) !!}
@stop
@section('content')
 <div class="col-md-10 col-md-offset-1">
                    @if(Session::has('flash_message'))
                        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! Session('flash_message') !!}</em></div>
                    @endif
                </div>
                <div class="col-md-10 col-md-offset-1">
                    @if(Session::has('flash_message_failed'))
                        <div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
                    @endif
                </div>
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<ul class="nav nav-tabs" role="tablist">
			    <li><a href="{{ url('/resources/category') }}">Categories</a></li>
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
								<select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">
										<option value="0">--Select Institution--</option>
										@foreach($inst_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="form-group required col-md-12">
								<label class="col-md-4 control-label">Category</label>
								<div class="col-md-6">
									<select class="form-control" name="category_id" id="category_id">
										<option value="0">--Select Category--      </option>

										<!-- {{--<option value="0">--Select Category--</option>--}}
										@if(getRole()!="administrator")
										@foreach($category as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
											@endif -->
									</select>
								</div>
							</div>	
							<div class="form-group col-md-12">
								<div class="col-md-6 col-md-offset-4">
									<button type="button" class="btn btn-primary" id="applyFiltersBtn" >
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
     }, 1000);
 })
 </script>
 <?php
$path = url()."/resources/";
?>
<script>
  	var searchRoute = "{{URL::route('subject-search')}}";
  	var categoryRoute = "{{URL::route('getcategory')}}";
</script>
<script type="text/javascript">

    function change_institution(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}categoryList/'+$('#institution_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
							$('#category_id').empty();
							$('#subject_id').empty();
							
							var opt = new Option('--Select Category--', '');
							//opt.addClass('selected','disabled','hidden');
							$('#category_id').append(opt);
							var opt1 = new Option('--Select Subject--', '');
							$('#subject_id').append(opt1);
							
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);
								$('#category_id').append(opt);
							}
						}
					});
	}
    </script>
@endsection