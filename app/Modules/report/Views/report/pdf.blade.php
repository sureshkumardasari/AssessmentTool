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
<table class="table table-bordered table-hover table-striped" id="report" width="100%">
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
                <?php $all_users_count+=$student->score;?>
            </td>
            <td>
                {{$student->percentage}}
            </td>
        </tr>
    @endforeach

    </tbody>
</table><br>
@if(count($students)>0)
    <table class="table average" width="100%">
        <tr>
            <td>class average score:</td>
            <td> {{$all_users_count/(count($students))}}</td>
        </tr>
    </table><br>
@endif
</body>
</html>