@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Lessons
					<a href="{{ url('/resources/lessonadd/') }}" class="btn btn-default btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
				</div>

				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-4 control-label">Institution</label>
						<div class="col-md-6">
							<select class="form-control" name="institution_id">
								<option value="0">Select</option>
								@foreach($inst_arr as $id=>$val)
								<option value="{{ $id }}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Category</label>
						<div class="col-md-6">
							<select class="form-control" name="category_id">
								<option value="0">Select</option>
								@foreach($category as $id=>$val)
								<option value="{{ $id }}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Subject</label>
						<div class="col-md-6">
							<select class="form-control" name="subject_id">
								<option value="0">Select</option>
								@foreach($subjects as $id=>$val)
								<option value="{{ $id }}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Name</th>
				                <th></th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach( $lessons as $id => $name )
				            <tr>				                
				                <td>{{ $name }}</td>
				                <td>
				                	<a href="{{ url('/resources/lessonedit/'.$id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>					
									<a href="{{ url('/resources/lessondel/'.$id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>					
								</td>
				            </tr>
				            @endforeach				            
				        </tbody>
				    </table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection