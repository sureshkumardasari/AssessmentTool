					<table id="lessonstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
								<th>Lesson</th>
								<th>Subject</th>
								<th>Category</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $lessons as $id => $name )
				            <tr>
								<td>{{ $name->l_name }}</td>
								<td>{{ $name->subject_name }}</td>
								<td>{{ $name->cat_name }}</td>
				                <td>
				                	<a href="{{ url('/resources/lessonedit/'.$id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="javascript:;" data-ref="{{ url('/resources/lessondel/'.$id) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</td>
				            </tr>
				            @endforeach				            
				        </tbody>
				    </table>

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
{!! HTML::script(asset('/js/custom/confirm.js')) !!}