<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Total Questions</th>
        <th>Correct Questions</th>
        <th>Wrong Questions</th>
        <th>Percentage(%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($questions as $question )
    <tr>
        <td>
        {{$question->question_id}}
        </td>
        <td>

        </td>
        <td>

        </td>
        <td>

        </td>
    </tr>
        @endforeach
    </tbody>
</table>