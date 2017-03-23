@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Student Answer Report</div>
                    <div class="panel-body">


                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        
        
                        <div class="form-group required">
                        <label class="col-md-2 control-label" id="mandatory" style="margin:0px 0 0 225px">Institution:</label>
                        <div class="col-md-6">
                        <select name="inst_id" class='form-control' id="institution_id" style="margin-left:-50px;">
                        <option value="0" selected >--Select--</option>
                        @foreach($inst_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                        @endforeach
                        </select><br>
                        </div>
                        </div>
                              <div class="form-group required">
                                    <label class="col-md-4 control-label"    style="margin:0px 0 0 225px">Assignment:</label>
                                    <div class="col-md-6">
                                        <select name="assign_id" class='form-control' id="assign_student" style="margin:-15px 0 0 359px" onchange="assignmt_change()">
                                            <option value="0" selected >--Select--</option>
                                            @if(getRole()!="administrator")
                                               <!--  @foreach($assignments as $id=>$ass)
                                                    <option value="{{$id}}">{{$ass}}</option>
                                                @endforeach -->
                                            @endif
                                        </select><br>
                                    </div>
                            </div>
                            <br><br>
                             <div class="form-group required">
                                <label class="col-md-4 control-label"  style="margin:0px 0 0 225px">Student:</label>
                                <div class="col-md-6">
                                    <select name="student_id" class='form-control' id="student" style="margin:-15px 0 0 359px">
                                        <option value="0" selected >--Select--</option>
                                        {{--@if(getRole()!="administrator")--}}
                                            {{--@foreach($users as $user)--}}
                                                {{--<option value="{{$user->id}}">{{$user->name}}</option>--}}
                                            {{--@endforeach--}}
                                        {{--@endif--}}
                                    </select><br>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-3"></div>
                                <div class="col-md-6" style="padding-left: 93px;">
                                    <button type="button" class="btn btn-primary" id="applyFiltersBtn" onclick="student_change()">Go</button>
                                </div>
                            </div>                 
                   <div class="form-group col-md-12">
                                <div class="col-md-7"></div><div class="col-md-5">
                        <a href="#" class="btn btn-primary" id="pdf" onclick="reports()">Export PDF</a>
                        <a href="#" class="btn btn-primary" id="xls" onclick="reports()">Export XLS</a>
                    </div></div>
 
                    <div id="report">

                    </div>
                 </div></div>
            </div>
        </div>
    </div>

    <script>
        var loadurl = "{{ url('/report/students_inst/') }}/" ;
        var assignmturl = "{{ url('/report/assignmt_inst/') }}/" ;
        var studentturl = "{{ url('/report/student_assignmt_inst/') }}/" ;
        var stdansloadurl = "{{ url('/report/students_ans_list/') }}/" ;
        function student_change(){
            if($('#institution_id').val()==0 || $('#assign_student').val()==0|| $('#student').val()==0){
                alert("please select all the fields");
            }
            else {
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

 /*       function inst_change(){

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

        }*/

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
        $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assign_student').val();
            var student_id=$('#student').val();
            window.open("{{ url('report/SAR_PDF/')}}/"+inst_id+"/"+assign_id+"/"+student_id);
        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assign_student').val();
            var student_id=$('#student').val();
            window.open("{{ url('report/SAR_XLS/')}}/"+inst_id+"/"+assign_id+"/"+student_id);
        });
         function reports(){
            
                $.ajax(
                        {

                            headers: {"X-CSRF-Token": csrf},
                            url: loadurl + $('#institution_id').val() + '/' + $('#assignment_id').val(),
                            type: 'post',
                            success: function (response) {
                                $('#report').empty();
                                $('#report').append(response);
                                $('#report').prepend($('.average'));
                            }
                        }
                )
            
        }

        

        $('#pdf').on('click',function(){

            var inst_id=$('#institution_id').val();
            var assign_id=$('#assignment_id').val();

            if(inst_id==0 || assign_id==0)
            {
                          alert("please select all the fields");
                           
            }
            else
            {
                window.open("{{ url('report/exportPDF/')}}/"+inst_id+"/"+assign_id);
            }
          
        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assignment_id').val();

            if(inst_id==0 || assign_id==0)
            {
                          alert("please select all the fields");
                           
            }
            else
            {
            window.open("{{ url('report/exportXLS/')}}/"+inst_id+"/"+assign_id);
        }
        });
    </script>
    @endsection
