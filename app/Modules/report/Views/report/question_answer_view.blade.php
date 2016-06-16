
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
        {{$question->total_count}}
        </td>
        <td>
            {{$question->answers_count}}
        </td>
        <td>
{{$question->total_count-$question->answers_count}}
        </td>
        <td>
            {{($question->answers_count/$question->total_count)*100}}%

        </td>
    </tr>
        @endforeach
    </tbody>
</table>