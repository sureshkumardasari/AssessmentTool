@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">My Assignments					
				</div>
				<div class="panel-body">
					
					<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
			            <li class="active"><a href="#available" data-toggle="tab">Available Assignments</a></li>
			            <li><a href="#upcoming" data-toggle="tab">Upcoming Assignments</a></li>
			        </ul>
					
			        <div id="my-tab-content" class="panel-body tab-content">
			            <div class="tab-pane active" id="available">
			                <table id="assignmentstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
						        <thead>
						            <tr>
						                <th>Name</th>
						                <th></th>
						            </tr>
						        </thead>
						        <tbody>
						            @foreach( $myassignment['Available'] as $row )
						            <tr>				                
						                <td>{{ $row->name }}</td>
						                <td>
						                	<a href="{{ url('/resources/assignmentedit/'.$row->id) }}" class="btn btn-default btn-sm">Available</a>														
										</td>
						            </tr>
						            @endforeach				            
						        </tbody>
						    </table>
			            </div>
			            <div class="tab-pane" id="upcoming">
			                <table id="assignmentstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
						        <thead>
						            <tr>
						                <th>Name</th>
						                <th></th>
						            </tr>
						        </thead>
						        <tbody>
						            @foreach( $myassignment['Upcoming'] as $row )
						            <tr>				                
						                <td>{{ $row->name }}</td>
						                <td>
						                	<a href="{{ url('/resources/assignmentedit/'.$row->id) }}" class="btn btn-default btn-sm">Upcoming</a>														
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
	</div>
</div>
@endsection