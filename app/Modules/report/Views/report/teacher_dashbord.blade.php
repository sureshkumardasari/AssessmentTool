<!-- @extends('default') -->
<!-- @section('content') -->
<div class="container">
        <div class="row">
        <div class="col-md-2 col-md-offset-1">
                <div class="panel panel-default">
					<table >
				        <thead>
				            <tr>
				               <th>Teacher Title</th>
				            </tr>
				        </thead>
				        <tbody id="question_list_filer">
				            @foreach( $tlist as $id => $value )
				            <tr>
				                <td>{{ $value['uname'] }}</td>
				                 
				            </tr>
				            @endforeach
				        </tbody>
				    </table>
				</div>
				<button type="button" class="btn btn-success"><a href="{{  url('/user')  }}">view Details</a></button>
			
			</div>
		</div>
</div>
<!-- @endsection -->