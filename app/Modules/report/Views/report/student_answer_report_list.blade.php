<div class="form-group col-md-12">
                                <div class="col-md-7"></div><div class="col-md-5">
                        <a href="#" class="btn btn-primary" id="pdf" >Export PDF</a>
                        <a href="#" class="btn btn-primary" id="xls" >Export XLS</a>
                    </div></div>

<html>
<?php
$arr=[1=>'A',2=>'B',3=>'C',4=>'D',5=>'E'];
?>



<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    @if($assignments)
    <tr>
        <th>Questions</th>
        <th>Correct Answer</th>
        <th>Your Answer</th>
     </tr>
    </thead>
    <tbody> 
    @foreach($assignments as $assignment)
    <tr> 
        <td>
            {{$assignment->question_title}}
        </td>
        <td> 
            {{$arr[$assignment->correct_answer]}}
        </td>
        <td> 
        @if($assignment->is_correct=="No")
           <p style="color:red;font:bold;">{{$assignment->your_answer}}&nbsp;&nbsp;<span class="glyphicon glyphicon-remove"></span></p> 
        @elseif($assignment->is_correct=="Open")
          <p style="color:green;font:bold;">{{$assignment->your_answer}}&nbsp;&nbsp;<span class="glyphicon glyphicon-ok"></span></p>
        @elseif($assignment->is_correct=="Yes")
            <p style="color:green;font:bold;">{{$assignment->your_answer}} &nbsp;&nbsp;<span class="glyphicon glyphicon-ok"></span></p>
        @endif
        </td>
    </tr>
        @endforeach
    </tbody>
     @else
     <tbody>
        <tr>    
            <td style="padding-left:500px;">
            No Data To Display
            </td>
        </tr>
    </tbody>
    @endif
</table>
</html>
<script type="text/javascript">
     $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assign_student').val();
            var student_id=$('#student').val();
            window.open("{{ url('report/SAR_PDF/')}}/"+inst_id+"/"+assign_id+"/"+student_id);
        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assign_student').val();
            var student_id=$('#student').val();
            window.open("{{ url('report/SAR_XLS/')}}/"+inst_id+"/"+assign_id+"/"+student_id);
        });
</script>