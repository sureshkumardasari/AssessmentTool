					<div>
					<!-- @if (Session::has('flash_message'))
    						<div class="alert alert-info" id="flash" align="center">{{ Session::get('flash_message') }}</div>
							@endif -->
				</div>
                
				<input type="hidden" name="action" id="action" value="{{$role_name}}">
				<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
				<table class="table table-striped table-bordered " id="example"cellspacing="0" width="100%">

				        <thead>
				            <tr>
				                <th>Name</th>
				                <th>Email</th>
				                <th>Institution</th>
				                <th>Role</th>
				                <th>Status</th>
				                <th style="width: 80px !important;">Action</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $users as $user )
				            <tr>				                
				                <td>{{ $user->username }}</td>
				                <td>{{ $user->email }}</td>
				                <td>{{ $user->Institutionname }}</td>
				                <td>{{ $user->rolename }}</td>
				                <td>{{ $user->status }}</td>
				                <td>
				                @if($role_name == 'user')
                                 <a href="{{ url('/user/edit/'.$user->id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                  <a href="javascript:;" data-ref="{{ url('/user/del/'.$user->id ) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                               @else

                               <a href="{{ url('/user/edit/'.$user->id.'/'.$role_name) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                  <a href="javascript:;" data-ref="{{ url('/user/del/'.$user->id ) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                              @endif
                               

  <!--                         
                                 <button href="javascript:;" id="delete_user" class="btn btn-default btn-sm" title="Delete" onclick="delete_user({{$user->id}})"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button> -->
				                 
								</td>
				            </tr>
				            @endforeach				            
				        </tbody>
				    </table>
				 
 

{!! HTML::script(asset('/js/custom/confirm.js')) !!}
<!-- <script type="text/javascript">

function delete_user($id)
{
	var csrf=$('#_token').val();
	//alert(csrf);
	 $.ajax(
        {
        	headers: {"X-CSRF-Token": csrf},
            url: "user/del/"+$id,
            type: 'get',
            success: function ()
            {
                console.log("it Work");
            }
        });

}
</script> -->
 <script>
  	$(document).ready(function() {
    $('#example').DataTable({
	aoColumnDefs: [
  {
     bSortable: false,
     aTargets: [ -1 ]
  }
]
 });
});	
</script>
<!-- <script>
	$(document).ready(function(){
		var loadurl = "{{ url('/user') }}/" ;
		$('#userstable').dataTable( {
			order: [],
			columnDefs: [ { orderable: false, targets: [5] } ],
			"scrollY":        "850px",
			"scrollCollapse": true,
			"paging":         true
		} );
	});
		
		function getAssignmentsforgrading(){
			var csrf=$('Input#csrf_token').val();
			var loadurl = "{{ url('/user') }}/" ;

			$.ajax(
					{

						headers: {"X-CSRF-Token": csrf},
						url:loadurl + $('#institution_id').val(),
						type: 'get',
						success: function (response) {
							$('#userstable').dataTable().fnDestroy();
							//$('#assignbody').empty();
							var tr;
							for (var i = 0; i < response.length; i++) {
								tr = $('<tr/>');
								tr.append("<td>" + response[i].assessment_name + "");
								tr.append("<td>" + response[i].name + "");
								tr.append("<td>"+ "</td>");
								tr.append("<td>"+ "</td>");
								//$('#assignbody').append(tr);

							}
							$('#userstable').dataTable( {
			order: [],
			columnDefs: [ { orderable: false, targets: [5] } ],
			"scrollY":        "850px",
			"scrollCollapse": true,
			"paging":         true
		} );
						}
					})

		}
		
	</script> -->
<!-- {!! HTML::script(asset('/js/custom/confirm.js')) !!} -->
