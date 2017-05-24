@if(Auth::user()->role_id==1)
<div class="container">
    <div class="row">
       <div class="col-lg-4 col-sm-6 ">
            <h5><b>Whole Class Score Report:</b></h5>
            <div id="third">    
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
            renderAt: 'third',
            width: '670',
            height: '500',
            dataSource: {
                "chart": {
                    "caption": "Most Recent Whole class score report",
                    "subCaption": "",
                    "xAxisName": "Subject Name",
                    "yAxisName": "Average Marks",
                    "numberPrefix": "",
                    "theme": "fint"
                },
               
                
                "dataset": [
                    {
                        "data": [
                        @foreach($students as $user_id => $assignment)
                        {
                        'label' : '{{$assignment->sname}}',
                        'value' : '{{$assignment->score}}'

                         },
                    @endforeach
                          //      {
                               
                          //       'value' : '{{$score}}'
                          // }
                    ]
                    }
                ]
            }
        }).render();
    });
    </script>
    @elseif(Auth::user()->role_id==3 ||Auth::user()->role_id==4)

<div class="col-md-4">
            <h5><b>Whole Class Score Report:</b></h5>
            <div id="third">    
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
            renderAt: 'third',
            width: '670',
            height: '500',
            dataSource: {
                "chart": {
                    "caption": "Most Recent Whole class score report",
                    "subCaption": "",
                    "xAxisName": "Subject Name",
                    "yAxisName": "Average Marks",
                    "numberPrefix": "",
                    "theme": "fint"
                },
               
                "dataset": [
                    {
                        "data": [
                        @foreach($assignments as $user_id => $assignment)
                        {
                        'label' : '{{$assignment->sname}}',
                        'value' : '{{$score}}'

                         },
                    @endforeach
                          //      {
                               
                          //       'value' : '{{$score}}'
                          // }
                    ]
                    }
                ]
            }
        }).render();
    });
    </script>
    @endif