<html>
<h3 align="center">Least Score Report</h3>
<head>
    <style>
        table, th, td {
            border: 0px solid #04060e;
            border-collapse: collapse;
        }
    </style>
</head>
<body>

<table class="table" width="100%">
    <thead>
    <tr>
    <th>Assignment Name</th>
    <th>Student Name</th>
    <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data)>0)
        @foreach($report_data as $assignment)
            <tr>
             <td>{{$assignment->assignment_name}}</td>
                <td>{{$assignment->user_name}}</td>
                <td>{{$assignment->rawscore}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" align="center">No data Available</td>
        </tr>
    @endif

    </tbody>
</table><br>
<table class="table" width="100%">
    <thead>
    <tr>
    <th>Assignment Name</th>
    <th>Student Name</th>
    <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data1)>0)
        @foreach($report_data1 as $assignment)
            <tr>
            <td>{{$assignment->assignment_name}}</td>
                <td>{{$assignment->user_name}}</td>
                <td>{{$assignment->rawscore}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" align="center">No data Available</td>
        </tr>
    @endif

    </tbody>
</table><br>
<table class="table" width="100%">
    <thead>
    <tr>
    <th>Assignment Name</th>
    <th>Student Name</th>
    <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data2)>0)
        @foreach($report_data2 as $assignment)
            <tr>
             <td>{{$assignment->assignment_name}}</td>
                <td>{{$assignment->user_name}}</td>
                <td>{{$assignment->rawscore}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" align="center">No data Available</td>
        </tr>
    @endif
    </tbody>
</table><br>
</body>
</html>