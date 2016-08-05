@extends('default')
@section('content')
<div class="container">
        <div class="row">
        <div class="col-md-2 col-md-offset-1">
                <div class="panel panel-default">

					<table >
				        <thead>
				            <tr>
				               <th>Question Title</th>
				            </tr>
				        </thead>
				        <tbody id="question_list_filer">
				            @foreach( $list as $id => $value )
				            <tr>
				                <td>{{ $value['question_title'] }}</td>
				                 
				            </tr>
				            @endforeach
				        </tbody>
				    </table>
				</div>
				<!-- <button><a href="{{ url('/resources/question') }}">View Details</a></button> -->
				  <button type="button" class="btn btn-success"><a href="{{ url('/resources/question') }}">view Details</a></button>
			</div>
		</div>
</div>
@endsection