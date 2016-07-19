<?php
$arr=[1=>'A',2=>'B',3=>'C',4=>'D',5=>'E'];
?>

<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Questions</th>
        <th>Correct Answer</th>
        <th>Your Answer</th>
     </tr>
    </thead>
    <tbody>
    @foreach($assignments as $assignment)
    <tr> 
        <td>
            {{$assignment->question_title}}
        </td>
        <td> 
            {{$arr[$assignment->correct_answer]}}
        </td>
        <td> 
        @if($assignment->is_correct=="No")
           <p style="color:red;font:bold;">{{$assignment->your_answer}}&nbsp;&nbsp;<span class="glyphicon glyphicon-remove"></span></p> 
        @else
            <p style="color:green;font:bold;"><b>{{$assignment->your_answer}} &nbsp;&nbsp;<span class="glyphicon glyphicon-ok"></span></p> 
        @endif
        </td>
    </tr>
        @endforeach
    </tbody>
</table>