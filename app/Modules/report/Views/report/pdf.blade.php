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
    <table >
        <thead>
        <tr>
            <th>
                Student Name
            </th>
            <th>
                Total Questions
            </th>
            <th>
                Correct Questions
            </th>
            <th>
                Percentage

            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($students as $student)
        <tr>
            <td align="center">
                {{$student->name}}
            </td>
            <td align="center">
                {{$student->total_count}}
            </td>
            <td align="center">
                {{$student->answers_count}}
            </td>
            <td align="center">
                {{($student->answers_count/$student->total_count)*100}}%
            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>