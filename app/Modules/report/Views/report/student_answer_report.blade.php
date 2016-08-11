@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Student Answer Report</div>
                    <div class="panel-body">


                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <div class="col-md-3">
                            <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, '','All'); ?>
                        </div>
                             <div class="form-group">
                                    <label class="col-md-2 control-label">Select Assignment:</label>
                                    <div class="col-md-2">
                                        <select name="assign_id" class='form-control' id="assign_student" onchange="assignmt_change()">
                                            <option value="0" selected >-Select-</option>
                                        </select>
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Select Student:</label>
                                <div class="col-md-2">
                                    <select name="student_id" class='form-control' id="student" >
                                        <option value="0" selected >-Select-</option>
                                        @if(getRole()!="administrator")
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary" id="applyFiltersBtn" onclick="student_change()"> Go</button>
                                </div>
                            </div>
                     </div>

                    <div id="report">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var loadurl = "{{ url('/report/students_inst/') }}/" ;
        var assignmturl = "{{ url('/report/assignmt_inst/') }}/" ;
        var studentturl = "{{ url('/report/student_assignmt_inst/') }}/" ;
        var stdansloadurl = "{{ url('/report/students_ans_list/') }}/" ;
        function student_change(){
            var csrf=$('Input#csrf_token').val();

            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:stdansloadurl+$('#institution_id').val()+'/'+$('#assign_student').val()+'/'+$('#student').val(),
                        type:'post',
                        success:function(response){
                            var a=response.length;
                            $('#report').empty();
                            $('#report').append(response);
                        }
                    }
            )
        }
        $('#institution_id').on('change',function(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:assignmturl+ $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#assign_student').empty();
                            $('#student').empty();
                            var opt = new Option('--Select Assignment--', '0');
                            $('#assign_student').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#assign_student').append(opt);
                            }
                        }
                    }
            )
        });

        function inst_change(){

            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+ $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#student').empty();
                            var opt = new Option('--Select student--', '');
                            $('#student').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#student').append(opt);
                            }
                        }
                    }
            )

        }

          function assignmt_change(){
           var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:studentturl+$('#institution_id').val()+'/'+$('#assign_student').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#student').empty();
                            var opt = new Option('--Select student--', '');
                            $('#student').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#student').append(opt);
                            }
                        }
                    }
            )

        }

    </script>
    @endsection
