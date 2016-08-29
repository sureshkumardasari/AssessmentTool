<html>
<?php
$arr=[1=>'A',2=>'B',3=>'C',4=>'D',5=>'E'];
?>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>


<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    @if($assignments)
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
                    <p style="color:green;font:bold;"><b>{{$assignment->your_answer}} &nbsp;&nbsp;<span class="glyphicon glyphicon-ok"></span></b></p>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    @else
        <tbody>
        <tr>
            <td style="padding-left:500px;">
                No Data To Display
            </td>
        </tr>
        </tbody>
    @endif
</table>
</html>