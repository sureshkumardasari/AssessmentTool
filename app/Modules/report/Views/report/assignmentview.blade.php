

<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Student Name</th>
        <th>Total Questions</th>
        <th>Correct Questions</th>
        <th>Percentage(%)</th>
    </tr>
    </thead>
    <tbody>

        @foreach($students as $student )
    <tr>
        <td>
        {{$student->name}}
        </td>
        <td>
        {{$student->total_count}}
        </td>
        <td>
        {{$student->answers_count}}
        </td>
        <td>
        {{($student->answers_count/$student->total_count)*100}}%
        </td>
    </tr>
        @endforeach

    </tbody>
</table>