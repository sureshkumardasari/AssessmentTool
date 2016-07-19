
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