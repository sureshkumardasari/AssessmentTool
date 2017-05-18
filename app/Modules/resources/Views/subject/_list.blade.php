<table class="table table-striped table-bordered " id="example"cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Id</th>
								<th>Category</th>
				                <th>Subject</th>
 				                <th>Action</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $subjectcategory as $id => $name )
 				            <tr>
 				                <td>{{ $name->s_id}}</td>
								<td>{{ $name->cat_name }}</td>
				                <td>{{ $name->subject_name }}</td>
 				                <td>
				                	<a href="{{ url('/resources/subjectedit/'.$name->s_id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="javascript:;" data-ref="{{ url('/resources/subjectdel/'.$name->s_id) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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

