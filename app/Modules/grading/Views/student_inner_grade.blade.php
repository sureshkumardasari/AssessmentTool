@extends('default')
@section('content')
    <script>
        var first_user="{{$id}}";
        var Question_ids=[];
        var Question_actual_answer={};
        var Question_selected_answers={};
        var Answer_ids=[];
        var correct_answers=[];
        var selected_student_answers=[];
        <?php
       // if($question_type=="Multiple Choice - Multi Answer"){

//dd($first_student_answers);
            foreach($first_student_answers['student_answers'] as $qid => $ans){
            ?>
             Question_selected_answers["{{$qid}}"]=[];
        <?php
                foreach($ans as $val){
                //dd($ans);
            ?>
            Question_selected_answers["{{$qid}}"].push("{{$val}}");
        selected_student_answers.push("{{$val}}");


        // seleceted_student_answer[""]

        <?php
        }
        // }
        // dd($first_student_answers);
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
                    <a href="{{ url('/grading/list-student/'.$assignment_id.'-'.$assessment_id) }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> BACK</a>
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
                         <span class="institutions"><b>
                                 {{$institution_name['name']}}
                             </b>

				        </span>
                    </div>
                    <div class="clr"></div>

                    <div class="mL20 pb10">

                        <span class="txt_17_b w140 fltL">Date Taken:</span><b><span class="date-taken">

                            {{$first_student_answers['student_details']['takendate']}}

                    </span>
                        </b>
                        <div class="clr"></div>
                    </div>
                    <div class="mL20">
                        <span class="txt_17_b w140 fltL">Date Graded:</span><b><span class="date-graded">
                             {{$first_student_answers['student_details']['gradeddate']}}
                    </span>
                        </b>
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
                                <script>Question_actual_answer['{{$ass_qst['Id']}}']=[];</script>
                                @foreach($ass_qst['answers'] as $idx => $a )
                                    <?php
                                    $ans_label = 'default';
                                    if($a['is_correct']=='YES'){$ans_label = 'success' ;
                                    ?> <script>Question_actual_answer['{{$ass_qst['Id']}}'].push("{{$a['Id']}}");correct_answers.push("{{$a['Id']}}")</script>
                                    <?php } ?>
                                    <tr>
                                        <td>
                                            <script>Answer_ids.push("{{$a['Id']}}");</script>
                                            {{$ans_arr[$i]}}. <span id="{{$a['Id']}}" class="editable-{{$ass_qst['Id']}} label label-{{$ans_label}}">{{$a['ans_text']}}</span>
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
                                                                    <input type="checkbox" name="ans_val{{$ass_qst['Id']}}" id="ans_val-{{$ass_qst['Id']}}-{{$a['Id']}}" value="{{$a['Id']}}" >
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
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" id="" data-target="#myModal" onclick="save_student_answers()">Save and Grade</button>
                    <a href="{{ url('/grading/list-student/'.$assessment_id.'-'.$assignment_id) }}" class="btn btn-info btn-sm center"> Cancel</a>

                    <div class="clr"></div>
                </div>
            </div>
        </div>
    </div>
    {{--</section>--}}
    {{--@include('resources::grading.grading_js')--}}

@endsection
<script>
    //
    var question_type="{{$question_type}}";
    function change_answer(question_id){
        //alert(JSON.stringify(Question_actual_answer));

        if(question_type=="Multiple Choice - Single Answer"){
            var myControls=$('input[type="radio"][name=ans_val'+question_id+']');
            $.each(myControls , function(i, val) {
                var answer=val.value;
                if($.inArray(answer,Question_actual_answer[question_id])==-1)
                {
                    $('#'+answer).removeClass('label-danger');
                }

            });
            var id=$('input[type="radio"][name=ans_val'+question_id+']:checked').val();
            if(id=="undefined"){id=0;}
            Question_selected_answers[question_id]=id;
            // alert(JSON.stringify(Question_selected_answers));

            if($.inArray(id,Question_actual_answer[question_id])==-1)
            {
                $("#"+id).addClass('label-danger');
            }
        }
        else if(question_type=="Multiple Choice - Multi Answer"){
            //alert(JSON.stringify(Question_selected_answers));
            var myControls=$('input[type="checkbox"][name=ans_val'+question_id+']');
            Question_selected_answers[question_id]=[];
            // alert(JSON.stringify(myControls));
            $('.editable-'+question_id).removeClass('label-danger');
            $.each(myControls , function(i, val) {
                //alert($('#ans_val-'+question_id+'-'+val.value).is(":checked"));
                if($('#ans_val-'+question_id+'-'+val.value).is(":checked")){
                    Question_selected_answers[question_id].push(val.value);
                    if($.inArray(val.value,Question_actual_answer[question_id])==-1)
                    {
                        $('#'+val.value).addClass('label-danger');
                    }
                    //$('#'+val.value).addClass('label-danger');
                }
                else{

                }

            });
            //alert(JSON.stringify(Question_selected_answers));

        }

    }

    function save_student_answers(){
        //alert(JSON.stringify(Question_selected_answers));
        var user_id=$('#drpAssignmentStudent').val();
        var next_student=$("#drpAssignmentStudent option:selected").next().val();
//    if(question_type=="Multiple Choice - Single Answer"){
//        var data={'user_id':user_id,'question_selected_answers':Question_selected_answers,'next_student':next_student}
//    }
//    else if(question_type=="Multiple Choice - Mingle Answer"){
//        var data={'user_id':user_id,'question_selected_answers':Question_selected_answers,'next_student':next_student}
//    }
        //var data=
        //alert(next_student);
        //alert($('#drpAssignmentStudent').val());
        // alert(JSON.stringify(Question_selected_answers));
        var csrf=$('Input#csrf_token').val();
        $.ajax({
            headers: {"X-CSRF-Token": csrf},
            url:"save_answer_for_student_by_student_grade/{{$assessment_id}}/{{$assignment_id}}",
            type:"post",
            data:{'question_type':question_type,'user_id':user_id,'question_selected_answers':Question_selected_answers,'next_student':next_student},
            success:function(response){
                //alert(JSON.stringify(selected_student_answers));
                //alert("responseeeeeeeeee");
                alert(JSON.stringify(response));
                if(response=="Completed") {
                    alert(response);
                    //$('#drpAssignmentStudent').val(next_student);
                }
                else if(response=="All students Graded"){
                    alert("Grading completed");
                }
                else if(response== "please answer at least one question"){
                    alert(response);
                }
                else {
                    selected_student_answers=[];
                    Question_selected_answers={};
                    $.each(response['student_answers'],function(i,val){
                        Question_selected_answers[i]=[];
                        $.each(val,function(j,ans){
                            Question_selected_answers[i].push(ans);
                            // $.each(val,function(j,ans){
                            selected_student_answers.push(ans);
                        });

                    });
//                selected_student_answers=[];
//                //alert(JSON.stringify(selected_student_answers));
//                $.each(response,function(i,val){
//                    selected_student_answers.push(val);
//                });
                    //alert(JSON.stringify(selected_student_answers));
                    $('#drpAssignmentStudent').val(next_student);
                    user_answers();
                }
            }
        });
    }
    // function for showing  user answers as correct or incorrect.....
    function user_answers(){
        alert(JSON.stringify(selected_student_answers));
        alert(JSON.stringify(Question_selected_answers));
        $.each(Answer_ids,function(i,val){
            $('#'+val).removeClass('label-danger');
        });
        $.each(Answer_ids,function(i,val){
            $('input[type="radio"][value='+val+']').prop('checked',false);
            $('input[type="checkbox"][value='+val+']').prop('checked',false);
        });
        if(question_type=="Multiple Choice - Multi Answer"){
            $.each(selected_student_answers,function(i,val){
                $('input[type="checkbox"][value='+val+']').prop('checked',true);
                if($.inArray(val.toString(),correct_answers)<0){
                    $('#'+val).addClass('label-danger');
                }
            });
        }
        else if(question_type=="Multiple Choice - Single Answer"){
            $.each(selected_student_answers,function(i,val){
                $('input[type="radio"][value='+val+']').prop('checked',true);
                if($.inArray(val.toString(),correct_answers)<0){
                    $('span#'+selected_student_answers[i]).addClass('label-danger');
                }
            });
        }
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
                alert(JSON.stringify(response));
                selected_student_answers=[];
                Question_selected_answers={};
                var student_details=response['student_details'];
                var student_answers=response['student_answers'];
                $.each(student_answers,function(i,val){

                    Question_selected_answers[i]=[];
                    $.each(val,function(j,ans){
                        Question_selected_answers[i].push(ans);
                        selected_student_answers.push(ans);
                    });

                });
                $('.date-taken').text(student_details['takendate']);
                $('.date-graded').text(student_details['gradeddate']);
                user_answers();
            }
        });
    }
</script>
<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#drpAssignmentStudent').val(first_user);
        user_answers();
    });
</script>
@section('footer-assets')
    @parent
@stop
