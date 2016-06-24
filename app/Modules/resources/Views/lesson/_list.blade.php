 						<table id="lessonstable" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
				            <tr>
								<th>Category</th>
								<th>Subject</th>
								<th>Lesson</th>
								<th></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $lessons as $id => $name )
				            <tr>
								<td>{{ $name->cat_name }}</td>
								<td>{{ $name->subject_name }}</td>
								<td>{{ $name->l_name }}</td>
				                <td>
				                	<a href="{{ url('/resources/lessonedit/'.$name->l_id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="javascript:;" data-ref="{{ url('/resources/lessondel/'.$name->l_id) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</td>
				            </tr>
				            @endforeach
				        </tbody>
				    </table>
					{!! HTML::script(asset('/js/custom/confirm.js')) !!}
