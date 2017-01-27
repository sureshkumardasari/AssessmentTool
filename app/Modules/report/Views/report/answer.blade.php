@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Questions & Answers</div>
                    <div class="panel-body">

                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">

                            <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, 'col-md-12','All'); ?>
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-2 control-label">Select institution:</label>--}}
                                {{--<div class="col-md-2">--}}
                                    {{--<select name="inst_id" class='form-control' id="institution_id" >--}}
                                        {{--<option value="0" selected >-Select-</option>--}}
                                        {{--@foreach($inst_arr as $id=>$val)--}}
                                            {{--<option value="{{ $id }}">{{ $val }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        <div class="form-group col-md-12">
                            <label class="col-md-4 control-label">Select Assignment:</label>
                            <div class="col-md-6">
                                <select name="assessment_id" class='form-control' id="assignment" >
                                    <option value="0" selected >-Select-</option>
                                    @if(getRole()!="administrator")
                                        @foreach($assignment as $ass)
                                            <option value="{{$ass->id}}">{{$ass->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-4 control-label">Select Subject:</label>
                            <div class="col-md-6">
                                <select name="subject_id" class='form-control' id="subject" >
                                    <option value="0" selected >-Select-</option>
                                    @if(getRole()!="administrator")

                                    @endif
                                </select>
                            </div>
                        </div>

                            <div class="form-group col-md-12">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary" id="applyFiltersBtn" onclick="inst_change()"> Go</button>
                                </div>
                            </div>

                    
                    
                    <div class="form-group col-md-12">
                                <label class="col-md-6 control-label"></label>
                                <div class="col-md-6">
                        <a href="#" class="btn btn-primary" id="pdf">Export PDF</a>
                        <a href="#" class="btn btn-primary" id="xls">Export XLS</a>
                    </div></div>
                    <div id="report">

                    </div>

                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var loadurl = "{{ url('/report/assignment_qstn/') }}/" ;
        function inst_change(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {
                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+$('#institution_id').val()+'/'+$('#assignment').val()+'/'+$('#subject').val(),
                        type:'post',
                        success:function(response){
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
                        url:loadurl+ $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#assignment').empty();
                            $('#subject').empty();
                            var opt = new Option('--Select Assignment--', 0);
                            $('#assignment').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#assignment').append(opt);
                            }
                        }
                    }
            )
        });
        $('#assignment').on('change',function(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:'assignment_subjects/'+ $('#institution_id').val()+'-'+$('#assignment').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#subject').empty();
                            var opt = new Option('--Select Subject--', 0);
                            $('#subject').append(opt);
                            for (i = 0; i < a; i++) {

                                var opt = new Option(response[i].name, response[i].id);
                                $('#subject').append(opt);
                            }

                        }
                    }
            )
        });
        $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assignment').val();
            var sub_id=$('#subject').val();
            window.open("{{ url('report/QuestionsexportPDF/')}}/"+inst_id+"/"+assign_id+"/"+sub_id);

        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assignment').val();
            var sub_id=$('#subject').val();
            window.open("{{ url('report/QuestionsexportXLS/')}}/"+inst_id+"/"+assign_id+"/"+sub_id);

        });
    </script>
@endsection