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
                    <div class="panel-heading" style="text-align:center; ">Test History Class Averages</div>
                    <div class="panel-body">

                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">

                        <!-- <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, 'col-md-12','All'); ?> -->
                        <div class="form-group required">
                        <label class="col-md-2 control-label" id="mandatory" style="margin:0px 0 0 225px">Institution:</label>
                        <div class="col-md-2">
                        <select name="inst_id" class='form-control' id="institution_id" style="margin-left:-50px;">
                       <option value="0" selected>--Select--</option>
                        @foreach($inst_arr as $id=>$val)


                        <option value="{{ $id }}">{{ $val }}</option>

                        @endforeach
                        </select>
                        </div>
                        </div>
                        <div class="form-group col-md-12">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                @if(getRole()=="administrator")
                                <button type="button" class="btn btn-primary" id="applyFiltersBtn" onclick="inst_change()"> Go</button>
                                    @else
                                    <button type="button" class="btn btn-primary pull-left" id="applyFiltersBtn" onclick="inst_change()"> Generate Report</button>
                                @endif
                            </div>

                        </div>
                        <div class="form-group col-md-12">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                            @if(getRole()=="administrator")
                            <a href="#" class="btn btn-primary" id="pdf" >Export PDF</a>
                            <a href="#" class="btn btn-primary" id="xls" >Export XLS</a>
                                @else
                                <a href="#" class="btn btn-primary pull-right" id="pdf" style="margin: 2px !important;" onclick="reports()">Export PDF</a>
                                <a href="#" class="btn btn-primary pull-right" id="xls" style="margin: 2px !important;" onclick="reports()">Export XLS</a>
                                @endif
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
        //$("#institution_id option[value=0]").remove();
        var op=new Option('select',0)
        //$("#institution_id").append(op);
       // $("#institution_id").val(0);
        function inst_change(){
            if($('#institution_id').val()==0 ){
                alert("please select the institution field");
            }
            else {
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
    }
        $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            window.open("{{ url('report/testhistoryexportPDF/')}}/"+inst_id);
        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            window.open("{{ url('report/testhistoryexportXLS/')}}/"+inst_id);
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

        $('#institution_id').on('change',function(){
            var csrf=$('Input#csrf_token').val();
            $.ajax(
                    {

                        headers: {"X-CSRF-Token": csrf},
                        url:loadurl+ $('#institution_id').val(),
                        type: 'post',
                        success: function (response) {
                            $('#report').empty();
                            var a = response.length;
                            $('#assignment_id').empty();
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