@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					Student Grading
					<a href="{{ url('/grading/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> BACK</a>
				</div>

				<div class="panel-body">
						<label class="col-md-2 control-label">Student Name</label>
						<div class="col-md-4">
							<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
							<input type="hidden" name="assignmentid" id="assignmentid" value="{{ $assignment_id }}">
							<input type="hidden" name="assessmentid" id="assessmentid" value="{{ $assessment_id}}">

 							<select class="form-control" name="user" id="user" onchange="change_user1()">
								<option value="0">--All--</option>
								@foreach($ass_usrs as $id=>$val)
									<option value="{{ $val->id }}">{{ $val->first_name." ".$val->last_name}}</option>
								@endforeach
							</select>
						</div>
				</div>
				<div class="panel-body">
					<table id="assignmentstable" class="table table-striped table-bordered" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Student Name</th>
				                
				                <th>Action</th>
				            </tr>
				        </thead>
				        <tbody class="student_list">
				            @foreach($ass_usrs as $id=>$val)
				            	<tr>
				            	<td>{{ $val->first_name." ".$val->last_name }} </td>
				            	
				            	<td> <a href="{{ url('/grading/list-student-question/'.$val->id.-$assignment_id.-$assessment_id) }}"><i class="icons ico-grade"></i></a></td>
				            	</tr>
				            @endforeach
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
    $('#assignmentstable').DataTable({
	aoColumnDefs: [
  {
     bSortable: false,
     aTargets: [ -1 ]
  }
]
 });
});
</script>
@include('resources::grading.grading_js')
@endsection