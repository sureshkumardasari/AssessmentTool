@extends('default')

@section('header-assets')
@parent
{!! HTML::style(asset('/css/bootstrap-datetimepicker.min.css')) !!}
{!! HTML::script(asset('/js/moment.min.js')) !!}
{!! HTML::script(asset('/js/bootstrap-datetimepicker.min.js')) !!}
{!! HTML::script(asset('/js/dual-list-box.min.js')) !!}
@stop
@section('content')

<?php 
 $dtFormat = 'm/d/Y - g:i a';
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			<?php 
				$operation = ($assignment->id) ? "Update" : "Create";
			?>
				<div class="panel-heading">{{$operation}} Assignment</div>
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

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/resources/assignmentupdate') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $assignment->id }}">
						<div class="form-group">
							<label class="col-md-3 control-label">Assignment Name </label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $assignment->name }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Description </label>
							<div class="col-md-6">
								<textarea class="form-control" id="assignment_text" name="assignment_text">{{ $assignment->description }}</textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label" >Assessment</label>
							<div class="col-md-6">
								<select class="form-control" name="assessment_id">
									<option value="0">Select</option>
									@foreach($assessments_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $assignment->assessment_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
<!-- https://eonasdan.github.io/bootstrap-datetimepicker/#minimum-setup -->
						<div class="form-group">
							<label class="col-md-3 control-label">Start Date Time </label>
							<div class="col-md-6">
							 <div class='input-group date' id='startdatetime'>
								<input type="text" class="form-control" name="startdatetime" value="{{ date($dtFormat, strtotime($assignment->startdatetime))}}}">
								<span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
							 </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">End Date Time </label>
							<div class="col-md-6">
							 <div class='input-group date' id='enddatetime'>
								<input type="text" class="form-control" name="enddatetime" value="{{ date($dtFormat, strtotime($assignment->enddatetime))}}">
								<span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
							 </div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Never Expires </label>
							<div class="col-md-6 checkbox">
								<label><input type="checkbox" name="never" value="" ></label>
							</div>
						</div>
						
						<div class="form-group required">
							<label class="col-md-3 control-label">Launch Type </label>
							<div class="col-md-6">
								<label class="radio-inline"><input type="radio" name="launchtype" id="launchtype_yes" value="proctor" {{ ($assignment->launchtype == "proctor" ) ? 'checked="checked"' : '' }}> Proctor </label>
								<label class="radio-inline"><input type="radio" class="" name="launchtype" id="launchtype_no" value="system" {{ ($assignment->launchtype == "system") ? 'checked="checked"' : '' }}> System </label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label" >Proctor </label>
							<div class="col-md-6">
								<select class="form-control" id="proctor_id" name="proctor_id">
									<option value="0">Select</option>
									@foreach($proctor_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $assignment->proctor_user_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Proctor Instructions </label>
							<div class="col-md-6">
								<textarea class="form-control" id="proctor_instructions" name="proctor_instructions">{{ $assignment->proctor_instructions
  }}</textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label" >Institution </label>
							<div class="col-md-6">
								<select class="form-control" name="institution_id">
									<option value="0">Select</option>
									@foreach($institution_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $assignment->institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label" ></label>
							<div class="col-md-6">
								<div id="dual-list-box" class="form-group row">
						            <select id="student_ids" name="student_ids[]" multiple="multiple" data-title="Students" data-source="{{ url('/user/usersjson') }}" data-value="id" data-text="username" data-horizontal="false">

						            </select>
						        </div>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label">Delivary Method </label>
							<div class="col-md-6">
								<label class="radio-inline"><input type="radio" name="delivery_method" id="delivery_method_yes" value="online" {{ ($assignment->delivery_method == "online" ) ? 'checked="checked"' : '' }}> Online </label>
								<label class="radio-inline"><input type="radio" class="" name="delivery_method" id="delivery_method_no" value="print" {{ ($assignment->delivery_method == "print") ? 'checked="checked"' : '' }}> Print </label>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label">Status</label>
							<div class="col-md-6">
								<input type="radio" class="" name="status" id="status_yes" value="1" {{ ($assignment->status == 1 ) ? 'checked="checked"' : '' }}> Active 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="0" {{ ($assignment->status == 0) ? 'checked="checked"' : '' }}> Inactive 
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">							
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//var selected = new Array(1,2,3);
 $('#student_ids').DualListBox('',<?=$assignmentUsersJson?>);

$(function () {
    $('#startdatetime').datetimepicker();
    $('#enddatetime').datetimepicker();
});

$('input:radio[name="launchtype"]').change(
    function(){
    	//alert($(this).is(':checked'))
        if ($(this).is(':checked') && $(this).val() == 'system') {
            // append goes here
            // $('#proctor_id').select('disable');
              $('#proctor_id').prop('disabled', true);
              $('#proctor_instructions').prop('readonly', true);  
          }
        else
        {        	
        	$('#proctor_id').prop('disabled', false);
        	$('#proctor_instructions').prop('readonly', false);
        }
    });

 </script

@endsection
