					<table id="subjectstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Subject</th>
				                <th>Category</th>
 				                <th></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $subjectcategory as $id => $name )
 				            <tr>
				                <td>{{ $name->subject_name }}</td>
				                <td>{{ $name->cat_name }}</td>
 				                <td>
				                	<a href="{{ url('/resources/subjectedit/'.$name->s_id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="javascript:;" data-ref="{{ url('/resources/subjectdel/'.$name->s_id) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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