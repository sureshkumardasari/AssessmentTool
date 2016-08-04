@extends('default')
@section('content')
    <script>
        var question_types=[];//list of question type for the assignment
        var Essay_answers={};// essay answers
        var Essay_scores={};
        var Essay_answer_scores={};// grader submit essay answer points
        var Question_selected_multi_answers={};//grader selected asnswers of multi answer type question
        var Question_selected_single_answers={};// grader selected answers of the single answer type question.
        var Question_actual_answer={};// actual answer of the question
        var Essay_question_ids=[];//list of essay question ids

        //---
        // var Question_actual_answer={};
        var Question_ids=[];// list of question ids of assignment assessment
        var Question_selected_answers={};// list of selected answers of each question
        var Answer_ids=[];// user submitted answers of the related question
        var correct_answers=[];// correct answers of the question
        var user_selected_correct_answers={};// list of user selected correct answers
        var selected_student_answers=[];// list of answer ids student submitted previous. useful at the time of page initial loading and while changing the user from the select box

        //---
    </script>
    @foreach($first_student_answers['student_answers'] as $key=>$answer)
        @if($key=="Essay")
            @foreach($answer as $id=>$value)
                <script>
                    Essay_answers["{{$id}}"]="{{$value['text']}}";
                    Essay_answer_scores["{{$id}}"]="{{$value['score']}}";
                    Essay_question_ids.push({{$id}});
                </script>
            @endforeach
        @elseif($key=="Multiple Choice - Multi Answer")
            @foreach($answer as $id=>$value)
                <script>Question_selected_multi_answers["{{$id}}"]=[];</script>
                @foreach($value as $val)
                    <script>
                        Question_selected_multi_answers["{{$id}}"].push({{$val}});
                        selected_student_answers.push("{{$val}}");
                    </script>
                @endforeach

            @endforeach
        @elseif($key=="Multiple Choice - Single Answer")
            @foreach($answer as $id=>$value)
                <script>Question_selected_single_answers["{{$id}}"]=[];</script>
                @foreach($value as $val)
                    <script>
                        Question_selected_single_answers["{{$id}}"].push({{$val}});
                        selected_student_answers.push("{{$val}}");
                    </script>
                @endforeach
            @endforeach
        @endif
    @endforeach

    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Grading By Student
                    <a href="{{ url('/grading/list-student/'.$assignment_id.'-'.$assessment_id) }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span> BACK</a>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Student:</label>
                            <div class="col-md-4">
                                <select name="student" id="student">
                                    @foreach($user_list as $id=>$name)
                                        <option value="{{$id}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-2 control-label">Type:</label>
                            <div class="col-md-4">
                                <select name="question_type" id="question_type" onchange="change_question_type()">
                                    <option>Select Question Type</option>
                                    @foreach($qst_select as $key=>$type)
                                        <script>
                                            question_types.push({{$key}});
                                        </script>
                                        <option value="{{$key}}">{{$type}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div>
                        <div>
                            <label class="col-md-2 control-label">Taken Date:</label>
                            <p id="date_taken">{{$first_student_answers['student_details']['takendate']}}</p>
                        </div>
                        <div>
                            <label class="col-md-2 control-label">Graded Date:</label>
                            <p id="graded_date">{{$first_student_answers['student_details']['gradeddate']}}</p>
                        </div>
                    </div>


                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Grading List
                    </div>
                    <div class="panel-body">
                        <?php
                        $j=1;
                        $ans_arr=['A','B','C','D','E'];
                        $i=0;
                        ?>

                        @foreach($qst_select as $key=>$question)
                            @if($question =="Essay")
                                <div id="question_type{{$key}}">
                                    <table>
                                        @foreach($qst[$key] as $quest)
                                            <tr>
                                                {{--//for displaying that the question is graded or not?--}}
                                                <td><span class="glyphicon glyphicon-ok completed" style="color:green" id="complete_status{{$quest['Id']}}"></span><span class="glyphicon glyphicon-remove incompleted" style="color:red" id="incomplete_status{{$quest['Id']}}"></span></td>
                                                <td>Q.{{$quest['Title']}}</td>

                                            </tr>
                                            <tr>
                                                <td>{{$quest['ans_text']}}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="modal fade" id="myModal{{$j}}" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title">{{$quest['Title']}} </h4>
                                                                </div>
                                                                <div class="modal-body" id="{{$j}}">
                                                                    <p>Q. {{$quest['ans_text']}}</p>
                                                                    <div id={{$quest['Id']}}>
                                                                        <textarea id='essay{{$quest["Id"]}}'></textarea>
                                                                    </div>
                                                                    <div></div>
                                                                </div>
                                                                <input type="number" name="essay_score" id="essay_score{{$quest["Id"]}}" question="{{$quest['Id']}}" max={{$quest['essayanswerpoint']}}>/{{$quest['essayanswerpoint']}}

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="change_essay_answer({{$quest['Id']}})">OK</button>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm open-modal" data-toggle="modal" value="{{$quest['Id']}}" data-target="#myModal{{$j++}}" >Response</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="col-md-offset-4">
                                        <button type="button" class="btn btn-primary" onclick="save_student_essay_answers()">save</button>
                                        <a class="btn btn-danger" href="{{ url('/grading/list-student/'.$assignment_id.'-'.$assessment_id) }}">cancel</a>
                                        <button type="button" class="btn btn-primary grade">Grade</button>
                                    </div>
                                </div>
                            @elseif( $question=="Multiple Choice - Multi Answer")
                                <div id="question_type{{$key}}">

                                    <table>
                                        @foreach($qst[$key] as $quest)
                                            <script>
                                                Question_ids.push({{$quest['Id']}});
                                            </script>
                                            <tr>
                                                {{--//for displaying that the question is graded or not?--}}
                                                <td><span class="glyphicon glyphicon-ok completed" style="color:green" id="complete_status{{$quest['Id']}}"></span><span class="glyphicon glyphicon-remove incompleted" style="color:red" id="incomplete_status{{$quest['Id']}}"></span></td>
                                                <td>Q.{{$quest['Title']}}</td>

                                            </tr>
                                            <tr>
                                                <td>{{$quest['ans_text']}}</td>
                                            </tr>
                                            <?php
                                            $i=0;
                                            ?>
                                            <script>Question_actual_answer['{{$quest['Id']}}']=[];</script>
                                            @foreach($quest['answers'] as $idx => $a )
                                                <?php
                                                $ans_label = 'default';
                                                if($a['is_correct']=='YES'){$ans_label = 'success' ;
                                                ?>
                                                <script>Question_actual_answer['{{$quest['Id']}}'].push("{{$a['Id']}}");correct_answers.push("{{$a['Id']}}")</script>
                                                <?php } ?>
                                                <tr>
                                                    <td>
                                                        <script>Answer_ids.push("{{$a['Id']}}");</script>
                                                        {{$ans_arr[$i]}}. <span id="{{$a['Id']}}" class="editable-{{$quest['Id']}} label label-{{$ans_label}}">{{$a['ans_text']}}</span>
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
                                                                    <h4 class="modal-title">{{$quest['Title']}} </h4>
                                                                </div>
                                                                <div class="modal-body" id="{{$j}}">
                                                                    <p>Q. {{$quest['ans_text']}}</p>
                                                                    {{--*/ $i = 0 /*--}}

                                                                    @foreach($quest['answers'] as $a )
                                                                        <div>
                                                                            <input type="checkbox" name="ans_val{{$quest['Id']}}" id="ans_val-{{$quest['Id']}}-{{$a['Id']}}" question="{{$quest['Id']}}" value="{{$a['Id']}}" >

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
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="change_multi_answer({{$quest['Id']}})">OK</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <!-- Modal end -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm open-modal" data-toggle="modal" value="{{$quest['Id']}}" data-target="#myModal{{$j++}}" >Edit</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="col-md-offset-4">
                                        <button type="button" class="btn btn-primary" onclick="save_student_multi_answers()">save</button>
                                        <a class="btn btn-danger" href="{{ url('/grading/list-student/'.$assignment_id.'-'.$assessment_id) }}">cancel</a>
                                        <button type="button" class="btn btn-primary grade">Grade</button>
                                    </div>

                                </div>
                            @elseif($question == "Multiple Choice - Single Answer")
                                <div id="question_type{{$key}}">
                                    <table>
                                        @foreach($qst[$key] as $quest)
                                            <script>
                                                Question_ids.push({{$quest['Id']}});
                                            </script>
                                            <tr>
                                                {{--//for displaying that the question is graded or not?--}}
                                                <td><span class="glyphicon glyphicon-ok completed" style="color:green" id="complete_status{{$quest['Id']}}"></span><span class="glyphicon glyphicon-remove incompleted" style="color:red" id="incomplete_status{{$quest['Id']}}"></span></td>
                                                <td>Q.{{$quest['Title']}}</td>

                                            </tr>
                                            <tr>
                                                <td>{{$quest['ans_text']}}</td>
                                            </tr>
                                            <?php
                                            $i=0;
                                            ?>
                                            <script>Question_actual_answer['{{$quest['Id']}}']=[];</script>
                                            @foreach($quest['answers'] as $idx => $a )
                                                <?php
                                                $ans_label = 'default';
                                                if($a['is_correct']=='YES'){$ans_label = 'success' ;
                                                ?>
                                                <script>Question_actual_answer['{{$quest['Id']}}'].push("{{$a['Id']}}");correct_answers.push("{{$a['Id']}}")</script>
                                                <?php } ?>
                                                <tr>
                                                    <td>
                                                        <script>Answer_ids.push("{{$a['Id']}}");</script>
                                                        {{$ans_arr[$i]}}. <span id="{{$a['Id']}}" class="editable-{{$quest['Id']}} label label-{{$ans_label}}">{{$a['ans_text']}}</span>
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
                                                                    <h4 class="modal-title">{{$quest['Title']}} </h4>
                                                                </div>
                                                                <div class="modal-body" id="{{$j}}">
                                                                    <p>Q. {{$quest['ans_text']}}</p>
                                                                    {{--*/ $i = 0 /*--}}

                                                                    @foreach($quest['answers'] as $a )
                                                                        <div>
                                                                            <input type="radio" name="ans_val{{$quest['Id']}}" id="ans_val"  question="{{$quest['Id']}}" value="{{$a['Id']}}">


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
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="change_single_answer({{$quest['Id']}})">OK</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <!-- Modal end -->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm open-modal" data-toggle="modal" value="{{$quest['Id']}}" data-target="#myModal{{$j++}}" >Edit</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="col-md-offset-4">
                                        <button type="button" class="btn btn-primary" onclick="save_student_single_answers()">save</button>&nbsp
                                        <a class="btn btn-danger" href="{{ url('/grading/list-student/'.$assignment_id.'-'.$assessment_id) }}">cancel</a>&nbsp
                                        <button type="button" class="btn btn-primary grade">Grade</button>
                                    </div>

                                </div>

                            @endif
                            <div>
                                <table>




                                </table>
                            </div>
                        @endforeach


                    </div>
                </div>

            </div>

        </div>

    </div>
    <script>
        $(function (){
            $.each(question_types,function(index,val){
                $('#question_type'+val).hide();
            });
        });
        function change_question_type(){
            // alert(question_types);
            $.each(question_types,function(index,val){
                $('#question_type'+val).hide();
            });
            var val=$('#question_type').val();
            $('#question_type'+val).show();
        }

        function  change_multi_answer(question_id){
            // alert(JSON.stringify(Question_selected_multi_answers));
            var myControls=$('input[type="checkbox"][name=ans_val'+question_id+']');
            Question_selected_multi_answers[question_id]=[];
            // alert(JSON.stringify(myControls));
            $('.editable-'+question_id).removeClass('label-danger');
            user_selected_correct_answers[question_id]=[];
            $.each(myControls , function(i, val) {
                //alert($('#ans_val-'+question_id+'-'+val.value).is(":checked"));
                if($('#ans_val-'+question_id+'-'+val.value).is(":checked")){
                    Question_selected_multi_answers[question_id].push(val.value);
                    if($.inArray(val.value,Question_actual_answer[question_id])==-1)
                    {
                        $('#'+val.value).addClass('label-danger');
                    }
                    else{
                        user_selected_correct_answers[question_id].push(val.value);
                       // alert(JSON.stringify(user_selected_correct_answers));
                    }
                    //$('#'+val.value).addClass('label-danger');
                }
                else{

                }

            });
            // alert(JSON.stringify(Question_selected_multi_answers));

            //Multi_answer_answers[]
            // alert(id);
            $('#incomplete_status'+question_id).hide();
            $('#complete_status'+question_id).show();
        }
        function change_single_answer(question_id){
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
            Question_selected_single_answers[question_id]=id;
            // alert(JSON.stringify(Question_selected_answers));
            user_selected_correct_answers[question_id]=[];
            if($.inArray(id,Question_actual_answer[question_id])==-1)
            {
                $("#"+id).addClass('label-danger');
            }
            else{
                user_selected_correct_answers[question_id].push(id);
            }
            $('#incomplete_status'+question_id).hide();
            $('#complete_status'+question_id).show();
        }
        function change_essay_answer(id){
            // alert(id);
            var t=$('#essay'+id);
            var essay_answer= t.val() || t.html() || t.text();
            // alert(essay_answer);
            //  alert($('#essay'+id).text());
            Essay_answers[id]=essay_answer;
            Essay_answer_scores[id]=$('#essay_score'+id).val();
            // alert(JSON.stringify(Essay_answers));
            //  alert(JSON.stringify(Essay_answer_scores));
            $('#incomplete_status'+id).hide();
            $('#complete_status'+id).show();
        }
        function save_student_essay_answers(){

            // alert(JSON.stringify(Essay_answers));
            // alert(Object.keys(Essay_answers).length);
            if(Object.keys(Essay_answers).length!=0 && Object.keys(Essay_answer_scores).length!=0){

                var csrf=$('Input#csrf_token').val();
                $.ajax({
                    headers: {"X-CSRF-Token": csrf},
                    url:"essay_grading_submit/{{$assessment_id}}/{{$assignment_id}}/"+2,
                    type:"post",
                    data:{
                        'essay_answers':Essay_answers,
                        'essay_answer_scores':Essay_answer_scores
                    },
                    success:function(response){
                        // alert(response);
                    }
                });

            }
            else{
                alert("please grade atlesat one Question");
            }
        }
        function save_student_single_answers(){
            var user_id=$('#student').val();
            var next_student=2;
            var question_type="Multiple Choice - Single Answer";
           // alert(JSON.stringify(Question_selected_single_answers));
            if(Object.keys(Question_selected_single_answers).length!=0){

                var csrf=$('Input#csrf_token').val();
                $.ajax({
                    headers: {"X-CSRF-Token": csrf},
                    url:"save_answer_for_student_by_student_grade/{{$assessment_id}}/{{$assignment_id}}",
                    type:"post",
                    data:{'question_type':question_type,'user_id':user_id,'question_selected_answers':Question_selected_single_answers,'next_student':next_student,'user_selected_correct_answers':user_selected_correct_answers},
                    success:function(response){
                        //alert(JSON.stringify(response));
                    }
                });

            }
            else{
                alert("please grade atlesat one Question");
            }

        }
        function save_student_multi_answers(){
            // alert(JSON.stringify(Question_selected_multi_answers));
            var user_id=$('#student').val();
            var next_student=0;
            var question_type="Multiple Choice - Multi Answer";
            // alert(JSON.stringify(Question_selected_multi_answers));
            if(Object.keys(Question_selected_multi_answers).length!=0){

                var csrf=$('Input#csrf_token').val();
                $.ajax({
                    headers: {"X-CSRF-Token": csrf},
                    url:"save_answer_for_student_by_student_grade/{{$assessment_id}}/{{$assignment_id}}",
                    type:"post",
                    data:{'question_type':question_type,'user_id':user_id,'question_selected_answers':Question_selected_multi_answers,'next_student':next_student,'user_selected_correct_answers':user_selected_correct_answers},
                    success:function(response){
                        // alert(response);
                    }
                });

            }
            else{
                alert("please grade at least one Question");
            }
        }

        $(document).ready(function(){
            //alert(JSON.stringify(Essay_answers));
            // alert(JSON.stringify(Question_selected_multi_answers));
            // alert(JSON.stringify(Question_selected_single_answers));
            // alert(JSON.stringify(Question_actual_answer));
            // alert(JSON.stringify(Question_selected_answers));
            //alert(JSON.stringify(correct_answers));
            // alert(JSON.stringify(Answer_ids));
            //alert(JSON.strinify(Essay_answers));
            $('.completed').hide();
            // alert(JSON.stringify(selected_student_answers));
            user_multi_answers();
            user_single_answers();
            user_essay_answers();
        });

        function user_answers(){

            user_multi_answers();
            user_single_answers();
            user_essay_answers();

        }
        function user_multi_answers(){
            //  alert(JSON.stringify(selected_student_answers));
            // alert(JSON.stringify(Question_selected_answers));

            $.each(Answer_ids,function(i,val){
                $('span#'+val).removeClass('label-danger');
            });
            $.each(Answer_ids,function(i,val){
                // $('input[type="radio"][value='+val+']').prop('checked',false);
                $('input[type="checkbox"][value='+val+']').prop('checked',false);
            });

            $.each(selected_student_answers,function(i,val){
                var input= $('input[type="checkbox"][value='+val+']');
                input.prop('checked',true);
                var question=input.attr('question');
                $('#incomplete_status'+question).hide();
                $('#complete_status'+question).show();
                if($.inArray(val.toString(),correct_answers)<0){
                    $('#'+val).addClass('label-danger');
                }
            });



        }
        function user_single_answers(){
            //  alert(JSON.stringify(selected_student_answers));
            // alert(JSON.stringify(Question_selected_answers));
            $.each(Answer_ids,function(i,val){
                $('#'+val).removeClass('label-danger');
                $('input[type="radio"][value='+val+']').prop('checked',false);
            });
            $.each(selected_student_answers,function(i,val){
                var input=$('input[type="radio"][value='+val+']');
                input.prop('checked',true);
                var question=input.attr('question');
                //alert(question);
                $('#incomplete_status'+question).hide();
                $('#complete_status'+question).show();
                if($.inArray(val.toString(),correct_answers)<0){
                    $('span#'+selected_student_answers[i]).addClass('label-danger');

                }
            });
        }
        function  user_essay_answers(){
            // alert(JSON.stringify(Essay_answers));
            $.each(Essay_question_ids,function(id,val){
                $('#essay'+val).html('');
            });
            $.each(Essay_answers, function(id,val){
                $('#incomplete_status'+id).hide();
                $('#complete_status'+id).show();
                $('#essay'+id).html(val);
                $('#essay_score'+id).val(Essay_answer_scores[id]);
            });
        }
        $('#student').on('change',function(){
            //alert($('#student').val());

            var assessment_id="{{$assessment_id}}";
            var assignment_id="{{$assignment_id}}";
            var user_id=$('#student').val();
            $.ajax({
                url:assessment_id+"/"+assignment_id+"/"+user_id,
                type:'get',
                success:function(response){
                    //alert(JSON.stringify(response));
                    selected_student_answers=[];
                    Question_selected_multi_answers={};
                    Question_selected_single_answers={};
                    Essay_answers={};
                    Essay_answer_scores={};
                    var student_details=response['student_details'];
                    var student_answers=response['student_answers'];
                    $.each(student_answers,function(i,val){
                        if(i=="Essay"){
                            $.each(val,function(q_id,answers){
                                Essay_answers[q_id]=answers['text'];
                                Essay_answer_scores[q_id]=answers['score'];

                            });

                        }
                        else if(i=="Multiple Choice - Multi Answer"){
                            $.each(val,function(q_id,answers){
                                Question_selected_multi_answers[q_id]=[];
                                $.each(answers,function(index,answer){
                                    Question_selected_multi_answers[q_id].push(answer);
                                    selected_student_answers.push(answer);
                                });



                            });
                        }
                        else if(i=="Multiple Choice - Single Answer"){
                            $.each(val,function(q_id,answers){
                                Question_selected_single_answers[q_id]=[];
                                $.each(answers,function(index,answer){
                                    Question_selected_single_answers[q_id].push(answer);
                                    selected_student_answers.push(answer);
                                });
                            });
                        }
                    });
                    $('#date_taken').val(student_details['takendate']);
                    $('.graded_date').val(student_details['gradeddate']);
                    user_answers();
                }
            });
        });

        $('.grade').on('click',function(){
            var assessment_id="{{$assessment_id}}";
            var assignment_id="{{$assignment_id}}";
            var user_id=$('#student').val();
            $.ajax({
                url:'manual_grade/'+assessment_id+'/'+assignment_id+'/'+user_id,
                type:"get",
                success:function(response){
                    alert(response);
                }


            });
        });

    </script>
@endsection
@section('footer-assets')
    @parent
@stop
