@if(Auth::user()->role_id==1)
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-sm-6 ">
            <h5><b>Class Average and Student Scores Report:</b></h5>
            <div id="first">
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
            renderAt: 'first',
            width: '670',
            height: '500',
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

                        @foreach($class_students as $user_id => $student)
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
    @elseif(Auth::user()->role_id==3 ||Auth::user()->role_id==4)

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5><b>Class Average and Student Scores Report:</b></h5>
            <div id="first">
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
            renderAt: 'first',
            width: '670',
            height: '500',
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

                        @foreach($class_students as $user_id => $student)
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
    @endif