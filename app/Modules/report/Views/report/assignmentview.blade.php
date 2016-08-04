

<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Student Name</th>
        <th>marks</th>
        <th>Percentage(%)</th>
    </tr>
    </thead>
    <tbody>
<?php $all_users_count=0; ?>
        @foreach($students as $student )
    <tr>
        <td>
        {{$student->name}}
        </td>
        <td>
        {{$student->score}}
            <?php $all_users_count+=$student->score;?>
        </td>
        <td>
            {{$student->percentage}}
        </td>
       {{-- --}}{{--<td>--}}{{--
        --}}{{--{{$student->answers_count}}--}}{{--
        --}}{{--</td>--}}{{--
        --}}{{--<td>--}}{{--
        --}}{{--{{($student->answers_count/$student->total_count)*100}}%--}}{{--
        --}}{{--</td>--}}
    </tr>
        @endforeach

    </tbody>
</table>
@if(count($students)>0)
<table class="table average">
    <tr>
        <td>class average score:</td>
        <td> {{$all_users_count}}/{{$total_marks *count($students)}}</td>
    </tr>
</table>
@endif
