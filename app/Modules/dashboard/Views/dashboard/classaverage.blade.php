@if(Auth::user()->role_id==1)
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