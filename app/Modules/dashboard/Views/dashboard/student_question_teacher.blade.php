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
function ellipsis($text, $max=100, $append='&hellip;') {
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
    return preg_replace('/(\W)&(\W)/', '$1&amp;$2', (preg_replace('/\W+$/', ' ', preg_replace('/\w+$/', '', $out)))) . $append;
}
// $t = " The maximum testtttt number of characters (to the word) that should be allowed";
// $tt = ellipsis($t, 30);
// echo $tt;

?>
<div class="container">
        <div class="row" style="margin:10px -15px -33px -33px;">
            <div class="col-md-4">
                <div class="panel panel-default">
                 <div class="panel-heading">List Of Teachers
                </div>
                <div class="panel-body">

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Teacher Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $tlist as $id => $value )
                            <tr>
                                <td><a href="{{ url('/user/edit/'.$value['uid'].'/'.$tech) }}">{{ $value['uname'] }}</a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                      <center><a class="btn btn-info" role="button" href="{{  url('user/users_list/teacher')  }}">View More</a></center> 
                  
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Students
                </div>
                <div class="panel-body">

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Students Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $slist as $id => $value )
                            <tr>
                                <td><a href="{{ url('/user/edit/'.$value['id'].'/'.$stud) }}">{{ $value['name'] }}</a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table> 
                    </div>
                </div>
                <center><a class="btn btn-info" role="button" href="{{ url('user/users_list/student') }}">View More</a></center>
                         
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">               
                <div class="panel-heading">List Of Questions
                </div> 
                <div class="panel-body">

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Question Discription</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $list_details as $id => $value )
                            <tr>
                                <td><a href="{{ url('/resources/questionview/'.$value['qid']) }}" title="{{ strip_tags(htmlspecialchars_decode($value['question_qst_text'])) }}" data-toggle="tooltip">
                                <?php
                                $tt = ellipsis($value['question_qst_text'], 30);
                                  echo $tt;
                                ?> 
                                </a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                 <center><a class="btn btn-info" role="button" href="{{ url('/resources/question') }}">View More</a></center>
                    
            </div>
            <div class="row" style="margin-bottom:20px;"> </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Lessons
                </div>
                <div class="panel-body">

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Lesson Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $list_lession as $id => $value )
                            <tr>
                                <td><a href="{{ url('/resources/lessonedit/'.$id) }}" title="{{ $value }}" data-toggle="tooltip">
                                 <?php
                                $tt = ellipsis($value, 30);
                                  echo $tt;
                                ?> 
                                </a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                    <center><a class="btn btn-info" role="button" href="{{  url('/resources/lesson')  }}">View More</a></center>
            </div>
            
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Assessments
                </div>
                <div class="panel-body">

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Name</th>
                            </tr>
                        </thead>
                       <tbody id="question_list_filer">
                        @foreach( $assessment as $name )
                            <tr>
                                <td><a href="{{ url('/resources/assessmentview/'.$name['id']) }}" title="{{ $name['name'] }}" data-toggle="tooltip">
                                 <?php
                                $tt = ellipsis($name['name'], 30);
                                  echo $tt;
                                ?> 
                                </a></td>
                            </tr>
                        @endforeach
                     </tbody>
                    </table>
                </div>
                </div>
                    <center><a class="btn btn-info" role="button" href="{{ url('/resources/assessment') }}">View More</a></center>
            </div>
            
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Assignments
                </div>
                <div class="panel-body">

                    <table id="text">
                        <thead>
                             <tr>
                               <th style="width: 45%;">Name </th>
                                <th style="width: 30%;">Start DateTime</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach( $assignments_user as $id => $row )
                                <tr>
                                    <td><a href="{{ url('/resources/assignmentview/'.$row->id) }}" title="{{ $row->name }}" data-toggle="tooltip">
                                    <?php
                                $tt = ellipsis($row->name, 26);
                                  echo $tt;
                                ?> 
                                </a></td>
                                    <td><div class="time">{{$row->startdatetime}}</div></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
              <center><a class="btn btn-info" role="button" href="{{ url('/resources/assignment') }}">View More</a></center>
            </div>
        </div>

</div>
<style>
    #text,th
    {
        /*width: 315px;*/
        border: 0px solid #000000;
        word-break: break-word;
    }
    .time{
        font-weight: normal;      
  }
</style>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>