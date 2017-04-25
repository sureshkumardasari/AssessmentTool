                        <div>
					@if (Session::has('flash_message'))
    						<div class="alert alert-info" id="flash" align="center">{{ Session::get('flash_message') }}</div>
							@endif
				</div>
                        <!-- <div>
                            @if(Session::has('flash_message_failed'))
                                <div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
                            @endif
                        </div> -->

                       <table class="table table-striped table-bordered " id="example"cellspacing="0" width="100%">
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
{!! HTML::script(asset('/js/custom/confirm.js')) !!}
				    
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

