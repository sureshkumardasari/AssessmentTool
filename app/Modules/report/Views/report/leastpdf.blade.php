<html>
<h3>Least Score Report</h3>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<table class="table">
    <thead>
    <tr>
    <th>Student Name</th>
    <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data)>0)
        @foreach($report_data as $assignment)
            <tr>
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
</table>
<table class="table">
    <thead>
    <tr>
    <th>Student Name</th>
    <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data1)>0)
        @foreach($report_data1 as $assignment)
            <tr>
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
</table>
<table class="table">
    <thead>
    <tr>
    <th>Student Name</th>
    <th>Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data2)>0)
        @foreach($report_data2 as $assignment)
            <tr>
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
</table>
</body>
</html>