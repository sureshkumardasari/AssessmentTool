
<html>
   <h3 align="center">Questions & Answers Report</h3>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
<table border="0">
    <thead>
    <tr>
        <th colspan="4">Selected Institution</th>
        <th colspan="4">Selected Assignment</th>
        <th colspan="4">Selected Subject</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        @foreach($inst as $val)
            <td colspan="4">{{$val->name}}</td>
        @endforeach
        @foreach($assign as $val)
            <td colspan="4">{{$val->name}}</td>
        @endforeach
            @foreach($sub as $val)
                <td colspan="4">{{$val->name}}</td>
            @endforeach
      </tr>
    </tbody>
</table><br>
<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th colspan="6">Question Id </th>
        <th colspan="6">Accuracy Percentage</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ques as $id=>$question )
        <tr>
            <td colspan="6">
                {{$question}}
            </td>
            <td colspan="6">
                {{isset($user_answered_correct_count[$id])?(($user_answered_correct_count[$id]/$user_count[$id])*100).'%':'no one answer the question'}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table><br>
</body>
</html>
