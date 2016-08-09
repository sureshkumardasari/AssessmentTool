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
        <div class="col-md-4">
            <h5><b>Class Average and Student Scores Report:<b></h5>
            <div id="chart-2">
            FusionCharts XT will load here!
            </div>
        </div>
        <div class="col-md-4">
            <h5><b>Test History Report:<b></h5>
            <div id="chart-1">    
            FusionCharts XT will load here!
            </div>
        </div>
        <div class="col-md-4">
            <h5><b>Whole Class Score Report:<b></h5>
            <div id="recentupdated">    
            FusionCharts XT will load here! 
            </div> 
        </div>
     </div>
   
    <div class="clearfix" style="padding:20px"></div> 
   
     <div> 
        @include('dashboard::dashboard.student_question_teacher')
    </div>
    <div class="panel panel-default col-md-4">
                <div class="panel-heading">List of Assignments
                </div>
                <div class="panel-body">
                   <th><!-- <b>List of Assignments</b> --></th>
                         <table>
                          <thead>
                            <tr>
                               <th>Name</th>
                                <th>StartDateTime</th>
                            </tr>
                          </thead>
                           <tbody>
                            @foreach( $assignments_user as $id => $row )
                                <tr>
                                    <td><a href="{{ url('/resources/assignmentview/'.$row->id) }}">{{  $row->name }}</a></td>
                                    <td>{{$row->startdatetime}}</td>
                                </tr>
                            @endforeach
                           </tbody>
                         </table> 
                         <center><button><a href="{{ url('/resources/assignment') }}">View More</a></button></center>
                </div>

     </div>
      @include('dashboard::dashboard.assignment_assessment')
<script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<script>

    FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'chart-2',
            width: '350',
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
            width: '350',
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
            width: '350',
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