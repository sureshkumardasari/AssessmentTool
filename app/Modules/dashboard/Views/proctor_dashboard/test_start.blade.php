@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Assignment Users
                    <a href="{{route('proctordashboard')}}" class="btn btn-primary btn-sm right">Back</a>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <div class="col-md-5">
                            <table id="student_table" class="table table-striped table-bordered parent-grid">
                                <caption style="color:red"><center><b>Not Started Students</b></center></caption>
                                <thead>
                                <td></td>
                                <td></td>
                                </thead>
                                <tbody>
                                @foreach($assignment_not_started_users as $user)
                                    <tr>
                                        <td>
                                            @if($user->status == "instructions")
                                                <input type="checkbox" class="not_started_user" name="user[]" value="{{$user->user_id}}">
                                            @endif
                                        </td>
                                        <td>
                                            {{$user->user_name}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-5">
                            <table id="student_selected_table" class="table table-striped table-bordered students-selected-parent-grid">
                                <caption style="color:red"><center><b>Started Students</b></center></caption>
                                <thead>
                                <td></td>
                                <td></td>
                                </thead>
                                <tbody class="child-grid">
                                @foreach($assignment_started_users as $user)
                                    <tr>
                                        <td class="col-md-4">
                                                <input type="checkbox" class="started_user" name="started_user[]" value="{{$user->user_id}}">
                                        </td>
                                        <td class="col-md-4">
                                            {{$user->user_name}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2" >
                            <div class="container">
                                <div class="row">
                                    <br><br><br><br>
                                    <div  class="btn-group" role="group">
                                        <div class="btn-group" role="group"><button class="btn btn-success start" onclick="startall('all')">Start All</button></div>
                                        <div class="btn-group" role="group"><button class="btn btn-success start" onclick="startall('selected')">Start Selected</button></div><br><br>
                                        <div class="btn-group" role="group"><button class="btn btn-danger stop" onclick="stopall('all')">Stop All</button></div>
                                        <div class="btn-group" role="group"><button class="btn btn-danger stop" onclick="stopall('selected')">Stop Selected</button></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var to_start_students=[];
        var to_stop_students=[];
        $(document).ready(function(){

            $('#student_table').DataTable({
                "dom": '<"top">rt<"bottom"i><"clear">',
                "paging":   false,
                "scrollY": 300

            });
            $('#student_selected_table').DataTable({
                "dom": '<"top">rt<"bottom"i><"clear">',
                "paging":   false,
                "scrollY": 300
            });
            hide_or_show_buttons();
        });
        function startall(type){
            var check=1;
            var users=$('.not_started_user');
            if(type=="all"){
                to_start_students=[];
                $.each(users, function () {
                    to_start_students.push($(this).val());
                });
            }
            else if(type=="selected"){
                if($('.not_started_user:checked').length==0){
                    check=0;
                }
                to_start_students=[];
                $.each(users,function(){
                    if($(this).is(':checked')){
                        var index=to_start_students.indexOf($(this).val());
                        if(index == -1 ){
                            $(this).addClass('started_user');
                            to_start_students.push($(this).val());
                        }
                    }

                    else{
                        var index=to_start_students.indexOf($(this).val());
                        if(index == 0 ){
                            to_start_students.splice(index,1);
                        }
                    }
                });
            }
            var already_started_students=$('.started_users').length;
            if(already_started_students == 0 ||already_started_students=="undefined"){
                already_started_students=0;
            }
            var data={'to_start_students':to_start_students,'assignment_id':'{{$assignment_id}}','already_started_students':already_started_students}
            var csrf=$('Input#csrf_token').val();
            if(check==1) {

                $.ajax({
                    headers: {"X-CSRF-Token": csrf},
                    url: 'update_status',
                    type: "post",
                    data: data,
                    success: function (response) {
                        if (response == "ok") {
                            $('#student_table').dataTable().fnDestroy();
                            $('#student_selected_table').dataTable().fnDestroy();
                            if (type == "all") {
                                var user = $(' .parent-grid tr').find('.not_started_user');
                            }
                            else if (type == "selected") {
                                var user = $(' .parent-grid tr').find('.not_started_user:checked');
                            }
                            user.each(function () {
                                $(this).removeClass('not_started_user').addClass('started_user');
                                var closestUl = $(this).closest('tr');
                                $(this).attr('checked', false)
                                var selected = closestUl.clone();
                                $(this).closest('tr').remove();
                                var value = $(this).val();
                                $('#student_selected_table' + ' .child-grid').append(selected);
                            });
                            $('#student_table').DataTable({
                                "dom": '<"top">rt<"bottom"i><"clear">',
                                "paging": false,
                                "scrollY": 300
                            });
                            $('<caption style="color:red"/>').html("<center><b>not students started</b></center>").prependTo('#student_table');
                            $('#student_selected_table').DataTable({
                                "dom": '<"top">rt<"bottom"i><"clear">',
                                "paging": false,
                                "scrollY": 300
                            });
                            $('<caption style="color:red"/>').html("<center><b>students started</b></center>").prependTo('#student_selected_table');
                            hide_or_show_buttons();
                        }
                        else if(response == "no"){
                            alert("Please select atleast one student");
                        }
                    }
                });
            }
            else{
                alert("please select");
            }

        }

        //to stop test for alresdy started students
        function stopall(type) {
            var check=1;
            var users = $('.started_user');
            if (type == "all") {
                to_stop_students=[];
                $.each(users, function () {
                    to_stop_students.push($(this).val());

                });
            }
            else if (type == "selected") {
                if($('.started_user:checked').length==0){
                    check=0;
                }

                    to_stop_students = [];
                    $.each(users, function () {
                        if ($(this).is(':checked')) {
                            var index = to_stop_students.indexOf($(this).val());
                            if (index == -1) {
                                $(this).addClass('selected_user');
                                to_stop_students.push($(this).val());
                            }
                        }

                        else {
                            var index = to_stop_students.indexOf($(this).val());
                            if (index == 0) {
                                to_stop_students.splice(index, 1);
                            }
                        }
                    });

            }
            var data={'to_stop_students':to_stop_students,'assignment_id':'{{$assignment_id}}'};
            var csrf=$('Input#csrf_token').val();
            if(check==1) {
                $.ajax({
                    headers: {"X-CSRF-Token": csrf},
                    url: 'update_status',
                    type: "post",
                    data: data,
                    success: function (response) {
                        if (response == "ok") {
                            $('#student_selected_table').dataTable().fnDestroy();
                            if (type == "all") {
                                var user = $(' .students-selected-parent-grid tr').find('.started_user');
                            }
                            else if (type == "selected") {
                                var user = $(' .students-selected-parent-grid tr').find('.started_user:checked');
                            }
                            user.each(function () {
                                $(this).removeClass('started_user');
                                var closestUl = $(this).closest('tr');
                                $(this).attr('checked', false);
                                $(this).closest('tr').remove();
                            });
                            $('#student_selected_table').DataTable({
                                "dom": '<"top">rt<"bottom"i><"clear">',
                                "paging": false,
                                "scrollY": 300
                            });
                            $('<caption style="color:red"/>').html("<center><b>students started</b></center>").prependTo('#student_selected_table');
                            hide_or_show_buttons();
                        }
//                        else if (response == "Assignment Completed") {
//                            alert("Assignment Completed");
//                        }
                        else if(response == "no"){
                            alert("Please select atleast one student");
                        }
                    }
                });
            }
            else{
                alert("please select");
            }


        }
        function hide_or_show_buttons(){
            var start=$('.not_started_user');
            var stop=$('.started_user');
            if(start.length==0 || start.length=="undefined"){
                $('.start').hide();
            }
            else {
                $('.start').show();
            }
            if(stop.length==0 || stop.length=="undefined"){
                $('.stop').hide();
            }
            else{
                $('.stop').show();
            }
        }

    </script>

@endsection