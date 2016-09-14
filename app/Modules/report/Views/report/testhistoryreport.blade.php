@extends('default')
@section('content')
    <style>
        table{

        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Test History Report</div>
                    <div class="panel-body">

                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">

                        <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, '','All'); ?>
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
                        <div class="form-group">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" id="applyFiltersBtn" onclick="inst_change()"> Go</button>
                            </div>
                        </div>
                        <div>
                            <a href="#" class="btn btn-primary" id="pdf">Export pdf</a>
                            <a href="#" class="btn btn-primary" id="xls">Export xls</a>

                        </div>
                    </div>
                    <div id="report">

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $("#institution_id option[value=0]").remove();
        var op=new Option('select',0)
        $("#institution_id").append(op);
        $("#institution_id").val(0);
        function inst_change(){
            var loadurl = "{{ url('/report/test_history/') }}/" ;
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {
                        headers: {"X-CSRF-Token": csrf},
                        url: loadurl+ $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            $('#report').empty();
                            $('#report').append(response);
                        }


                    }
            )
        }
        $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            window.open("{{ url('report/testhistoryexportPDF/')}}/"+inst_id);
        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            window.open("{{ url('report/testhistoryexportXLS/')}}/"+inst_id);
        });
    </script>
@endsection