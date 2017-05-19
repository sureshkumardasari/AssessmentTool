<div class="form-group col-md-12 gobutton">
                            <div class="col-md-6 col-md-offset-8">
                            <a href="#" class="btn btn-primary" id="pdf" >Export PDF</a>
                            <a href="#" class="btn btn-primary" id="xls" >Export XLS</a>
                        </div></div><br><br><br>
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
                <td>{{$sum - ($penality[$stud_id][$id]['multi_single'] )*$assessment->guessing_panality}}/{{$totalpoints[$id]}}</td>
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
                
                @if(count($lesson > 0))
                @foreach($lesson as $lid=>$score)
                
                    <?php  $sum=(($score[0]->sum == "null")||($score[0]->sum == ""))?0:$score[0]->sum; ?>
                    <td>{{$sum - ($penality[$stud_id][$id]['multi_single'] )*$assessment->guessing_panality}}/{{$totalpoints[$lid]}}</td>
                @endforeach
                @else
                <td> </td>
                @endif
            </tr>
        @endforeach 
        </tbody>
    </table>
@endif
<script type="text/javascript">
   var loadurl = "{{ url('/report/assignment_wholeclass/') }}/" ;

    $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assi_id=$('#assignment_id').val();
            var sub_id=$('#subject_id').val();
            var less_id=$('#lesson_id').val();
            window.open("{{ url('report/wholeclassscoreexportPDF/')}}/"+inst_id+"/"+assi_id+"/"+sub_id+"/"+less_id);
        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assi_id=$('#assignment_id').val();
            var sub_id=$('#subject_id').val();
            var less_id=$('#lesson_id').val();
            window.open("{{ url('report/wholeclassscoreexportXLS/')}}/"+inst_id+"/"+assi_id+"/"+sub_id+"/"+less_id);
        });


</script>