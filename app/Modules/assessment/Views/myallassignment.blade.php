@extends('default')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">My Assignments					
				</div>
				<div class="panel-body">
					<!--  -->
					@if (!empty($myassignment) && count($myassignment) > 0)
			       	<table class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				       	<thead class='ffthead'>
				       		<tr>
				       			<th><span class="text">Assignment Name</span></th>
				       			<th><span class="text">Assessment Name</span></th>
				       			<th><span class="text">Start Date Time</span></th>
								<th><span class="text">End Date Time</span></th>
								<th><span class="text">Status</span></th>	
								<th></th>							
				       		</tr>
				       	</thead>
				       	<tbody id='all-test'>
				       		<!-- list -->
@foreach($myassignment['proctor_launch'] as $assignment)
	<?php
		$isVisible = false;
		$startDateTime 	= date('Y-m-d H:i:s', strtotime($assignment->StartDateTime));
		$endDateTime 	= date('Y-m-d H:i:s', strtotime($assignment->EndDateTime));

		$now 	= date('Y-m-d H:i:s');			       						

		if ($endDateTime < $now) {
			$isVisible = true;
		}
	?>
	<tr class="student-dashboard-assignments-list">
		<td><span class="text">{{$assignment->AssignmentName}}</span></td>
		<td><span class="text">{{$assignment->AssessmentName}}</span></td>
		<td><span class="text">{{ $assignment->StartDateTime }}</span></td>
		<td><span class="text">{{ $assignment->EndDateTime }}</span></td>
		<td>		
			<span class="text">	

				<?php				
					$isAlreadyStarted = false;
					if ( $assignment->AssignmentStatus == "upcoming" && $assignment->launchtype == 'proctor') {
						$status = 'upcoming';
						
					} else if ( $assignment->AssignmentStatus == "available") {
						$status = 'available';
						
					} else if ( $assignment->AssignmentStatus == "instructions") {
						$status = 'inprogress';
						
					} else if ( $assignment->AssignmentStatus == "test") {
						$status = 'inprogress';
						
					} else if ( $assignment->AssignmentStatus == "inprogress") {
						$status = 'inprogress';
						$isAlreadyStarted = true;
						
					}else if ( $isVisible == true || $assignment->AssignmentStatus == 'completed' || $assignment->AssignmentStatus == 'completed' ) {
						$status = 'completed';
						
					} else {
						$status = $assignment->AssignmentStatus;
						
					}
					echo ucwords($status);
				  ?>
			</span></span>
		</td>	
		@if ($status == 'available')
			<td><span class="text"><a class="anchar" href="{{ route('tests-instructions', array('id' => $assignment->AssessmentsId.'-'.$assignment->AssignmentId))}}?flag=instructions">Instructions</a></span></td>
		@elseif ($status == 'inprogress' && $isAlreadyStarted == false)
			<td><span class="text"><a href="{{ route('tests-detail', array('id' => $assignment->AssessmentsId.'-'.$assignment->AssignmentId )) }}" class="btn btn-primary">Start Test</a></span></td>
			<!-- <td><span class="text">{!! $status !!}</span></td> -->
		@elseif ($status == 'inprogress' && $isAlreadyStarted == true)
			<td><span class="text"><a href="{{ route('tests-detail', array('id' => $assignment->AssessmentsId.'-'.$assignment->AssignmentId )) }}" class="btn btn-primary">Resume Test</a></span></td>
		
		@elseif ($status == 'completed')
			<td><span class="text">Completed</span></td>	
		
		@elseif ($status == 'upcoming')
			<td><span class="text"></span></td>
		
		@else	
			<td><span class="text">{!! ucwords($status) !!}</span></td>
		@endif
	</tr>
@endforeach

@foreach($myassignment['system_launch'] as $assignment)
	<?php
		$isVisible = false;
		$endDateTime = false;

		$startDateTime 	= date('Y-m-d H:i:s', strtotime($assignment->StartDateTime));
		if($assignment->Expires=='0' || $assignment->Expires=='false')
		{
			if ($assignment->EndDateTime !=  null) {
				$endDateTime 	= date('Y-m-d H:i:s', strtotime($assignment->EndDateTime));
			}
		}
		$now 	= date('Y-m-d H:i:s');
//	echo $endDateTime;exit;
		if ($endDateTime != false && $endDateTime < $now) {
			$isVisible = true;
		}
        $nowAfter15Min = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($now)));
	?>
       

	<tr class="student-dashboard-assignments-list">
		<td><span class="text">{{$assignment->AssignmentName}}</span></td>
		<td><span class="text">{{$assignment->AssessmentName}}</span></td>
		<td><span class="text">{{ $startDateTime }}</span></td>
		<td><span class="text">{{ ($endDateTime == false)? 'Never expires' : $endDateTime }}</span></td>
		<td>		
			<span class="text">

				<?php					

					if ( $assignment->AssignmentStatus == "upcoming" && $startDateTime > $now) {
						$status="upcoming";

					}
					else if($assignment->AssignmentStatus=="upcoming" && $startDateTime < $now){
						if($endDateTime!=false){
							if($endDateTime >= $now)
							$status='available';
							else $status='completed';
						}
						else $status='available';

					}else if ( $assignment->AssignmentStatus == "available" || ($startDateTime < $now && $now <= $endDateTime && $assignment->AssignmentStatus != "completed")) {
						$status = 'available';
						
					} else if ( $assignment->AssignmentStatus == "instructions") {
						$status = 'inprogress';
						
					} else if ( $assignment->AssignmentStatus == "test") {
						$status = 'inprogress';
						
					} else if ( $isVisible == true || $assignment->AssignmentStatus == 'completed' || $assignment->AssignmentStatus == 'completed' ) {
						$status = 'completed';							
					} else {
						$status = $assignment->AssignmentStatus;
						
					}
					echo ucwords($status);					
				 ?>
			</span></span>
		</td>	
		@if ($status == 'available' || ($startDateTime < $now && ($now <= $endDateTime ) && $assignment->AssignmentStatus != "completed"))
			<td><span class="text"><a class="anchar" href="{{ route('tests-instructions', array('id' => $assignment->AssessmentsId.'-'.$assignment->AssignmentId))}}">Instructions</a></span></td>
		@elseif ($status == 'inprogress' && $assignment->AssignmentStatus == "instructions")
			<td><span class="text"><a href="{{ route('tests-detail', array('id' => $assignment->AssessmentsId.'-'.$assignment->AssignmentId)) }}" class="btn btn-primary">Start Test</a></span></td>
		@elseif ($status == 'inprogress' && $assignment->AssignmentStatus == "inprogress")
			<td><span class="text"><a href="{{ route('tests-detail', array('id' => $assignment->AssessmentsId.'-'.$assignment->AssignmentId)) }}" class="btn btn-primary">Resume Test</a></span></td>			
		@elseif ($status == 'completed')
			<td><span class="text">Completed</span></td>	
		@elseif ($status == 'upcoming' && ( $startDateTime < $now && ($assignment->Expires == false || $assignment->Expires =='0') ) )
			<td><span class="text"><a class="anchar" href="{{ route('tests-instructions', array('id' => $assignment->AssessmentsId.'-'.$assignment->AssignmentId))}}">Instructions</a></span></td>
		@elseif ($status == 'upcoming')
			<td><span class="text"></span></td>
		@else	
			<td><span class="text">{!! ucwords($status) !!}</span></td>
		@endif
	</tr>
        
@endforeach

<!-- list -->
				       	</tbody>
			        </table>
			        @else
			        <h3>No assignments found...!</h3>
			        @endif						
					<!--  -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection