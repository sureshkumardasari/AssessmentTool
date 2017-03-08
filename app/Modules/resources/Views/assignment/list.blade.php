@extends('default')
@section('content')
<div class="container">
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Assignments
					<a href="{{ url('/resources/assignmentadd/') }}" class="btn btn-default btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
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
					<table id="assignmentstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Name</th>
								<th>AssessmentName</th>
								<th>StartDateTime</th>
								<th>EndDateTime</th>
								<th>Status</th>
				                <th></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $assignments as $id => $row )
				            <tr>
				                <td>{{ $row->name }}</td>
                                <td>{{$row->assessment_name}}</td>
								<td>{{date('Y/m/d g:i:s A', strtotime($row->startdatetime))}}</td>
								<td>{{($row->neverexpires == 1) ? 'Never expires' : date('Y/m/d g:i:s A', strtotime($row->enddatetime))}}  </td>
								<td>{{$row->status}}</td>
									<td>
				                	<a href="{{ url('/resources/assignmentview/'.$row->id) }}" class="btn btn-default btn-sm" title="Details" ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>	
				                	@if($row->status=="upcoming")
										<a href="{{ url('/resources/assignmentedit/'.$row->id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
										@endif
									<a href="javascript:;" data-ref="{{ url('/resources/assignmentdel/'.$row->id) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>					
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
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>
{!! HTML::script(asset('/js/custom/confirm.js')) !!}
@endsection