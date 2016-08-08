@extends('default')
@section('content')
    <script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/fusioncharts-jquery-plugin-master/src/fusioncharts-jquery-plugin.js')}}"></script>
    <style>
        .flash {
            position:absolute;
            z-index:1;
            left:600px;
            width: 300px;
            border: 5px;
            background-color:grey;
            padding: 10px;
            margin: 25px;
        }
    </style>
    @if(Session::has('flash_message'))

        <div class="flash">
            <p>
                <b>
                    {{ Session::pull('flash_message') }}
                </b>
            </p>
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <div class="form-group">
                            <div class="">
                                <div>
                                    <table id="report" class="table datatableclass">
                                        <caption><center><b>Completed Assignments</b></center></caption>
                                        <thead>
                                        <tr>
                                            <th>sl.no</th>
                                            <th>assignment</th>
                                            <th>raw score</th>
                                            <th>percentage</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1; ?>
                                        @foreach($completed_assignments as $completed)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$completed->name}}</td>
                                                <td style="color:blue">{{$completed->rawscore}}</td>
                                                <td style="color:blue">{{$completed->percentage}}%</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        <br>

                        <div id="chart-1" class="col-md-10 col-md-offset-2">
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        //$('#report').dataTable();
        FusionCharts.ready(function(){
            var salesChart = new FusionCharts({
                type: 'scrollline2d',
                dataFormat: 'json',
                renderAt: 'chart-1',
                width: '550',
                height: '350',
                dataSource: {
                    "chart": {
                        "caption": "Your Performance Graph",
                        "subCaption": "",
                        "xAxisName": "Assignment",
                        "yAxisName": "Precentage Gained",
                        "showValues": "0",
                        "numberPrefix": "",
                        "numberSuffix": "%",
                        "showBorder": "0",
                        "showShadow": "0",
                        "bgColor": "#ffffff",
                        "paletteColors": "#008ee4",
                        "showCanvasBorder": "0",
                        "showAxisLines": "0",
                        "showAlternateHGridColor": "0",
                        "divlineAlpha": "100",
                        "divlineThickness": "1",
                        "divLineIsDashed": "1",
                        "divLineDashLen": "1",
                        "divLineGapLen": "1",
                        "lineThickness": "3",
                        "flatScrollBars": "1",
                        "scrollheight": "10",
                        "numVisiblePlot": "12",
                        "showHoverEffect":"1"
                    },
                    "categories": [
                        {
                            "category": [
                                    @foreach($percentage as $user_id => $percent)
                                {
                                    'label':'{{$user_id}}'

                                },
                                @endforeach
                            ]
                        }
                    ],
                    "dataset": [
                        {
                            "data": [
                                    @foreach($percentage as $user_id => $percent)
                                {
                                    'value' : '{{$percent}}'
                                },
                                @endforeach
                            ]
                        }
                    ]
                }
            }).render();
        });
    </script>

@endsection
