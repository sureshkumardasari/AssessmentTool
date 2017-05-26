<?php

/**
 * Function to ellipse-ify text to a specific length
 *
 * @param string $text   The text to be ellipsified
 * @param int    $max    The maximum number of characters (to the word) that should be allowed
 * @param string $append The text to append to $text
 * @return string The shortened text
 * @author Brenley Dueck
 * @link   http://www.brenelz.com/blog/2008/12/14/creating-an-ellipsis-in-php/
 */
function ellipsis4($text, $max=100, $append='') {
    if (strlen($text) <= $max) return $text;

    $replacements = array(
        '|<br /><br />|' => ' ',
        '|&nbsp;|' => ' ',
        '|&rsquo;|' => '\'',
        '|&lsquo;|' => '\'',
        '|&ldquo;|' => '"',
        '|&rdquo;|' => '"',
    );

    $patterns = array_keys($replacements);
    $replacements = array_values($replacements);


    $text = preg_replace($patterns, $replacements, $text); // convert double newlines to spaces
    $text = strip_tags($text); // remove any html.  we *only* want text
    $out = substr($text, 0, $max);
    if (strpos($text, ' ') === false) return $out.$append;
    return preg_replace('/(\W)&(\W)/', '$1&amp;$2', (preg_replace('/\W+$/', ' ', preg_replace('/\W+$/', '', $out)))) . $append;
}
// $t = " The maximum testtttt number of characters (to the word) that should be allowed";
// $tt = ellipsis($t, 30);
// echo $tt;

?>

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
 <?php 
    $graph1 = 'ca';
    $graph2 = 'wc';
    $graph3 = 'th';

 ?>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-sm-6  ">
            <h5><!-- <a href="{{ URL('dashboard/ClassAverage/'.$graph1) }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"> <b>Class Average and Student Scores Report:<b> </a> --></h5>
            <div id="chart-2">
          
            </div>
            <center><a href="{{ URL('dashboard/ClassAverage/'.$graph1) }}" class="btn btn-info btn-sm center fancybox fancybox.ajax">View More</a></center>
        </div>
        <div class="col-lg-4 col-sm-6 ">
            <!-- <h5><a href="{{ URL('dashboard/ClassAverage/'.$graph3) }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><b>Test History Class Averages:</b></a></h5> -->
            <div id="chart-1">    
           
            </div>
             <center><a href="{{ URL('dashboard/ClassAverage/'.$graph3) }}" class="btn btn-info btn-sm center fancybox fancybox.ajax">View More</a></center>
        </div>
        <div class="col-lg-4 col-sm-6 ">
            <!-- <h5><a href="{{ URL('dashboard/ClassAverage/'.$graph2) }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><b>Whole Class Score Report:</b></a></h5> -->
            <div id="recentupdated">    
           
            </div> 
            <center><a href="{{ URL('dashboard/ClassAverage/'.$graph2) }}" class="btn btn-info btn-sm center fancybox fancybox.ajax">View More</a></center>
        </div>
     </div>
   
   
   
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
                    "theme": "fint",
                     "labelDisplay": "rotate",
                     "slantLabels": "1",
                },


                "data": [

                        @foreach($class_students as $user_id => $student)
                                {
                                    <?php
                                $tt = ellipsis4($student['name'], 10);
                                 
                                ?> 
                        'label':'{{$tt}}',
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
                    "theme": "fint",
                     "labelDisplay": "rotate",
                     "slantLabels": "1",
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
                                    <?php
                                $tt = ellipsis4($assignment['assign_name'], 10);
                                 
                                ?> 
                                    'label' : '{{$tt}}',
                                'value' : '{{round($mark[($assignment->assign_id)],2)}}%'

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
                    "theme": "fint",
                    "labelDisplay": "rotate",
                     "slantLabels": "1",
                },
               
                
                "dataset": [
                    {
                        "data": [
                        @foreach($students as $user_id => $assignment)
                        {
                            <?php
                                $tt = ellipsis4($assignment['sname'], 10);
                                 
                                ?> 
                        'label' : '{{ $tt}}',
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

@endsection