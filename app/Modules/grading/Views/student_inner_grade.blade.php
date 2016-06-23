@extends('default')
@section('content')
    <script>
        var first_user="{{$id}}";
        var Question_ids=[];
        var Question_actual_answer={};
        var Question_selected_answers={};
        var Answer_ids=[];

        var selected_student_answers={};
        <?php
            foreach($first_student_answers as $qid=>$ans){
                ?>
               // seleceted_student_answer[""]
                selected_student_answers["{{$qid}}"]="{{$ans}}";
        <?php
            }
        ?>
    </script>

<style>
    section .msgs_links ul li {
        margin-left: 0 !important;
    }
    .move-to-next {
        top: 300px !important;
    }
    .fancybox-inner{
        height: auto !important;
    }
    .fancybox-inner .fancybox-iframe{
        min-height: 700px !important;
        height: auto !important;
    }

    .question > p >img{
        display :block !important;
    }
</style>
{{--<section class="assesmant-q-details msgs_box">--}}
    {{--<div class="msgs_links mb24">--}}



        {{--<h1 class="fltL"></h1>--}}
        {{--<div class="clr"></div>--}}
    {{--</div>--}}
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Grade by Student
                    <a href="{{ url('/grading/list-student/'.$assessment_id.'-'.$assignment_id) }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> BACK</a>
                </div>
                <div class="panel-body">

                <?php $studentIndex = 0; ?>

                        <label class="col-md-2 control-label">Student Name</label>
                    <div class="col-md-4">
                        <select name="studentId" id="drpAssignmentStudent" class="custom_slct filter-listing w200" onchange="change_user_answers()">
                            @foreach($user_list_detail as $id=>$val)
                                <option value="{{ $val->id }}">{{ $val->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fltL mt8">
                        Institution:
                         <span class="institutions">

				        </span>
                    </div>
                    <div class="clr"></div>

                <div class="mL20 pb10">

                    <span class="txt_17_b w140 fltL">Date Taken:</span><span class="date-taken"></span>
                    <div class="clr"></div>
                </div>
                <div class="mL20">

                    <span class="txt_17_b w140 fltL">Date Graded:</span><span class="date-graded"></span>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
                    <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
       <div class="panel-body">
        <table  width="100%">
            <tbody>
            <?php
                    $j=1;
            $ans_arr = ['A', 'B', 'C', 'D', 'E'];
            ?>
            @foreach($questionss_list as $k=>$ass_qst)
             <tr>
                 <script>
                     Question_ids.push({{$ass_qst['Id']}});
                 </script>
                <td><b>Q. {{$ass_qst['Title']}}</b></td>
            </tr>

            <tr>
                <td>{{$ass_qst['ans_text']}}</td>
            </tr>
            {{--*/ $i = 0 /*--}}
            @foreach($ass_qst['answers'] as $idx => $a )
                <?php
                $ans_label = 'default';
                if($a['is_correct']=='YES'){$ans_label = 'success' ;
                ?> <script>Question_actual_answer['{{$ass_qst['Id']}}']=[];Question_actual_answer['{{$ass_qst['Id']}}'].push("{{$a['Id']}}");</script><?php  }?>
                <tr>
                    <td>
                        <script>Answer_ids.push("{{$a['Id']}}");</script>
                        {{$ans_arr[$i]}}. <span id="{{$a['Id']}}" class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>
                    </td>

                </tr>
                {{--*/ $i++ /*--}}
            @endforeach
             <tr>
                 <td>


                     <!-- Modal -->
                     <div class="modal fade" id="myModal{{$j}}" role="dialog">
                         <div class="modal-dialog">

                             <!-- Modal content-->
                             <div class="modal-content">
                                 <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                                     <h4 class="modal-title">{{$ass_qst['Title']}} </h4>
                                 </div>
                                 <div class="modal-body" id="{{$j}}">
                                     <p>Q. {{$ass_qst['ans_text']}}</p>
                                     {{--*/ $i = 0 /*--}}
                                     @foreach($ass_qst['answers'] as $a )
                                         <div>
                                             @if(($ass_qst['question_type'])=="Multiple Choice - Single Answer")
                                                 <input type="radio" name="ans_val{{$ass_qst['Id']}}" id="ans_val" value="{{$a['Id']}}">
                                             @elseif(($ass_qst['question_type'])=="Multiple Choice - Multi Answer")
                                                 <input type="checkbox" name="ans_val[]" id="ans_val" value="{{$a['Id']}}">
                                             @endif

                                             {{--*/
                                             $ans_label = 'default';
                                             if($a['is_correct']=='YES')$ans_label = 'success' ;
                                             /*--}}


                                             {{$ans_arr[$i]}}.
                                             <span  class="label label-{{$ans_label}}">{{$a['ans_text']}}</span>

                                         </div>
                                         {{--*/ $i++ /*--}}
                                     @endforeach

                                     <div>

                                     </div>

                                 </div>


                                 <div class="modal-footer">
                                     <button type="button" class="btn btn-default" data-dismiss="modal" onclick="change_answer({{$ass_qst['Id']}})">OK</button>
                                 </div>
                             </div>

                         </div>
                     </div>
                     <!-- Modal end -->
                 </td>
             </tr>
             <tr>
             <td>
                 <button type="button" class="btn btn-info btn-sm open-modal" data-toggle="modal" value="{{$ass_qst['Id']}}" data-target="#myModal{{$j++}}" >Edit</button>
             </td>
             </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" id="" data-target="#myModal" onclick="save_student_answers()">Save</button><button type="button" class="btn btn-info btn-sm" data-toggle="modal" id="" data-target="#myModal">Save and Grade</button>
    <div class="clr"></div>
                </div>
            </div>
        </div>
    </div>
{{--</section>--}}
@include('resources::grading.grading_js')

@endsection
<script>
//

function change_answer(question_id){
    var myControls=$('input[type="radio"][name=ans_val'+question_id+']');
    var id=$('input[type="radio"][name=ans_val'+question_id+']:checked').val();
    if(id=="undefined"){id=0;}
    Question_selected_answers[question_id]=id;
   // alert(JSON.stringify(Question_selected_answers));
    $.each(myControls , function(i, val) {
       var answer=val.value;
       if($.inArray(answer,Question_actual_answer[question_id])==-1)
       {
           $('#'+answer).removeClass('label-danger');
       }

   });
    if($.inArray(id,Question_actual_answer[question_id])==-1)
    {
        $("#"+id).addClass('label-danger');
    }
}

function save_student_answers(){
    var user_id=$('#drpAssignmentStudent').val();
    var next_student=$("#drpAssignmentStudent option:selected").next().val();
    //alert(next_student);
    //alert($('#drpAssignmentStudent').val());
   // alert(JSON.stringify(Question_selected_answers));
    var csrf=$('Input#csrf_token').val();
    $.ajax({
        headers: {"X-CSRF-Token": csrf},
        url:"save_answer_for_student_by_student_grade/{{$assessment_id}}/{{$assignment_id}}",
        type:"post",
        data:{'user_id':user_id,'question_selected_answers':Question_selected_answers,'next_student':next_student},
        success:function(response){
            //alert("responseeeeeeeeee");
            //alert(JSON.stringify(response));
            if(response=="Completed") {
                alert(response);
                $('#drpAssignmentStudent').val(next_student);
            }
            else if(response=="All students Graded"){
                alert("Grading completed");
            }
            else if(response== "please answer atlest one question"){
                alert(response);
            }
            else {
                selected_student_answers={};
                //alert(JSON.stringify(selected_student_answers));
                $.each(response,function(i,val){
                    selected_student_answers[i]=val;
                });
                //alert(JSON.stringify(selected_student_answers));
                $('#drpAssignmentStudent').val(next_student);
                user_answers();
            }
        }
    });
}
// function for showing  user answers as correct or incorrect.....
    function user_answers(){
        $.each(Answer_ids,function(i,val){
            $('#'+val).removeClass('label-danger');
        });
        $.each(Answer_ids,function(i,val){
            $('input[type="radio"][value='+val+']').prop('checked',false);
        });
        $.each(selected_student_answers,function(i,val){
            $('input[type="radio"][value='+val+']').prop('checked',true);
        });
       // alert(JSON.stringify(Question_actual_answer));
        //alert("hererererere");
        $.each(selected_student_answers, function(i,val){
           // alert( val.toString()+","+Question_actual_answer[i]+","+$.inArray(val,Question_actual_answer[i]));
            if($.inArray(val.toString(),Question_actual_answer[i])!=0){
                $('#'+selected_student_answers[i]).addClass('label-danger');
            }

        });
    }


//function for  updating user answers everytime when user is changed through ajax call...
    function change_user_answers(){
        var assessment_id="{{$assessment_id}}}";
        var assignment_id="{{$assignment_id}}}";
        var user_id=$('#drpAssignmentStudent').val();
        $.ajax({
           url:assessment_id+"/"+assignment_id+"/"+user_id,
            type:'get',
            success:function(response){
                selected_student_answers={};
                //alert(JSON.stringify(selected_student_answers));
                $.each(response,function(i,val){
                    selected_student_answers[i]=val;
                });
                //alert(JSON.stringify(selected_student_answers));
                user_answers();
            }
        });
    }
</script>
@section('footer-assets')
@parent
 @stop
