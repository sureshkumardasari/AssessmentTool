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
					<div>
				       
				       		<?php
				       		$ans_arr = ['A', 'B', 'C', 'D', 'E'];
				       		?>
				            <div class="form-group">
								{{--//for displaying that the question is graded or not?--}}
								<div class="col-md-2"><span class="glyphicon glyphicon-ok completed" style="color:green" id="complete_status{{$ass_qst['Id']}}"></span><span class="glyphicon glyphicon-remove incompleted"  style="color:red" id="incomplete_status{{$ass_qst['Id']}}"></span>
				                <b><label style="color:green" class="control-label">Question Title:</label></b></div><div class="col-md-10"><p> <b>{{$ass_qst['Title']}}</b></p></div>
				            </div>  

				            <div class="form-group">				                
				                <div class="col-md-2"><b><p style="color:green">Question Text:</p></b></div><div class="col-md-10"><b>{{$ass_qst['qst_text']}}</b></div>
				            </div> 
				            <div></div>	
				            {{--*/ $i = 0 /*--}}
							@if(!(($ass_qst['question_type']=="Essay" )||($ass_qst['question_type']=="Fill in the blank")))
								@foreach($ass_qst['answers'] as $idx => $a )
									{{--*/
									$ans_label = 'default';
									if($a['is_correct']=='YES')$ans_label = 'success' ;
									/*--}}
								<div class="form-group">
									
									{{$ans_arr[$i]}}. <span id="{{$a['Id']}}" class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>
									
								</div>
								 {{--*/ $i++ /*--}}
								@endforeach
							@endif
				            <div>
				                	<div class="form-group col-md-offset-2">
				                	<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Edit</button>
				                	</div>

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
												@if(($ass_qst['question_type']!="Essay") && ($ass_qst['question_type']!="Fill in the blank"))
													@foreach( $ass_qst['answers'] as $idx => $a )
													<div>
														@if(($ass_qst['question_type'])== "Multiple Choice - Single Answer")
														<input type="radio" name="ans_val" id="ans_val" editattr="{{$a['Id']}}" class="answer_selection_part" value="{{$a['Id']}}">
														@elseif(($ass_qst['question_type'])=="Multiple Choice - Multi Answer")
														<input type="checkbox" name="ans_val[]" id="ans_val{{$a['Id']}}" editattr="{{$a['Id']}}" class="multiple_answer" value="{{$a['Id']}}">
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
												@elseif($ass_qst['question_type']=="Fill in the blank" )
													<div>
													<label>Response:</label>
														<p id="fib{{$ass_qst['Id']}}"></p>
														<label>Score:</label>
														<input type="number" name="fib_score" id="fib_score{{$ass_qst["Id"]}}" max={{$ass_qst['essayanswerpoint']}}>/{{$ass_qst['essayanswerpoint']}}
													</div>
												@else
													<div>
													<label>Response:</label>
														<p id="essay{{$ass_qst['Id']}}"></p>
														<label>Score:</label>
														<input type="number" name="essay_score" id="essay_score{{$ass_qst["Id"]}}" max={{$ass_qst['essayanswerpoint']}}>/{{$ass_qst['essayanswerpoint']}}
													</div>
												@endif

									            <div>

									            </div>

									        </div>


									        <div class="modal-footer">
									          <button type="button" class="btn btn-default essay_ok" data-dismiss="modal">OK</button>
									        </div>
									      </div>
									      
									    </div>
									  </div>
									  <!-- Modal end -->
								
				            </div>
					   <div>
						   
							   <br>
							   <div class="form-group">
								   <div>
									   <button type="button" class="btn btn-info btn-sm" id="save" >save & grade next student</button>
								   &nbsp
									   <a  type="button" class="btn btn-danger btn-sm" href="{{ url('/grading/list-question/'.$assignment_id.'-'.$assessment_id) }}">cancel</a>
								   </div>
							   </div>


							   {{--<div class="col-md-1 col-md-offset-7">--}}
							   {{--<button type="button" class="btn btn-info btn-sm">save & grade next student</button>--}}
							   {{--</div>--}}
					
					   </div>
				            		            
				        
				    </div>
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
	var question_type="{{$ass_qst['question_type']}}";
	var question_id="{{$ass_qst['Id']}}"
	var selected_answer_correct={};
	var selected_multi_answer_text={};
	var selected_multi_answer=[];
		var selected_answer_id=null;
		var selected_answer_text=null;
	var selected_answer_score=null;
		var is_correct="YES";
	$(function(){
		$('.completed').hide();
	});
		$(document).on('click','#save',function(e){
			e.preventDefault();
			mapTollTip1($(this));

		});
		$(document).on('change','#ans_val',function(e){
			//alert("single");
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
			
			var user_id=$("#status").val();
			//alert(user_id);
			var nextuserid=$("#status option:selected").next().val();
			
			if(question_type=="Multiple Choice - Multi Answer"){
				//alert($('Input[name=ans_val[]'));
				var data={"assessment_id":'{{$assessment_id}}',"assignment_id":"{{$assignment_id}}","question_type":question_type,"selected_answer_text":selected_multi_answer_text,"selected_answer":selected_multi_answer,"is_correct":selected_answer_correct,"user_id":user_id ,"nextuserid":nextuserid};

			}
			else if(question_type=="Multiple Choice - Single Answer"){
			var data={"assessment_id":'{{$assessment_id}}',"assignment_id":"{{$assignment_id}}","question_type":question_type,"selected_answer_text":selected_answer_text,"selected_answer":selected_answer_id,"is_correct":is_correct,"user_id":user_id ,"nextuserid":nextuserid};
			}
			else if(question_type=="Essay"){
				var data={"assessment_id":'{{$assessment_id}}',"assignment_id":"{{$assignment_id}}","question_type":question_type,"selected_answer_text":selected_answer_text,'selected_answer_score':selected_answer_score,"user_id":user_id ,"nextuserid":nextuserid};
			}
			else if(question_type=="Fill in the blank"){
				var data={"assessment_id":'{{$assessment_id}}',"assignment_id":"{{$assignment_id}}","question_type":question_type,"selected_answer_text":selected_answer_text,'selected_answer_score':selected_answer_score,"user_id":user_id ,"nextuserid":nextuserid};
			}
			var user_id=$('#status').val();
			
			var csrf=$('Input#csrf_token').val();
			$.ajax({
				headers: {"X-CSRF-Token": csrf},
				url:'save_answer_for_student_by_question_grade/{{$qst_id}}',
				type:'post',
				data: data,
				success:function(response){
					if(response=="All students graded"){
						alert("all students graded successfully");
					}
					else if(response=="No data given"){
						alert("please add atleast one  answer");
					}
					else {
					//	var index = $("#status option:selected").index();
						//$("#status option:eq(" + (index + 1) + ")").attr("selected", "selected");
						$("#status").val(nextuserid);
						change_user_answers();
					}

				}
			});
		}


		function change_user_answers(){
			var assessment_id="{{$assessment_id}}";
			var assignment_id="{{$assignment_id}}";
			var selected=$('#status').val();
			var status=null;
			var user_id=$('#status').val();
			var csrf=$('Input#csrf_token').val();
			$.ajax({
				headers: {"X-CSRF-Token": csrf},
				url:'next_student_answers_for_grade_by_question/'+user_id+'/'+question_type+','+assessment_id+','+assignment_id+','+'{{$qst_id}}',
				type:'get',
				success:function(response){
					//alert(response);
					$('.answer_selection_part').prop( "checked", false );
					$('.multiple_answer').prop("checked",false);
					$.each(ans_label , function(i, val) {
						if(ans_label [i]!="YES"){
							$('#'+i).removeClass('label-danger');
						}
					});
					if(question_type=="Multiple Choice - Single Answer"){
						$.each(response , function(i, val) {
							status=1;
						$('input[editattr~='+val+']').prop('checked',true);
						if(ans_label[val]!="YES"){

							$('#'+response[i]).addClass('label-danger');
						}
						is_correct=ans_label[val];
						selected_answer_id=val;
						selected_answer_text=$('#'+val).text();
					});
					}
					else if(question_type=="Multiple Choice - Multi Answer"){
						selected_multi_answer=[];
						selected_multi_answer_text={};
						selected_answer_correct={};
						$.each(response , function(i, val) {
							status=1;
							$('input[editattr~='+val+']').prop('checked',true);
							if(ans_label[val]!="YES"){

								$('#'+response[i]).addClass('label-danger');
							}
							selected_answer_correct[val]=(ans_label[val]);

							selected_multi_answer.push(val);
							selected_multi_answer_text[val]=($('#'+val).text());
						});
					}
					else if(question_type == "Essay"){
						$.each(response,function(score,text){
							status=1;
							$('#essay'+question_id).val(text);
							$('#essay_score'+question_id).val(score);
							selected_answer_text=text;
							selected_answer_score=score;
						});
						if(jQuery.isEmptyObject(response)){
                			$('#essay_score'+question_id).val(0);
            			}
					}
					else if(question_type == "Fill in the blank"){
						$.each(response,function(score,text){
							status=1;
							$('#fib'+question_id).val(text);
							$('#fib_score'+question_id).val(score);
							selected_answer_text=text;
							selected_answer_score=score;
						});
						if(jQuery.isEmptyObject(response)){
                			$('#fib_score'+question_id).val(0);
            			}
					}
					if(status==1){
						$('#incomplete_status'+question_id).hide();
						$('#complete_status'+question_id).show();
					}
					//alert(JSON.stringify(selected_answer_correct));
				}

			});

		}

		$('.multiple_answer').on('click',function(){
			//alert("multi");
			//alert(JSON.stringify(selected_multi_answer_text));
			//alert(JSON.stringify(selected_answer_correct));
			//alert($(this).is(':checked'));
			var checked_ans_val=$(this).val();
			//alert(checked_ans_val);
			//alert(selected_multi_answer);
			if($(this).is(':checked')){
				//alert(JSON.stringify(selected_answer_correct));
				selected_multi_answer.push(checked_ans_val);
				//alert(selected_multi_answer);
				selected_multi_answer_text[checked_ans_val]=$('#'+checked_ans_val).text();
				selected_answer_correct[checked_ans_val]=ans_label[checked_ans_val];
				if(ans_label[checked_ans_val]!="YES"){
					$('#'+checked_ans_val).addClass('label-danger');
				}
				else{

				}
				//alert(selected_multi_answer);
				// alert(JSON.stringify(selected_answer_correct));
			
			}
			else{
					//alert(JSON.stringify(selected_answer_correct));
				//alert(JSON.stringify(selected_multi_answer_text));
				var removeindex=selected_multi_answer.indexOf(checked_ans_val);
				selected_multi_answer.splice(removeindex,1);
				delete selected_multi_answer_text[checked_ans_val];
				delete selected_answer_correct[checked_ans_val];
					$('#'+checked_ans_val).removeClass('label-danger');

			//alert(JSON.stringify(selected_multi_answer_text));
					//alert(JSON.stringify(selected_answer_correct));
			}
			//alert(JSON.stringify(selected_multi_answer_text));
			//alert(JSON.stringify(selected_answer_correct));
			//alert(JSON.stringify(selected_answer_correct));
		});
		$('.essay_ok').on('click',function(){
			var question_id="{{$ass_qst['Id']}}";
			if(question_type=="Essay"){
				var t=$('#essay'+question_id);
				selected_answer_text= t.val() || t.html() || t.text();
				selected_answer_score=$('#essay_score'+question_id).val();
				//alert(selected_answer_text);
				//alert(selected_answer_score);
			}
            else if(question_type=="Fill in the blank"){
                var t=$('#fib'+question_id);
                selected_answer_text= t.val() || t.html() || t.text();
                selected_answer_score=$('#fib_score'+question_id).val();
            }
		});
	</script>
@endsection