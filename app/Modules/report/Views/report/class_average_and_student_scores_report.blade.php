@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Class Average and Student Scores Report</div>
                    <div class="panel-body">

                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                      <!--   <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, 'col-md-12','All'); ?> -->
                      
                           <div class="form-group required">
                                <label class="col-md-2 control-label col-md-offset-2" > Institution:</label>
                               <div class="col-md-6">
                                    <select name="inst_id" class='form-control' id="institution_id">
                                        <option value="0" selected >-Select-</option>
                                     @foreach($inst_arr as $id=>$val)
                                            <option value="{{ $id }}">{{ $val }}</option>
                                        @endforeach
                                   </select>
                                </div>
                            </div>
                            <br><br>
                            <div class="form-group required">
                                <label class="col-md-2 control-label col-md-offset-2"> Assignment:</label>
                                <div class="col-md-6">
                                    <select name="assignment_id" class='form-control' id="assignment_id" >
                                        <option value="0" selected >--Select--</option>
                                        @if(getRole()!="administrator")
                                           <!--  @foreach($assignments as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
                                            @endforeach -->
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                 <label class="col-md-4 control-label"></label>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary" id="applyFiltersBtn" onclick="report()"> Go</button>
                                       
                                </div>
                            </div>

                    <div class="form-group col-md-12">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                        <a href="#" class="btn btn-primary" id="pdf" onclick="reports()">Export PDF</a>
                        <a href="#" class="btn btn-primary" id="xls" onclick="reports()">Export XLS</a>
                    </div></div>
                    <div id="report">

                    </div>

                    
</div>
            </div>
            </div>
        </div>
    </div>
    <script>
        var loadurl = "{{ url('/report/assessment_inst/') }}/" ;
        function report(){
            if($('#institution_id').val()==0 || $('#assignment_id').val()==0){
                alert("please select all the fields");
            }
            else {
                var csrf = $('Input#csrf_token').val();

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
        }
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
                          return false;
                           
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
                          return false;

                           
            }
            else
            {
            window.open("{{ url('report/exportXLS/')}}/"+inst_id+"/"+assign_id);
        }
        });
    </script>

   
@endsection