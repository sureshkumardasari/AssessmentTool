
<html>
<head>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Question Id </th>
        <th>Accuracy Percentage</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ques as $id=>$question )
        <tr>
            <td>
                {{$question}}
            </td>
            <td>
                {{isset($user_answered_correct_count[$id])?(($user_answered_correct_count[$id]/$user_count[$id])*100).'%':'no one answer the question'}}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{--@if(count($students)>0)
    <table class="table average">
        <tr>
            <td>class average score:</td>
            <td> {{$all_users_count/(count($students))}}</td>
        </tr>
    </table>
@endif--}}
</body>
</html>
