<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Assignment</th>
        <th>Assessment</th>
        <th>Date</th>
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

        </td>
        <td>
            {{$assignment->question_id}}
        </td>
        <td>

        </td>
        <td>

        </td>
    </tr>
        @endforeach
    </tbody>
</table>