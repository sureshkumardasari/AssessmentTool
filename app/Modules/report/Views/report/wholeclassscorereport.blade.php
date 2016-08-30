@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Whole Class Score Report</div>
                    <div class="panel-body">
                    <form class="form-horizontal">
                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, '','All'); ?>
                        
                            {{--<div class="form-group required">--}}
                                {{--<label class="col-md-2 control-label">institution:</label>--}}
                                {{--<div class="col-md-2">--}}
                                    {{--<select name="inst_id" class='form-control' id="institution_id" >--}}
                                        {{--<option value="0" selected >-Select Institution-</option>--}}
                                        {{--@foreach($inst_arr as $id=>$val)--}}
                                            {{--<option value="{{ $id }}">{{ $val }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Assignment:</label>
                                <div class="col-md-6">
                                    <select name="assignment_id" class='form-control' id="assignment_id"  >
                                        <option value="0" selected >-Select Assignment-</option>
                                        @if(getRole()!="administrator")
                                            @foreach($assignment as $ass)
                                                <option value="{{$ass->id}}">{{$ass->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                            <label class="col-md-4 control-label">Subject</label>
                            <div class="col-md-6">
                                <select class="form-control" name="subject_id" id="subject_id" class="multipleSelect" multiple="multiple">
                                    <option value="0">-Select Subject-</option>
                                  
                                </select>
                            </div>
                        </div>
                        <div class="form-group required">
                            <label class="col-md-4 control-label">Lesson</label>
                            <div class="col-md-6">
                                <select class="form-control" name="lesson_id" id="lesson_id" class="multipleSelect" multiple="multiple">
                                    <option value="0">-Select Lesson-</option>

                                </select>
                            </div>
                        </div>
                             <div class="form-group">
                               <div class="col-md-6 col-md-offset-4">
                                    <button type="button" class="btn btn-info  pull-right btn-md"  id="applyFiltersBtn" onclick="update()"><i>Update</i></button>
                                       
                                </div>
                            </div>

                        </div>
                    </div>
                    </form>
                    <div id="wholescore">

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var loadurl = "{{ url('/report/assignment_wholeclass/') }}/" ;

        $( document ).ready(function() {
             $('#subject_id').multiselect();
          //  $('#subject_id').multiselect('refresh');
            $('#lesson_id').multiselect();
          //  $('#lesson_id').multiselect('refresh');
        });
        function update(){
            var csrf=$('Input#csrf_token').val();

            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+$('#institution_id').val()+'/'+$('#assignment_id').val()+'/'+$('#subject_id').val()+'/'+$('#lesson_id').val(),
                        type:'post',
                            success:function(response){
                                $('#wholescore').empty();
                                $('#wholescore').append(response);
                                $('#wholescore').prepend($('.average'));
                        }
                    }
            )
        }

        $('#institution_id').on('change',function(){

            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+ $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#assignment_id').empty();
                            $('#subject_id').empty();
                            $('#lesson_id').empty(0);
                            //$('#lesson_id').empty();
                            var opt = new Option('--Select Assignment--', '0');
                            $('#assignment_id').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#assignment_id').append(opt);
                            }
                        }
                    }
            )
        });
          $('#assignment_id').on('change',function(){
            var csrf=$('Input#csrf_token').val();

            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:'assignment_subject/'+ $('#assignment_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#subject_id').empty();
                            $('#lesson_id').empty();
                            // var opt = new Option('--Select Subjects--', '');
                            // $('#subject_id').append(opt);
                            $('#subject_id').multiselect('destroy');
                            $('#subject_id').empty();
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#subject_id').append(opt);
                            }
                            $('#subject_id').multiselect();
                            $('#subject_id').multiselect('refresh');
                        }
                    }
            )
        });
        $('#subject_id').on('change',function(){
            var csrf=$('Input#csrf_token').val();
            var a=$('#subject_id').val();

            if(a == null || a == "undefined"){
              
                var  length=1;
            }
            else{

                var length=$('#subject_id').val().length;
            }

            if(length>1){
                $('#lesson_id').attr('disabled',true);
            }
            else{
                $('#lesson_id').attr('disabled',false);
            }
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:'assignment_lesson/'+ $('#subject_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                           // $('#subject_id').empty();
                            // var opt = new Option('--Select Subjects--', '');
                            // $('#subject_id').append(opt);
                            $('#lesson_id').multiselect('destroy');
                            $('#lesson_id').empty();
                            $.each(response,function(id,name){
                                var opt = new Option(name, id);
                                $('#lesson_id').append(opt);
                            });

                            $('#lesson_id').multiselect();
                            $('#lesson_id').multiselect('refresh');
                            $('#subject_id').multiselect('refresh');
                        }
                    }
            )
        });


      /*  function inst_change(){

            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+ $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#assignment').empty();
                            var opt = new Option('--Select Assignment--', '');
                            $('#assignment').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#assignment').append(opt);
                            }
                        }
                    }
            )

        }*/
         
       /* function assignment_change(){

            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+ $('#assignment').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#subject_id').empty();
                            var opt = new Option('--Select Subject--', '');
                            $('#subject_id').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#subject_id').append(opt);
                            }
                        }
                    }
            )

        }*/

    </script> 
    <link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css">

    <script type="text/javascript" src="{{asset('js/bootstrap-multiselect.js')}}"></script>
    @endsection
