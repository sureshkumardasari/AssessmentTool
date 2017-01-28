@extends('default')

@section('header-assets')
@parent
{!! HTML::script(asset('/js/custom/users.js')) !!}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<input type="hidden" name="_token" class="hidden-token" value="{{csrf_token()}}">
				<div class="panel-heading">Users
					<a href="{{ url('/user/add/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add </a>
					<a href="{{ route('userBulkUpload') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Bulk Upload </a>
					<a href="{{ URL::to('user/download') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax">Download XLS</a>
				</div>
				<div>
					@if(Session::has('flash_message'))
						<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! Session('flash_message') !!}</em></div>
					@endif
				</div>
				<div>
					@if(Session::has('flash_message_failed'))
						<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
					@endif
				</div>
				<div class="panel-body">

					<div class="panel panel-default">
						<div class="panel-heading searchfilter pointer">Advanced Filters
							<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
						</div>

						<div class="panel-body searchfilter-body hide">
							<!-- <div class="form-group col-md-6">
								<label class="col-md-4 control-label">Select Institution</label>
								<div class="col-md-6">
									<select class="form-control" name="institution_id" id="institution_id">
										<option value=0>All</option>
										@foreach($inst_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div> -->
							<?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, '','All'); ?>
							<br><br>
							<div class="form-group col-md-5">
								<label class="col-md-4" style="margin-left:-15px;">Select Role</label>
								<div class="col-md-6">
									<select class="form-control" name="role_id" id="role_id">
										<option value=0>All</option>
										@foreach($roles_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					
					<div id="user-list"> {!! $usersList !!} </div>					
				</div>
			</div>
		</div>
	</div>
</div>

<script>
  	var userSearchRoute = "{{URL::route('usersearch')}}";
</script>
@endsection