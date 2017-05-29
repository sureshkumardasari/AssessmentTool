
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
<table border="0" width="100%">
    <thead>
    <tr>
        <th>Selected Institution</th>
        <th>Selected Assignment</th>
        <th width="40%">Selected Subject</th>
    </tr>
    </thead>
    <tbody>
      <tr>
        @foreach($inst as $val)
            <td>{{$val->name}}</td>
        @endforeach
        @foreach($assign as $val)
            <td>{{$val->name}}</td>
        @endforeach
            @foreach($sub as $val)
                <td>{{$val->name}}</td>
            @endforeach
      </tr>
    </tbody>
</table><br>
<table class="table table-bordered table-hover table-striped" id="report" width="100%">
    <thead>
    <tr>
        <th>Question </th>
        <th width="50%">Accuracy Percentage</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ques as $id=>$question )
        <tr>
            <td>
                {{ strip_tags(htmlspecialchars_decode($question)) }}
            </td>
            <td>
                {{round(isset($user_answered_correct_count[$id])?(($user_answered_correct_count[$id]/$user_count[$id])*100):'').'%'}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table><br>
</body>
</html>
