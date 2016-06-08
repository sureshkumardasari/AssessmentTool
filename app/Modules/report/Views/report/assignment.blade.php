@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Scores By Assignment</div>
                    <div class="panel-body">
                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Select institution:</label>
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
                                <label class="col-md-2 control-label">Select Assignment:</label>
                                <div class="col-md-2">
                                    <select name="assessment_id" class='form-control' id="assignment"  onchange="assignment_change()">
                                        <option value="0" selected >-Select-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="report" >

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>

        function assignment_change(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:'/AssesmentTool/public/report/assignment_inst/'+$('#institution_id').val()+'/'+$('#assignment').val(),
                        type:'post',
                            success:function(response){
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
                        url: '/AssesmentTool/public/report/assignment_inst/' + $('#institution_id').val(),
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

        }

    </script>
    {{--<script>
        @if(isset($from) && $from == 'search')
        $(document).ready(function() {
            $('.datatableclass').DataTable({
                language: {
                    paginate: {
                        previous: '‹',
                        next:     '›'
                    },
                    aria: {
                        paginate: {
                            previous: 'Previous',
                            next:     'Next'
                        }
                    }
                }
            });
        });
        @endif
    </script>--}}
    @endsection
