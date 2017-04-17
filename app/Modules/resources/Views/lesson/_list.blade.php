	
	<div>
	@if (Session::has('flash_message'))
    						<div class="alert alert-info" id="flash" align="center">{{ Session::get('flash_message') }}</div>
							@endif
	</div>
	
	<table class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
						<thead>
				            <tr>
				                <th>Id</th>
								<th>Category</th>
								<th>Subject</th>
								<th>Lesson</th>
								<th>Action</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $lessons as $id => $name )
				            <tr>
				                 <td>{{ $name->l_id }}</td>
								<td>{{ $name->cat_name }}</td>
								<td>{{ $name->subject_name }}</td>
								<td>{{ $name->l_name }}</td>
				                <td>
				                	<a href="{{ url('/resources/lessonedit/'.$name->l_id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="javascript:;" data-ref="{{ url('/resources/lessondel/'.$name->l_id) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</td>
				            </tr>
				            @endforeach
				        </tbody>
				    </table>


					{!! HTML::script(asset('/js/custom/confirm.js')) !!}
<script>
  	@if(isset($from) && $from == 'search')
  	$(document).ready(function() {
	    $('.datatableclass').DataTable({
	    	language: {
		        paginate: {
		            previous: '‹',
		            next:     '›'
		        },
		        aria: {
		            paginate: {
		                previous: 'Previous',
		                next:     'Next'
		            }
		        }
		    }
	    });
	});
	@endif
</script>