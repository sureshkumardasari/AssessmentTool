                        <div>
                           @if (Session::has('flash_message'))
    						<div class="alert alert-info" id="flash" align="center">{{ Session::get('flash_message') }}</div>
							@endif
                        </div>

                        <table id="categorytable" class="table table-striped table-bordered " cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Id</th>
				                <th>Name</th>
				                <th>Action</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $category as $id => $name )
				            <tr>
				                 <td>{{ $id }}</td>				                
				                <td>{{ $name }}</td>
				                <td>
				                	<a href="{{ url('/resources/categoryedit/'.$id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="javascript:;" data-ref="{{ url('/resources/categorydel/'.$id) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</td>
				            </tr>
				            @endforeach				            
				        </tbody>
				    </table>

<script>
  $(document).ready(function() {
    $('#categorytable').DataTable({
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
     }, 1000);
 })
 </script>
{!! HTML::script(asset('/js/custom/confirm.js')) !!}