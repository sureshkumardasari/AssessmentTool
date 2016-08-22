@extends('default')

@section('header-assets')

@section('content')

<?php 
 $dtFormat = 'Y/m/d g:i:s A';
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Assignment Details</div>
				<div class="panel-body">	
					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Assignment Name </label>
							<div class="col-md-3 control-label">{{ $assignment->name }}</div>
						</div>
						<div>
							<label class="col-md-3 control-label">Description </label>
							<div class="col-md-3 control-label">{{ $assignment->description }}</div>
						</div>
					</div>

					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Assessment Name </label>
							<div class="col-md-3 control-label">{{ $assignment->assessment_name }}</div>
						</div>
						<div>
							<label class="col-md-3 control-label">Start Date Time  </label>
							<div class="col-md-3 control-label">{{ ($assignment->startdatetime) ? date($dtFormat, strtotime($assignment->startdatetime)) : ''}}</div>
						</div>
					</div>
					<div class="row">					
						<div>
							<label class="col-md-3 control-label">End Date Time </label>
							<div class="col-md-3 control-label">{{ ($assignment->enddatetime) ? date($dtFormat, strtotime($assignment->enddatetime)) : ''}}</div>
						</div>
						<div>
							<label class="col-md-3 control-label">Never Expires </label>
							<div class="col-md-3 control-label">{{ ($assignment->neverexpires == 1 ) ? 'Yes' : 'No' }}</div>
						</div>
					</div>

					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Launch Type </label>
							<div class="col-md-3 control-label">{{ $assignment->launchtype }}</div>
						</div>
						<div>
							<label class="col-md-3 control-label">Proctor </label>
							<div class="col-md-3 control-label">{{ $assignment->proctor_name }}</div>
						</div>
					</div>
					
					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Proctor Instructions </label>
							<div class="col-md-3 control-label">{{ $assignment->proctor_instructions }}</div>
						</div>
						<div>
							<label class="col-md-3 control-label">Institution  </label>
							<div class="col-md-3 control-label">{{ $assignment->institution_name }}</div>
						</div>
					</div>

					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Selected Students</label>
							<div class="col-md-3 control-label">
								@for($i=0;$i< count($assignmentUsersArr);$i++)
								<li>{{$assignmentUsersArr[$i]->username}}</li>
								@endfor

							</div>
						</div>
						<div>
							<label class="col-md-3 control-label">Delivery Method  </label>
							<div class="col-md-3 control-label">{{ $assignment->delivery_method }}</div>
						</div>
					</div>

						<div class="row">
							<div class="col-md-6 col-md-offset-4">							
								<!-- <button type="button" class="btn btn-primary">  --><a target="_blank" href="{{asset('data/assessment_pdf/assessment_'. $assignment->assessment_id .'.pdf')}}" ><button type="button" class="btn btn-primary"> Print Test</button></a> <!-- </button> -->
								<button type="button"  class="btn btn-primary btnAnswerKeys">Print Answer Key</button>
							</div>
						</div>					
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
 /**
 * Handle Click Event Of btnAnswerKeys | Download Print Answer Key
 * @return  void(0)
 */
$(document).on('click','.btnAnswerKeys', function( e ){
    e.preventDefault();
    _downloadCSVFile('/resources/get-print-answer-key-csv');
});

 function  _downloadCSVFile(urlVal) {
        // var urlVal = '/assessment/get-print-answer-key-csv';
        var assid = '{{$assignment->id}}';
	$.ajax({
		type: 'get',
		url: urlVal,
		data: {assignmentId: assid},
		dataType: 'json',
		beforeSend: function() {},
		success: function(response) {
		    if(response.success == true){
		        window.location.href = response.fileUrl;
		    }
		},
		complete: function(response) {
		},
		error: function(response) {
		}
	});
}

</script>


@endsection
