					<table id="userstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Name</th>
				                <th>Email</th>
				                <th>Institution</th>
				                <th>Role</th>
				                <th>Status</th>
				                <th></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $users as $user )
				            <tr>				                
				                <td>{{ $user->username }}</td>
				                <td>{{ $user->email }}</td>
				                <td>{{ $user->Instname }}</td>
				                <td>{{ $user->rolename }}</td>
				                <td>{{ ($user->status == 'Yes') ? 'Active' : 'Inactive' }}</td>
				                <td>
				                	<a href="{{ url('/user/edit/'.$user->id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="{{ url('/user/del/'.$user->id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>					
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
			    