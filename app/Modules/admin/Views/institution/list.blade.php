@extends('default')
@section('content')
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Institutions
					<a href="{{ url('/user/institutionadd/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
					<a href="{{ url('/user/institutionBulkUpload') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Bulk Upload </a>
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
					<table id="institutionstable" class="table table-striped table-bordered " cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Id</th>
				                <th>Name</th>
				                <th >Action</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $institutions as $id => $name )
				            <tr>
				                <td>{{ $id }}</td>				                
				                <td>{{ $name }}</td>
				                <td>
				                	<a href="{{ url('/user/institutionedit/'.$id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="javascript:;" data-ref="{{ url('/user/institutiondel/'.$id) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
	$(document).ready(function() {
    $('#institutionstable').DataTable({
	aoColumnDefs: [
  {
     bSortable: false,
     aTargets: [ -1 ]
  }
]
 });
});
</script>
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
