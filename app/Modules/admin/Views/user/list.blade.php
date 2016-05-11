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
				<div class="panel-heading">Users
					<a href="{{ url('/user/add/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add </a>

					<a href="{{ route('userBulkUpload') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Bulk Upload </a>
				</div>
				<div class="panel-body">

					<div class="panel panel-default">
						<div class="panel-heading">Advanced Filters
							<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right searchfilter" aria-hidden="true"></span></a>
						</div>

						<div class="panel-body searchfilter-body hide">
							<div class="form-group col-md-6">
								<label class="col-md-4 control-label">Select Institution</label>
								<div class="col-md-6">
									<select class="form-control" name="institution_id" id="institution_id">
										<option value="0">All</option>
										@foreach($inst_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group col-md-5">
								<label class="col-md-4 control-label">Select Role</label>
								<div class="col-md-6">
									<select class="form-control" name="role_id" id="role_id">
										<option value="0">All</option>
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