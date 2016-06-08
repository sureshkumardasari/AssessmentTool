

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
        {{$student->user_name}}
        </td>
        <td>
        {{$student->question_id}}
        </td>
        <td>
        {{--{{$student->is_correct}}--}}
        </td>
        <td>

        </td>
    </tr>
        @endforeach

    </tbody>
</table>