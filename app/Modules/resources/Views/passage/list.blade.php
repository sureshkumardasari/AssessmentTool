@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Passages
					<a href="{{ url('/resources/passageadd/') }}" class="btn btn-default btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
				</div>

				<div class="panel-body">
					<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Name</th>
				                <th></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $passages as $id => $name )
				            <tr>				                
				                <td>{{ $name }}</td>
				                <td>
				                	<a href="{{ url('/resources/passageedit/'.$id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="{{ url('/resources/passagedel/'.$id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>					
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