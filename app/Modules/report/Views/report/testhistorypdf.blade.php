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
<table border="0">
    <thead>
    <tr>
        <th colspan="12">Selected Institution</th>
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
<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
     <tr>
      <th colspan="3">Assignment</th>
      <th colspan="3">Assessment</th>
      <th colspan="3">Total Students</th>
      <th colspan="3">Students attended</th>
      <th colspan="3">Average of Assignment for Completed users</th>
     </tr>
    </thead>
    <tbody>
    @if(count($assignments)>0)
        @foreach($assignments as $assignment)
            <tr>
                <td colspan="3">{{$assignment->assign_name}}</td>
                <td colspan="3">{{$assignment->assess_name}}</td>
                <td colspan="3">{{$All_users[$assignment->assign_id]}}</td>
                <td colspan="3">{{isset($complete_users[$assignment->assign_id])?$complete_users[$assignment->assign_id]:0}}</td>
                <td colspan="3">{{$marks[$assignment->assign_id]}}%</td>
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