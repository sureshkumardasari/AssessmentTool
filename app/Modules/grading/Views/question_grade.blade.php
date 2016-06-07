@extends('default')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					Grade by Question	
					<a href="{{ url('/grading/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> BACK</a>				
				</div>
				<div class="panel-body">				
						<label class="col-md-2 control-label">Status</label>
						<div class="col-md-4">
							<select class="form-control" name="status" id="status" onchange="change_status()">
								<option value="">All</option>
								<option value="Not Started">Not Started</option>
								<option value="In Progress">In Progress</option>
								<option value="complete">Complete</option>
							</select>
						</div>				
				</div>	

				<div class="panel-body">
					<table id="assignmentstable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Question Title</th>
				                <th></th>
				                <th>Action</th>
				            </tr>
				        </thead>
				        <tbody>
				            @foreach($ass_qst as $id => $asn )
				            <tr>				                
				                <td>{{$asn['Title']}}</td>

				                <td>

				                </td>

				                <td>	
				                	<i class="icons ico-grade"  TITLE="grade" >
			                             <span class="reply_box">
			                                Grade
			                            </span>
			                         </i>
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