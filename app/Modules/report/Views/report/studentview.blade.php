<table class="table table-bordered table-hover table-striped" id="report">
<thead>
<tr>
    <th>Assessment</th>
    <th>Assignment</th>
    <th>Date</th>
    <th>Correct Questions</th>
    <th>Percentage(%)</th>
</tr>
</thead>
<tbody>

@foreach($assignments as $assignment )
    <tr>
        <td>
            {{$assignment->assessment_name}}
        </td>
        <td>
            {{$assignment->assignment_name}}
        </td>
        <td>
            {{--{{$student->is_correct}}--}}
        </td>
        <td>
            {{$assignment->question_id}}
        </td>
        <td></td>
    </tr>
@endforeach


</tbody>
</table>