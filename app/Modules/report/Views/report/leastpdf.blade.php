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
<table class="table">
    <thead>
    <tr>
    <th colspan="6">Student Name</th>
    <th colspan="6">Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data)>0)
        @foreach($report_data as $assignment)
            <tr>
                <td colspan="6">{{$assignment->user_name}}</td>
                <td colspan="6">{{$assignment->rawscore}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" align="center">No data Available</td>
        </tr>
    @endif

    </tbody>
</table><br>
<table class="table">
    <thead>
    <tr>
    <th colspan="6">Student Name</th>
    <th colspan="6">Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data1)>0)
        @foreach($report_data1 as $assignment)
            <tr>
                <td colspan="6">{{$assignment->user_name}}</td>
                <td colspan="6">{{$assignment->rawscore}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" align="center">No data Available</td>
        </tr>
    @endif

    </tbody>
</table><br>
<table class="table">
    <thead>
    <tr>
    <th colspan="6">Student Name</th>
    <th colspan="6">Score</th>
    </tr>
    </thead>
    <tbody>
    @if(count($report_data2)>0)
        @foreach($report_data2 as $assignment)
            <tr>
                <td colspan="6">{{$assignment->user_name}}</td>
                <td colspan="6">{{$assignment->rawscore}}</td>
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