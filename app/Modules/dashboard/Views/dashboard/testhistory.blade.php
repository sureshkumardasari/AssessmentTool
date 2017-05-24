@if(Auth::user()->role_id==1)
<div class="container">
    <div class="row">
       <div class="col-lg-4 col-sm-6 ">
            <h5><b>Test History Class Averages:<b></h5>
            <div id="second">    
            FusionCharts XT will load here!
            </div>
        </div>
    </div>
 </div>
 <script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<script>

    FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'second',
            width: '670',
            height: '500',
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
                                'value' : '{{round($mark[($assignment->assign_id)],2)}}%'

                            },
                            @endforeach
                    // ]
                    // }
                ]
            }
        }).render();
    });
    </script>
    @elseif(Auth::user()->role_id==3 ||Auth::user()->role_id==4)

<div class="col-md-4">
            <h5><b>Test History Class Averages:<b></h5>
            <div id="second">    
            FusionCharts XT will load here!
            </div>
        </div>
 <script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<script>

    FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'second',
            width: '670',
            height: '500',
            dataSource: {
                "chart": {
                    "caption": "Test History Report",
                    "subCaption": "",
                    "xAxisName": "Assignment Name",
                    "yAxisName": "Total Students",
                    "numberPrefix": "",
                    "theme": "fint"
                },

                "data": [
                        @foreach($assignments as $user_id => $assignment)
                        {
                        'label' : '{{$assignment->assign_name}}',
                        'value' : '{{round($mark[($assignment->assign_id)],2)}}%'
                    },
                    @endforeach
            // ]
                    // }
                ]
            }
        }).render();
    });
    </script>
    @endif