<html>
<h3>Class Average and Student Scores Report</h3>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
{{--<input type="text">Selected Institution: $inst</input>--}}
<table border="0">
    <thead>
    <tr>
        <th>Selected Institution</th>
        <th>Selcted Assignment</th>
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
</table>
<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Student Name</th>
        <th>marks</th>
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
                <?php $all_users_count+=$student->score;?>
            </td>
            <td>
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
</table>
@if(count($students)>0)
    <table class="table average">
        <tr>
            <td>class average score:</td>
            <td> {{$all_users_count/(count($students))}}</td>
        </tr>
    </table>
@endif
</body>
</html>