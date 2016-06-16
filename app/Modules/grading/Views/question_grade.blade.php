@extends('default')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					Grade by Question	
					<a href="{{ url('/grading/list-question/'.$assignment_id.'-'.$assessment_id) }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> BACK</a>				
				</div>
				<div class="panel-body">				
						<label class="col-md-2 control-label">Users</label>
						<div class="col-md-4">
							<select class="form-control" name="status" id="status" onchange="change_user_assign_qst()">
								<option value="">All</option>
								@foreach($assignmentUsersArr as $idx => $a )
									<option value="{{$a->id}}">{{$a->username}}</option>		
								@endforeach	
							</select>
						</div>				
				</div>	
				
				<div class="panel-body">
					<table  width="100%">
				       <tbody>
				       		<?php
				       		$ans_arr = ['A', 'B', 'C', 'D', 'E'];
				       		?>
				            <tr>				                
				                <td><b>Q. {{$ass_qst['Title']}}</b></td>
				            </tr>  

				            <tr>				                
				                <td>{{$ass_qst['qst_text']}}</td>
				            </tr> 	
				            {{--*/ $i = 0 /*--}}
				            @foreach($ass_qst['answers'] as $idx => $a )
				            	{{--*/ 
				                $ans_label = 'default';
				                if($a['is_correct']=='YES')$ans_label = 'success' ;
				                /*--}}
				            <tr>				                
				                <td>
				                {{$ans_arr[$i]}}. <span class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>
				                </td>
				            </tr> 
				             {{--*/ $i++ /*--}}
				            @endforeach	
				            <tr>
				                <td>	
				                	<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>

			                         <!-- Modal -->
									  <div class="modal fade" id="myModal" role="dialog">
									    <div class="modal-dialog">
									    
									      <!-- Modal content-->
									      <div class="modal-content">
									        <div class="modal-header">
									          <button type="button" class="close" data-dismiss="modal">&times;</button>
									          <h4 class="modal-title">{{$ass_qst['Title']}} </h4>
									        </div>
									        <div class="modal-body">
									          <p>Q. {{$ass_qst['qst_text']}}</p>
									          	{{--*/ $i = 0 /*--}}
									            @foreach($ass_qst['answers'] as $idx => $a )
									            <div>	
									            	@if(($ass_qst['question_type'])=="Multiple Choice - Single Answer")
									            	<input type="radio" name="ans_val" id="ans_val" value="{{$a['Id']}}">
									                @elseif(($ass_qst['question_type'])=="Multiple Choice - Multi Answer")
									                <input type="checkbox" name="ans_val[]" id="ans_val" value="{{$a['Id']}}">
									                @endif

									                {{--*/ 
									                $ans_label = 'default';
									                if($a['is_correct']=='YES')$ans_label = 'success' ;
									                /*--}}
									                

									                {{$ans_arr[$i]}}. 
									                <span class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>

									            </div> 
									            {{--*/ $i++ /*--}}
									            @endforeach	

									            <div>

									            </div>

									        </div>


									        <div class="modal-footer">
									          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									        </div>
									      </div>
									      
									    </div>
									  </div>
									  <!-- Modal end -->
								</td>
				            </tr>
				            		            
				        </tbody>
				    </table>
				</div>

			</div>
		</div>
	</div>
</div>
@endsection