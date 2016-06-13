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
							<select class="form-control" name="user" id="user" onchange="change_user()">
								<option value="0">All</option>
								@foreach($ass_usrs as $id=>$val)
									<option value="{{ $val->id }}">{{ $val->first_name." ".$val->last_name }}</option>
								@endforeach
							</select>
						</div>				
				</div>	
				<div class="panel-body">
					<table id="assignmentstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Student Name</th>
				                <th></th>
				                <th>Action</th>
				            </tr>
				        </thead>
				        <tbody id="student_list">
				            @foreach($ass_usrs as $id=>$val)
				            	<tr>
				            	<td>{{ $val->first_name." ".$val->last_name }} </td>
				            	<td> </td>
				            	<td> <a href="{{ url('/grading/list-student-question/'.$val->id) }}"><i class="icons ico-grade"></i></a></td>
				            	</tr>
				            @endforeach			            
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>
@include('resources::grading.grading_js')
@endsection