@extends('default')
@section('content')
 <style>
    .panel-body{
        min-height: 200px;
    }
    button{
          margin: 13px 12px 12px 10px;

    }
 </style>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-sm-7  ">
            <h5><b>Class Average and Student Scores Report:<b></h5>
            <div id="chart-2">
            FusionCharts XT will load here!
            </div>
        </div>
        <div class="col-lg-4 col-sm-7 ">
            <h5><b>Test History Class Averages:<b></h5>
            <div id="chart-1">    
            FusionCharts XT will load here!
            </div>
        </div>
        <div class="col-lg-4 col-sm-7 ">
            <h5><b>Whole Class Score Report:<b></h5>
            <div id="recentupdated">    
            FusionCharts XT will load here! 
            </div> 
        </div>
     </div>
   
    <div class="clearfix" style="padding:20px"></div> 
   
     <div style="margin-bottom:20px;"> 
        @include('dashboard::dashboard.student_question_teacher')
    </div>
  
 <script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<script>

    FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'chart-2',
            width: '370',
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
      FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'chart-1',
            width: '370',
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
     FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'recentupdated',
            width: '370',
            height: '350',
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
                         },
                    @endforeach
                               {
                               
                                'value' : '{{$score}}'
                          }
                    ]
                    }
                ]
            }
        }).render();
    });
</script>

@endsection