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
				       			<th><span class="text">Name</span></th>
				       			<th><span class="text">Start Date Time</span></th>
								<th><span class="text">End Date Time</span></th>
								<th><span class="text">Status</span></th>	
								<th></th>							
				       		</tr>
				       	</thead>
				       	<tbody id='all-test'>
				       		{{--*/
				       			$lastIndex = '';
				       			$status = "";
				       		/*--}}
				       		@foreach($myassignment as $key => $test)
				       			@if ($lastIndex != $test->AssignmentId)
				       			{{--*/ $lastIndex = $test->AssignmentId;/*--}}
					       		<tr>
						       		<td><span class="text">{{ $test->name }}</span></td>
						       		<td><span class="text">{{ $test->StartDateTime }}</span></td>
						       		<td><span class="text">{{ $test->EndDateTime }}</span></td>
						       		<td>
						       				<?php
						       				$now 	= date('Y-m-d H:i:s');
						       				$startDateTime 	= date('Y-m-d H:i:s', strtotime($test->StartDateTime));
				       						if(($test->EndDateTime == null )||( $test->EndDateTime == "0000-00-00 00:00:00"))
				       						{
				       							$datetime = new DateTime('tomorrow');
												$endDateTime = $datetime->format('Y-m-d H:i:s');
				       						}
				       						else
				       						{
				       							$endDateTime = date('Y-m-d H:i:s', strtotime($test->EndDateTime));
				       						}
				       						/*IF Part: Non Expired  - Tests Part: Expireable Tests*/
				       						if ($test->launchtype == 'proctor') {
						       					if ($now > $endDateTime)
						       						$display_status = 'completed';
						       					elseif ($test->AssignmentStatus == "test")
						       						$display_status = 'available';
						       					else
						       						$display_status = $test->AssignmentStatus;
						       					
						       				}else{
						       					if (($test->Expires == '1')){
							       					if (($now >= $startDateTime) && ($now <= $endDateTime) && $test->AssignmentStatus == "upcoming"){
							       						$display_status = 'available';
							       						$status = "available";
							       					}elseif (($now >= $startDateTime) && ($now <= $endDateTime)){				       					
							       						$display_status = $test->AssignmentStatus;
							       						$status = $test->AssignmentStatus;
							       					}elseif ($test->AssignmentStatus == "upcoming" && $now > $endDateTime){
							       						$display_status = 'completed';
							       						$status = "completed"; 
							       					}else{
							       						$display_status = $test->AssignmentStatus;
							       						$status = $test->AssignmentStatus; 
							       					}							       					
							       				}else{
							       					if ($now >= $startDateTime && $test->AssignmentStatus == "upcoming")
							       					{	
							       						$display_status = 'available';
							       						$status = "available";
							       					}else
								       				{	
								       					$display_status = $test->AssignmentStatus;
								       					$status = $test->AssignmentStatus;
							       					}
							       				}
						       				}
						       				echo ucwords($display_status);
						       				?>
						       		</td>
						       	@endif		

						       	<?php
						       		if ($test->launchtype == 'proctor')				
					       			{
					       				if ($now > $endDateTime)
					       				$display_status = 'completed';
					       				elseif ($test->AssignmentStatus == "instructions")
					       				$display_status = '<a class="anchar" href="'.route('tests-instructions', array('id' => $test->AssessmentsId.'-'.$test->AssignmentId)).'?flag=instructions">Instructions</a>';		       				
					       				else 				       			
										$display_status = '--';

					       				echo '<td><span class="text">'.$display_status.'</span></td>';
					       			}
					       			else
					       			{
					       				if ($status == "available" || ($status != 'upcoming' && $status != 'completed'))
						       			$display_status = '<a class="anchar" href="'.route('tests-instructions', array('id' => $test->AssessmentsId.'-'.$test->AssignmentId)) .'">Instructions</a>';
						       		elseif ($status == "completed")
						       			$display_status = 'completed';
						       		else					       			
						       			$display_status = '--';				       		

						       		echo '<td><span class="text">'.$display_status.'</span></td>';
					       			}

					       			?>
					       			
						       	@if ($lastIndex != $test->AssignmentId && $key != 0)
					       		</tr>
					       		@endif			       		
				       		@endforeach
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