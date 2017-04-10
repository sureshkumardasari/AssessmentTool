@extends('default')

@section('header-assets')

@section('content')

<?php 
 $dtFormat = 'Y/m/d g:i:s A';
?>
<style type="text/css">
.row {
	padding: 5px;
}
</style>
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
							<label class="col-md-3 control-label">Assessment Name </label>
							<div class="col-md-3 control-label">{{ $assignment->assessment_name }}</div>
						</div>						
					</div>

					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Description </label>
							<div class="col-md-3 control-label">{{ $assignment->description }}</div>
						</div>
						<div>
							<label class="col-md-3 control-label">Institution  </label>
							<div class="col-md-3 control-label">{{ $assignment->institution_name }}</div>
						</div>						
					</div>
					<div class="row">
						<div>
							<label class="col-md-3 control-label">Start Date Time  </label>
							<div class="col-md-3 control-label">{{ ($assignment->startdatetime) ? date($dtFormat, strtotime($assignment->startdatetime)) : ''}}</div>
						</div>
						@if(($assignment->neverexpires == 1 ))	
						<div>
							<label class="col-md-3 control-label">End Date Time </label>
							<div class="col-md-3 control-label">Never Expires</div>
						</div>
						@else				
						<div>
							<label class="col-md-3 control-label">End Date Time </label>
							<div class="col-md-3 control-label">{{ ($assignment->enddatetime) ? date($dtFormat, strtotime($assignment->enddatetime)) : ''}}</div>
						</div>
						@endif
					</div>

					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Launch Type </label>
							<div class="col-md-3 control-label">{{ $assignment->launchtype }}</div>
						</div>						
						<div>
							<label class="col-md-3 control-label">Delivery Method  </label>
							<div class="col-md-3 control-label">{{ $assignment->delivery_method }}</div>
						</div>
					</div>
					@if($assignment->launchtype == 'proctor')
					<div class="row">
						<div>
							<label class="col-md-3 control-label">Proctor </label>
							<div class="col-md-3 control-label">{{ $assignment->proctor_name }}</div>
						</div>					
						<div>
							<label class="col-md-3 control-label">Proctor Instructions </label>
							<div class="col-md-3 control-label">{{ $assignment->proctor_instructions }}</div>
						</div>						
					</div>
					@endif
					
					<div class="row">					
						<div>
							<label class="col-md-3 control-label">Selected Students:</label>							
						</div>						
					</div>
					<div class="row" style="width: 95%; margin-left: 10px;">						
						<div class="row" style="border: 1px solid #ccc;padding:5px">
							<div class="col-md-1">S.No</div>
							<div class="col-md-2">Name</div>
							<div class="col-md-2">Test Status</div>
							<div class="col-md-3">Test TakenDate</div>
							<div class="col-md-1">Score</div>
							<div class="col-md-3">Status</div>
						</div>
					</div>
					<?php
					$starr = ['upcoming'=>'Upcoming', 'instructions'=>'Instruction', 'test'=>'Test', 'completed'=>'Completed', 'inprogress'=>'Inprogress'];
					?>
					<div class="row" style="width: 95%; margin-left: 10px;">						
						@for($i=0;$i< count($assignmentUsersArr);$i++)
						
						<div class="row" style="border: 1px solid #ccc;">
							<div class="col-md-1">{{$i+1}}</div>
							<div class="col-md-2">{{ $assignmentUsersArr[$i]->username}}</div>
							<div class="col-md-2">{{ $starr[$assignmentUsersArr[$i]->status]}}</div>
							
							<div class="col-md-3">{{ ($assignmentUsersArr[$i]->status == 'completed') ? ($assignmentUsersArr[$i]->takendate) : '' }}</div>

							<div class="col-md-1">{{ ($assignmentUsersArr[$i]->status == 'completed') ? $assignmentUsersArr[$i]->rawscore : ''}}</div>
							

							@if($assignment->status != 'completed')
							<div class="col-md-3"><select id="auid_{{$assignmentUsersArr[$i]->auid}}">
								<?php
								foreach ($starr as $key => $value) {
									echo '<option value="'.$key. ( ($key == $assignmentUsersArr[$i]->status) ? '" selected' : '"').'>'.$value.'</option>';
								}
								?>
							</select>
							<button type="button" id="btn_{{$assignmentUsersArr[$i]->auid}}" value="{{$assignmentUsersArr[$i]->auid}}" class="btn btn-primary btnStatusUpdate">Update</button>
							</div>
							@endif
						</div>
						@endfor											
					</div>

					<div class="row"><BR>
						<div class="col-md-6 col-md-offset-4">							
							<a target="_blank" href="{{asset('data/assessment_pdf/assessment_'. $assignment->assessment_id .'.pdf')}}" ><button type="button" class="btn btn-primary"> Print Test</button></a> <!-- </button> -->
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

$(document).on('click','.btnStatusUpdate', function( e ){
    e.preventDefault();
    //alert($(this).val());
    var auid = $(this).val();
    var status = $("#auid_"+auid).val();
    var r = confirm("Are you sure want to change Status!");
	if (r == true) {
		$.ajax({
			type: "GET",
			url: "{{URL::route('assignment-userstatus')}}/"+auid+"/"+status,
			success: function (data) {
					window.location.reload();
					return false;
			}
		});   
	} else {
	    
	}    
});

</script>


@endsection
