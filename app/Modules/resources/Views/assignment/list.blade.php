@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Assignments
					<a href="{{ url('/resources/assignmentadd/') }}" class="btn btn-default btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
				</div>

				<div class="panel-body">
					<table id="assignmentstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Name</th>
								<th>AssessmentName</th>
								<th>StartDateTime</th>
				                <th></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $assignments as $id => $row )
				            <tr>
				                <td>{{ $row->name }}</td>
                                <td>{{$row->assessment_name}}</td>
								<td>{{$row->startdatetime}}</td>
									<td>
				                	<a href="{{ url('/resources/assignmentview/'.$id) }}" class="btn btn-default btn-sm" title="Details" ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>	
				                	<a href="{{ url('/resources/assignmentedit/'.$id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="{{ url('/resources/assignmentdel/'.$id) }}" class="btn btn-default btn-sm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>					
								</td>
				            </tr>
							@endforeach
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection