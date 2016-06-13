@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Scores By Assessment</div>
                    <div class="panel-body">


                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label" >Select institution:</label>
                                <div class="col-md-2">
                                    <select name="inst_id" class='form-control' id="institution_id" onchange="inst_change()">
                                        <option value="0" selected >-Select-</option>
                                        @foreach($inst_arr as $id=>$val)
                                            <option value="{{ $id }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Select Assessment:</label>
                                <div class="col-md-2">
                                    <select name="assessment_id" class='form-control' id="assessment" onchange="assessment_change()">
                                        <option value="0" selected >-Select-</option>
                                    </select>
                                </div>
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
        function assessment_change(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {
                        headers: {"X-CSRF-Token": csrf},
                        url: '/AssesmentTool/public/report/assessment_inst/' + $('#institution_id').val() + '/' + $('#assessment').val(),
                        type: 'post',
                        success: function (response) {
                            $('#report').empty();
                            $('#report').append(response);
                        }


                    }
            )
        }
        function inst_change(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {
                        headers: {"X-CSRF-Token": csrf},
                        url: '/AssesmentTool/public/report/assessment_inst/' + $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            var a = response.length;
                            $('#assessment').empty();
                            var opt = new Option('--Select Assessnment--', '');
                            $('#assessment').append(opt);
                            for (i = 0; i < a; i++) {
                                var opt = new Option(response[i].name, response[i].id);
                                $('#assessment').append(opt);
                            }
                        }
                    }
            )
        }
    </script>
@endsection