

<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Student Name</th>
        <th>marks</th>
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
            {{$student->percentage}}
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
<table class="table average">
    <tr>
        <td>class average score:</td>
        <td> {{$all_users_count/(count($students))}}</td>
    </tr>
</table>
@endif
<div id="chart-1">FusionCharts XT will load here!</div>
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


