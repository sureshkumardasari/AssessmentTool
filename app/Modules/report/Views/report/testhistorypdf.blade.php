<html>
<h3 align="center">Test-History-Class-Averages</h3>
<head>
    <style>
        table, th, td {
            border: 0px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
<table border="0" width="100%">
    <thead>
    <tr>
        <th>Selected Institution</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($inst as $val)
            <td>{{$val->name}}</td>
        @endforeach
    </tr>
    </tbody>
</table><br>
<table class="table table-bordered table-hover table-striped" id="report" width="100%">
    <thead>
     <tr>
      <th>Assignment</th>
      <th>Assessment</th>
      <th>Total Students</th>
      <th>Students attended</th>
      <th>Average of Assignment for Completed users</th>
     </tr>
    </thead>
    <tbody>
    @if(count($assignments)>0)
        @foreach($assignments as $assignment)
            <tr>
                <td>{{$assignment->assign_name}}</td>
                <td>{{$assignment->assess_name}}</td>
                <td>{{$All_users[$assignment->assign_id]}}</td>
                <td>{{isset($complete_users[$assignment->assign_id])?$complete_users[$assignment->assign_id]:0}}</td>
                <td>{{$mark[($assignment->assign_id)].'%'}}</td>
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