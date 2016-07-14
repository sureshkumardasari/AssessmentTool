<table class="table">
    <thead>
    <th>Assignment</th>
    <th>Assessment</th>
    <th>Total Students</th>
    <th>Students attended</th>
    <th>Average of Assignment for Completed users</th>
    </thead>
    <tbody>
    @if(count($assignments)>0)
    @foreach($assignments as $assignment)
        <tr>
            <td>{{$assignment->assign_name}}</td>
            <td>{{$assignment->assess_name}}</td>
            <td>{{$All_users[$assignment->assign_id]}}</td>
            <td>{{isset($complete_users[$assignment->assign_id])?$complete_users[$assignment->assign_id]:0}}</td>
            <td>{{$marks[$assignment->assign_id]}}%</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="5" align="center">No data Available</td>
        </tr>
        @endif

    </tbody>
</table>


