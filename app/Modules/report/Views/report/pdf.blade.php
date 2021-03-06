<html>
<h3 align="center">Class Average and Student Scores Report</h3>
<head>
    <style>
        table, th, td {
            border: 0px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
{{--<input type="text">Selected Institution: $inst</input>--}}
<table border="0" width="100%">
    <thead>
    <tr>
        <th>Selected Institution</th>
        <th>Selected Assignment</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($inst as $val)
            <td>{{$val->name}}</td>
        @endforeach
        @foreach($assi as $val)
            <td>{{$val->name}}</td>
        @endforeach
    </tr>
    </tbody>
</table><br>
<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Student Name</th>
        <th>Marks</th>
        <th>Percentage(%)</th>
    </tr>
    </thead>
    <tbody>
<?php $all_users_count=0; ?>
        @foreach($students as $student )
    <tr>
        <td>
        {{$student->name}}
        </td>
        <td>
        {{$student->score}}
            
        </td>
        <td>
            {{round($student->percentage).'%'}}
            <?php $all_users_count+=$student->percentage;?>
        </td>
       {{-- --}}{{--<td>--}}{{--
        --}}{{--{{$student->answers_count}}--}}{{--
        --}}{{--</td>--}}{{--
        --}}{{--<td>--}}{{--
        --}}{{--{{($student->answers_count/$student->total_count)*100}}%--}}{{--
        --}}{{--</td>--}}
    </tr>
        @endforeach

    </tbody>
</table>
@if(count($students)>0)
<table class="table average" style="border-bottom: 1px solid lightgray;">
    <tr>
        <td><strong>class average score:</strong></td>
        <td style="width: 50%;"> {{round($all_users_count/(count($students))).'%'}}</td>
    </tr>
</table>
@endif
</body>
</html>