<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Questions</th>
        <th>Correct Answer</th>
        <th>Your Answer</th>
     </tr>
    </thead>
    <tbody>
    @foreach($assignments as $assignment )
    <tr> 
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