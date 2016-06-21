@extends('default')
@section('header-assets')
	@parent
	{!! HTML::script(asset('assets/js/common.js')) !!}
	{!! HTML::script(asset('assets/js/grade.js')) !!}
@stop
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
							<select class="form-control" name="status" id="status" onchange="change_user_answers()">
								<option value="0">All</option>
								@foreach($assignmentUsersArr as $idx => $a )
									<option value="{{$a->id}}">{{$a->username}}</option>		
								@endforeach	
							</select>
						</div>				
				</div>
				<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
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
				                {{$ans_arr[$i]}}. <span id="{{$a['Id']}}" class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>
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
									            	<input type="radio" name="ans_val" id="ans_val" editattr="{{$a['Id']}}" class="answer_selection_part" value="{{$a['Id']}}">
									                @elseif(($ass_qst['question_type'])=="Multiple Choice - Multi Answer")
									                <input type="checkbox" name="ans_val[]" id="ans_val{{$a['Id']}}" editattr="{{$a['Id']}}" class="answer_selection_part" value="{{$a['Id']}}">
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
									          <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
									        </div>
									      </div>
									      
									    </div>
									  </div>
									  <!-- Modal end -->
								</td>
				            </tr>
					   <tr>
						   <td class="form-group">
							   <br>
							   <div class="col-md-1">
							   <button type="button" class="btn btn-info btn-sm" id="save" >save</button>
							   </div>
							   <div>
						   <a class="col-md-1">cancel</a>
							   </div>
							   <div class="col-md-1 col-md-offset-7">
							   <button type="button" class="btn btn-info btn-sm">save & grade next student</button>
							   </div>
						   </td>
					   </tr>
				            		            
				        </tbody>
				    </table>
				</div>

			</div>
		</div>
	</div>
</div>
<script>
	//var ans_label={};
	ans_label= {<?php $c=array(); $i=1; $b= count($ass_qst['answers']); foreach($ass_qst['answers'] as $idx => $a){
		if($i++ < $b){
		echo "\"{$a['Id']}\":\"{$a['is_correct']}\",";}
		else {$c[0]=$a['Id'];
			$c[1]=$a['is_correct'];}
	}
	echo "\"{$c[0]}\":\"{$c[1]}\"";
			?>};

</script>

	<script>
		var selected_answer_id=null;
		var selected_answer_text=null;
		var is_correct="YES";
		$(document).on('click','#save',function(e){
			e.preventDefault();
			mapTollTip1($(this));

		});
		$(document).on('change','#ans_val',function(e){
			e.preventDefault();
			$.each(ans_label , function(i, val) {
				if(ans_label [i]!="YES"){
					$('#'+i).removeClass('label-danger');
				}

			});
			var id=$(this).val();
			if(ans_label[id]!="YES"){
				//is_correct="NO";
				$('#'+id).addClass('label-danger');
			}
			is_correct=ans_label[id];
			selected_answer_id=id;
			selected_answer_text=$('#'+id).text();
			//alert(selected_answer_text);


		});

		function mapTollTip1(tipTarget){
			// removing already open tool tip if it exists.
			$('.tip-container').remove();
			//var formativeUrl = $(tipTarget).attr('formative-url');
			// var questionUrl = $(tipTarget).attr('question-url');
			// var url="grading/list-question/1-1";
			var val = tipTarget.html();
			var OkBtnText     = "Yes";
			var cnclBtnText   = "No";
			var headerText    = "";
			//var okclassName   = 'btn-grade';
			//var cnclclassName = 'btn-grade-cncl';

			myDialog(tipTarget, {
				headerText: "",
				message: headerText,
				buttons: [
					{
						text: OkBtnText,
						//className: okclassName,
						click: function(e) {
							$('.tip-container').remove();
							$('.ssi-tip-arrow').remove();
							save_question_grade_for_student();
							//    if(typeof id =="undefined"){
							//
							//    }
							//     //window.location.href=url;
							//    else
							//        sendRequest('delete',id);
						}
					}, {
						text: cnclBtnText,
						//className: okclassName,
						click: function(e) {
							if(typeof id =="undefined"){
								$('.tip-container').remove();
								$('.ssi-tip-arrow').remove();
								//window.location.href=url;
							} else {
								e.preventDefault();
								// $('.ssi-tip').remove();
								// $('.tip-container').remove();
							}
						}
					}
				]
			});
			$('.tip-container').addClass('r0');
			$("input.orderTopicToolTip").val(val);
//            $('.ssi-tip').attr('style','top: 483px; left: 1048.5px;');
//            var val = $("input.orderTopicToolTip").val();
		}

		function save_question_grade_for_student(){
			var nextuserid=$("#status option:selected").next().val();
			var question_type="{{$ass_qst['question_type']}}";
			if(question_type=="Multiple Choice - Multi Answer"){
				alert($('Input[name=ans_val[]'));
			}
			var user_id=$('#status').val();
			var data={"assessment_id":'{{$assessment_id}}',"assignment_id":"{{$assignment_id}}","selected_answer_text":selected_answer_text,"is_correct":is_correct,"user_id":user_id ,"nextuserid":nextuserid};
			var csrf=$('Input#csrf_token').val();
			$.ajax({
				headers: {"X-CSRF-Token": csrf},
				url:'save_answer_for_student_by_question_grade/'+selected_answer_id+"/{{$qst_id}}",
				type:'post',
				data: data,
				success:function(response){
					if(response=="All students graded"){
						alert("all students graded successfully");
					}
					else {
						var index = $("#status option:selected").index();
						$("#status option:eq(" + (index + 1) + ")").attr("selected", "selected");
						change_user_answers();
					}

				}
			});
		}


		function change_user_answers(){
			var selected=$('#status').val();
			//$("#status").children().removeAttr("selected");
			//$("#status option:selected").attr('selected','selected');
			//$('#status').val(selected).attr('selected',"selected");
			//alert(nextuserid);
			//$("#status option').attr("selected", "selected");
			alert($('#status').val());
			var user_id=$('#status').val();
			var csrf=$('Input#csrf_token').val();
			$.ajax({
				headers: {"X-CSRF-Token": csrf},
				url:'next_student_answers_for_grade_by_question/'+user_id+'/{{$qst_id}}',
				type:'get',
				success:function(response){
					//e.preventDefault();
					$('.answer_selection_part').prop( "checked", false );

					//alert(response);
					$.each(ans_label , function(i, val) {
						if(ans_label [i]!="YES"){
							$('#'+i).removeClass('label-danger');
						}
					});
					$.each(response , function(i, val) {
						//alert(ans_label[val]+','+response[i]);
						$('input[editattr~='+val+']').prop('checked',true);
						//selected_answer_id=val;
						if(ans_label[val]!="YES"){

							$('#'+response[i]).addClass('label-danger');
						}
						is_correct=ans_label[val];
						selected_answer_id=val;
						selected_answer_text=$('#'+val).text();
						//alert(selected_answer_text);
					});
				}
			});
		}
	</script>
@endsection