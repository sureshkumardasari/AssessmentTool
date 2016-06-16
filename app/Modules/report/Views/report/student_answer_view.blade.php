<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Assignment</th>
        <th>Assessment</th>
        <th>Total Questions</th>
        <th>Correct Questions</th>
        <th>Percentage(%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($assignments as $assignment )
    <tr>
        <td>
            {{$assignment->assignment_name}}
        </td>
        <td>
            {{$assignment->assessment_name}}
        </td>
        <td>
            {{$assignment->total_count}}
        </td>
        <td>
            {{$assignment->answers_count}}
        </td>
        <td>
            {{($assignment->answers_count/$assignment->total_count)*100}}%
        </td>
    </tr>
        @endforeach
    </tbody>
</table>