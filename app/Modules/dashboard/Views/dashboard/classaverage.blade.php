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
<style type="text/css">
    .fancybox-inner {
    position: relative;
    width: 770px!important;
    overflow-x: hidden!important;
}
   .fancybox-skin {
    position: relative;
    background: #f9f9f9;
    color: #444;
    text-shadow: none;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    width: 100%!important;
}
</style> 
@if(Auth::user()->role_id==1)
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h5><b>Class Average and Student Scores Report:</b></h5>
            <div id="first">
            
            </div>
        </div>
         <div class="form-group  col-md-7">
 <label class="col-md-4 control-label"><b>Select Number of Students</b></label>
    <div class="col-md-6">
    <select class="col-md-6" id="change">

              <option value="10" id="var">10</option>
              <option value="20" id="var">20</option>
              <option value="30" id="var">30</option>
              <option value="40" id="var">40</option>
              <option value="50" id="var">50</option>
              <option value="all" id="var">All</option>
            </select>
        <div class="col-md-6 ">
            
            <div id="first">
           
            </div>
        </div>
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
            width: '720',
            height: '500',
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
    </script>
    
    @elseif(Auth::user()->role_id==3 ||Auth::user()->role_id==4)    
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5><b>Class Average and Student Scores Report:</b></h5>
            <div id="first">
           
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
    </script>
    @endif

  
 <?php
$path = url()."/dashboard/";
?>
    <script type="text/javascript">
         $(document).ready(function($) {

    $("#change").change(function() {
        
        var search_id = $(this).val();
        
        var ca='ca';
        
        $.ajax({
           
            url:'{{$path}}ClassLists/'+ca+'/'+search_id ,
            type:'get',
            success: function (response) {
               
              $("#first").append(response);  
                
            }
        });
    });
});
</script>