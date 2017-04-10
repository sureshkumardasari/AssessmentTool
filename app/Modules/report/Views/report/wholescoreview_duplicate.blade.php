<div class="form-group col-md-12">
                            <div class="col-md-6 col-md-offset-8">
                            <a href="#" class="btn btn-primary" id="pdf" onclick="reports()">Export PDF</a>
                            <a href="#" class="btn btn-primary" id="xls" onclick="reports()">Export XLS</a>
                        </div></div>
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
function reports(){
             var csrf=$('Input#csrf_token').val();
                $.ajax(
                        {

                            headers: {"X-CSRF-Token": csrf},
                            url: loadurl + $('#institution_id').val() + '/' + $('#assignment_id').val(),
                            type: 'post',
                            success: function (response) {
                                $('#report').empty();
                                $('#report').append(response);
                                $('#report').prepend($('.average'));
                            }
                        }
                )
            
        }

</script>
@endif