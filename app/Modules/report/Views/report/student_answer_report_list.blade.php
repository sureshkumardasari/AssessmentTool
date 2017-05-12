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
        <th>Question</th>
        <th>Correct Answer</th>
        <th>Your Answer</th>
     </tr>
    </thead>
    <tbody> 
    <tr>
    <?php 
        $pre_qid = '';

        $temp_your_ans = [];
        $pre_right_ans = [];
    ?>
    @foreach($assignments as $assignment)
        <?php
            $right_ans = [];
        ?>
        @foreach($correct_answers as $key => $correct_answer )
            @if($key == $assignment->questionid)
            @foreach($correct_answer as $key1 => $value )
                <?php
                    
                    $right_ans[] = $arr[$value];
                ?>
            @endforeach
            @endif
        @endforeach

        <?php

        if($pre_qid=='' || $pre_qid!=$assignment->questionid){
            if($pre_qid!='' && $pre_qid!=$assignment->questionid){

                echo '<td>';
                foreach ($temp_your_ans as $key => $value) {
                    if(in_array($value, $pre_right_ans)){
                        echo '<span class="glyphicon glyphicon-ok"></span>'.($value)."&nbsp;&nbsp;&nbsp;";
                    }else
                echo '<span class="glyphicon glyphicon-remove"></span>'.($value)."&nbsp;&nbsp;&nbsp;";

                
                }

                
                echo '</td> </tr><tr>';

                $temp_your_ans = [];
            }

            echo '<td>'
                .$assignment->qst_text.'
            </td>
            <td>'.implode(',', $right_ans).'             
            </td>';
            
            

        }

            $temp_your_ans[] = $assignment->your_answer;
            $pre_right_ans = $right_ans;

        ?>
        
                 

        <?php

        $pre_qid = $assignment->questionid;
        ?>

        @endforeach
        <?php
        if($pre_qid!=''){
          

            echo '<td>';
                foreach ($temp_your_ans as $key => $value) {
                    if(in_array($value, $right_ans)){
                        echo '<span class="glyphicon glyphicon-ok"></span>'.($value)."&nbsp;&nbsp;&nbsp;";
                    }else
                echo '<span class="glyphicon glyphicon-remove"></span>'.($value)."&nbsp;&nbsp;&nbsp;";

                
                }

                
                echo '</td> ';

        }
        ?>
        </tr>
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