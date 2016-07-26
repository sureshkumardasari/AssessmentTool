

<table class="table table-bordered table-hover table-striped" id="wholescore">
 <thead>
    <tr>
        <th>Student Name</th>
        @foreach($subjects as $val)
        <th><i>{{$val->assignmentname}}</i></th>
        @endforeach
        {{--<th>Correct Questions</th>--}}
        {{--<th>Percentage(%)</th>--}}
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
            <?php
            $user_obtained=(($student->multi_answers_count * $marks->mcsingleanswerpoint)+($student->essay_answers_count * $marks->essayanswepoint));
            $user_obtained= $user_obtained - (($student->total_count - ($student->multi_answers_count +$student->essay_answers_count)) * $marks->guessing_panality);
            $all_users_count +=$user_obtained;
            ?>
{{$user_obtained}}
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
    <tr><td>
        
    <h4 align="middle"><i>  @foreach($subjects as $sub) {{$sub -> subject}}
   
    @endforeach
        :: {{$all_users_count/count($students)}}/{{$total_marks}}</i></h4></td>
    </tr>
</table>
@endif