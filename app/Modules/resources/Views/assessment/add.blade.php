@extends('default')
@section('header-assets')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
		
			<div class="panel panel-default">			
			<div class="panel-heading">Create Assessment</div>
				<div class="panel-body">
					
					<div class="form-group required">
						<label class="col-md-4 control-label">Title</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="title" value="">
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

					<div class="col-md-12">
						@include('resources::assessment.partial.questions')
                    </div> 
                    
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection