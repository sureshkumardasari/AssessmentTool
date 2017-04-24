<html>
<h3 align="center">Whole Class Score Report</h3>
<head>
</head>
<body>
<table border="1" width="100%">
    <thead>
    <tr>
        <th>Selected Institution</th>
        <th>Selected Assignment</th>
        <th>Selected Subject</th>
        <th>Selected Lesson</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($inst as $val)
            <td>{{$val->name}}</td>
        @endforeach
        @foreach($assign as $val)
            <td>{{$val->name}}</td>
        @endforeach
        @foreach($sub1 as $val)
                <td>{{$val->name}}</td>
            @endforeach
            @foreach($less as $val)
                <td>{{$val->name}}</td>
            @endforeach
    </tr>
    </tbody>
</table>
    @if($type == "subjects")

  <p align="center"><b>Assignment:: </b>{{$assignment->name}}</p><br>
    <table class="table table-bordered table-hover table-striped" id="wholescore">
        <caption><center><b>Subjects</b></center></caption>
        <thead>
        <th>Student Name</th>

        @foreach($subjects as $sub)
            <th>{{$sub}}</th>
        @endforeach
        </thead>
        <tbody>
        @foreach($subject_score as $stud_id=>$subject)
            <tr>
                <td>{{$students[$stud_id]}}</td>
                @foreach($subject as $id=>$score)
    <?php $sum=(($score[0]->sum == "null")||($score[0]->sum == ""))?0:$score[0]->sum;?>
                <td>{{$sum - ($penality[$stud_id][$id]['multi_single'] )*$assignment->guessing_panality}}/{{$score[0]->total}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@elseif($type== "lessons")
    <p align="center"><b>Assignment:: </b>{{$assignment->name}}</p><br>
    <p align="center"><b>Subject:: </b>{{$subjects[$sub_id]}}</p>

    <table class="table table-bordered table-hover table-striped" id="wholescore">
        <caption><center><b>Lessons</b></center></caption>
        <thead>
        <th>Student Name</th>
        @foreach($lessons as $id=>$lesson)
            <th>{{$lesson}}</th>
        @endforeach
        </thead>
        <tbody>
        @foreach($lesson_score as $stud_id=>$lesson)
            <tr>
                <td>{{$students[$stud_id]}}</td>
                @foreach($lesson as $score)
                    <?php $sum=(($score[0]->sum == "null")||($score[0]->sum == ""))?0:$score[0]->sum;?>
                    <td>{{$sum - ($penality[$stud_id][$id]['multi_single'] )*$assessment->guessing_panality}}/{{$score[0]->total}}</td>
                @endforeach
            </tr>
        @endforeach 
        </tbody>
    </table>

@endif
</body>
</html>