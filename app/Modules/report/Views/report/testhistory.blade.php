<div class="form-group col-md-12">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                            @if(getRole()=="administrator")
                            <a href="#" class="btn btn-primary" id="pdf" >Export PDF</a>
                            <a href="#" class="btn btn-primary" id="xls" >Export XLS</a>
                                @else
                                <a href="#" class="btn btn-primary pull-right" id="pdf" style="margin: 2px !important;" >Export PDF</a>
                                <a href="#" class="btn btn-primary pull-right" id="xls" style="margin: 2px !important;" >Export XLS</a>
                                @endif
                                </div>
                        </div>
<table class="table">
    <thead>
    <th>Assignment</th>
    <th>Assessment</th>
    <th>Total Students</th>
    <th>Students attended</th>
    <th>Average of Assignment for Completed users(%)</th>
    </thead>
    <tbody>
    @if(count($assignments)>0)
    @foreach($assignments as $assignment)
        <tr>
            <td>{{$assignment->assign_name}}</td>
            <td>{{$assignment->assess_name}}</td>
            <td>{{$All_users[($assignment->assign_id)]}}</td>
            <td>{{isset($complete_users[$assignment->assign_id])?$complete_users[$assignment->assign_id]:0}}</td>
            <td>{{round($mark[($assignment->assign_id)]).'%'}}</td>
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
                                'value' : '{{ceil(round($mark[($assignment->assign_id)],2))}}%'
                            },
                            @endforeach
                    // ]
                    // }
                ]
            }
        }).render();
    });
    
        $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            window.open("{{ url('report/testhistoryexportPDF/')}}/"+inst_id);
        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            window.open("{{ url('report/testhistoryexportXLS/')}}/"+inst_id);
        });
</script>


