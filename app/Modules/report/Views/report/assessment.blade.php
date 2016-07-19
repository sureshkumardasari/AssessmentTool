@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Scores By Assessment</div>
                    <div class="panel-body">


                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, '','All'); ?>
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-2 control-label" >Select institution:</label>--}}
                                {{--<div class="col-md-2">--}}
                                    {{--<select name="inst_id" class='form-control' id="institution_id" onchange="inst_change()">--}}
                                        {{--<option value="0" selected >-Select-</option>--}}
                                        {{--@foreach($inst_arr as $id=>$val)--}}
                                            {{--<option value="{{ $id }}">{{ $val }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group">
                                <label class="col-md-2 control-label">Select Assessment:</label>
                                <div class="col-md-2">
                                    <select name="assessment_id" class='form-control' id="assessment" >
                                        <option value="0" selected >-Select-</option>
                                        @if(getRole()!="administrator")
                                            @foreach($assessment as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" id="applyFiltersBtn" onclick="assessment_change()"> Go</button>
                                       
                                </div>
                            </div>

                    </div>
                    <div id="report">

                    </div>

                    <div>
                        <a href="#" class="btn btn-primary" id="pdf">Export Pdf</a>
                    </div>

            </div>
            </div>
        </div>
    </div>
    <script>
        var loadurl = "{{ url('/report/assessment_inst/') }}/" ;
        function assessment_change(){
            var csrf=$('Input#csrf_token').val();

            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+$('#institution_id').val()+'/'+$('#assessment').val(),
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
                            $('#assessment').empty();
                            var opt = new Option('--Select Assessment--', '');
                            $('#assessment').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#assessment').append(opt);
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
                            $('#assessment').empty();
                            var opt = new Option('--Select Assessment--', '');
                            $('#assessment').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#assessment').append(opt);
                            }
                        }
                    }
            )

        }

        $('#pdf').on('click',function(){
            //alert();
            var inst_id=$('#institution_id').val();
            var assmt_id=$('#assessment').val();
            //alert(inst_id+","+assmt_id);
            //location.reload("{{ url('exportPDF/')}}"+inst_id+"/"+assmt_id);
          window.open("{{ url('report/exportPDF/')}}/"+inst_id+"/"+assmt_id);

        })


    </script>


@endsection