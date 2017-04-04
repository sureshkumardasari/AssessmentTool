@extends('default')
@section('content')
<div class="container">
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<ul class="nav nav-tabs" role="tablist">
			    <li><a href="{{ url('/resources/category') }}">Categories</a></li>
		        <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
		        <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
		        <li><a href="{{ url('/resources/question') }}">Questions</a></li>
		        <li class="active"><a href="{{ url('/resources/passage') }}">Passages</a></li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">Passages
					<a href="{{ url('/resources/passageadd/') }}" class="btn btn-default btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
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
					<table id="passagestable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Id</th>
				                <th>Name</th>
				                <th style="visibility: hidden;"></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $passages as $id => $name )
				            <tr>
				                <td>{{ $id }}</td>				                
				                <td>{{ $name }}</td>
				                <td>
									<a href="{{ url('/resources/passageview/'.$id) }}" class="btn btn-default btn-sm" title="Details"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
				                	<a href="{{ url('/resources/passageedit/'.$id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="javascript:;" data-ref="{{ url('/resources/passagedel/'.$id) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
