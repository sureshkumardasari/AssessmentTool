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
<table border="0">
    <thead>
    <tr>
        <th colspan="6">Selected Institution</th>
        <th colspan="6">Selcted Assignment</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($inst as $val)
            <td colspan="6">{{$val->name}}</td>
        @endforeach
        @foreach($assi as $val)
            <td colspan="6">{{$val->name}}</td>
        @endforeach
    </tr>
    </tbody>
</table><br>
<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th colspan="4">Student Name</th>
        <th colspan="4">marks</th>
        <th colspan="4">Percentage(%)</th>
    </tr>
    </thead>
    <tbody>
    <?php $all_users_count=0; ?>
    @foreach($students as $student )
        <tr>
            <td colspan="4">
                {{$student->name}}
            </td>
            <td colspan="4">
                {{$student->score}}
                <?php $all_users_count+=$student->score;?>
            </td>
            <td colspan="4">
                {{$student->percentage}}
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
</table><br>
@if(count($students)>0)
    <table class="table average">
        <tr>
            <td colspan="6">class average score:</td>
            <td colspan="6"> {{$all_users_count/(count($students))}}</td>
        </tr>
    </table><br>
@endif
</body>
</html>