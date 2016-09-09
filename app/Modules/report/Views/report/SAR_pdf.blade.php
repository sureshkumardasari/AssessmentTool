<html>
<h3 align="center">Student Answer Report</h3>
<?php
$arr=[1=>'A',2=>'B',3=>'C',4=>'D',5=>'E'];
?>
<head>
    <style>
        table, th, td {
            border: 0px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<table border="0">
    <thead>
    <tr>
        <th>Selected Institution</th>
        <th>Selected Assignment</th>
        <th>Selected Student</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($inst as $val)
            <td>{{$val->name}}</td>
        @endforeach
        @foreach($assign as $val)
            <td>{{$val->name}}</td>
        @endforeach
        @foreach($user as $val)
            <td>{{$val->name}}</td>
        @endforeach
    </tr>
    </tbody>
</table>
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