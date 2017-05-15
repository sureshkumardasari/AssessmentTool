<div class="form-group col-md-12 gobutton">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                            <a href="#" class="btn btn-primary" id="pdf" onclick="reports()">Export PDF</a>
                            <a href="#" class="btn btn-primary" id="xls" onclick="reports()">Export XLS</a>
                        </div></div>

<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Student Name</th>
        <th>Marks</th>
        <th>Percentage(%)</th>
    </tr>
    </thead>
    <tbody>
<?php $all_users_count=0; ?>
        @foreach($students as $student )
    <tr>
        <td>
        {{$student->name}}
        </td>
        <td>
        {{$student->score}}
            <?php $all_users_count+=$student->score;?>
        </td>
        <td>
            {{round($student->percentage).'%'}}
        </td>
       {{-- --}}{{--<td>--}}{{--
        --}}{{--{{$student->answers_count}}--}}{{--
        --}}{{--</td>--}}{{--
        --}}{{--<td>--}}{{--
        --}}{{--{{($student->answers_count/$student->total_count)*100}}%--}}{{--
        --}}{{--</td>--}}
    </tr>
        @endforeach

    </tbody>
</table>
@if(count($students)>0)
<table class="table average" style="border-bottom: 1px solid lightgray;">
    <tr>
        <td><strong>class average score:</strong></td>
        <td style="width: 50%;"> {{round($all_users_count/(count($students))).'%'}}</td>
    </tr>
</table>
@endif
<div id="chart-1">FusionCharts XT will load here!</div>

<script type="text/javascript">
    function reports(){
            
                $.ajax(
                        {

                            headers: {"X-CSRF-Token": csrf},
                            url: loadurl + $('#institution_id').val() + '/' + $('#assignment_id').val(),
                            type: 'post',
                            success: function (response) {
                                $('#report').empty();
                                $('#report').append(response);
                                //$('#report').before($('.average'));
                                $('#report').prepend($('.gobutton'));
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
<script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<script>

    FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'chart-1',
            width: '550',
            height: '350',
            dataSource: {
                "chart": {
                    "caption": "Student Marks",
                    "subCaption": "",
                    "xAxisName": "Student Name",
                    "yAxisName": "Marks",
                    "numberPrefix": "",
                    "theme": "fint"
                },


                        "data": [
                                @foreach($students as $user_id => $student)
                                {
                                'label':'{{$student->name}}',
                                'value' : '{{$student->score}}'
                            },
                            @endforeach

                ]
            }
        }).render();
    });
    
</script>


