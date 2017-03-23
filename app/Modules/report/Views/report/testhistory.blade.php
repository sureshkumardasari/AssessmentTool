<table class="table">
    <thead>
    <th>Assignment</th>
    <th>Assessment</th>
    <th>Total Students</th>
    <th>Students attended</th>
    <th>Average of Assignment for Completed users</th>
    </thead>
    <tbody>
    @if(count($assignments)>0)
    @foreach($assignments as $assignment)
        <tr>
            <td>{{$assignment->assign_name}}</td>
            <td>{{$assignment->assess_name}}</td>
            <td>{{$All_users[$assignment->assign_id]}}</td>
            <td>{{isset($complete_users[$assignment->assign_id])?$complete_users[$assignment->assign_id]:0}}</td>
            <td>{{$marks[$assignment->assign_id]}}%</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="5" align="center">No data Available</td>
        </tr>
        @endif

    </tbody>
</table>



<div id="chart-1" style="padding-left: 10px;">FusionCharts XT will load here!</div>
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
                    "caption": "Test History Report",
                    "subCaption": "",
                    "xAxisName": "Assignment Name",
                    "yAxisName": "Total Students",
                    "numberPrefix": "",
                    "theme": "fint"
                },
                // "categories": [
                //     {
                //         "category": [
                //          @foreach($assignments as $user_id => $assignment)
                //          {
                //             'label' : '{{$assignment->assign_name}}'
                //         },
                //         @endforeach
                //     ]
                //     }
                // ],
                // "dataset": [
                //     {
                        "data": [
                                @foreach($assignments as $user_id => $assignment)
                                {
                                    'label' : '{{$assignment->assign_name}}',
                                'value' : '{{$marks[$assignment->assign_id]}}%'
                            },
                            @endforeach
                    // ]
                    // }
                ]
            }
        }).render();
    });
</script>


