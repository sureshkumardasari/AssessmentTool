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
        $pre_qtype = '';

        $temp_your_ans = [];
        $pre_right_ans = [];
        //dd($assignments);
    ?>
    @foreach($assignments as $assignment)
        <?php
            $right_ans = [];
            //print_r($correct_answers);
        ?>
        @foreach($correct_answers as $key => $correct_answer )
            @if($key == $assignment->questionid && ($assignment->qtype == 3))
            <?php 
                 $right_ans[] = 'essay';
            ?>
            @elseif($key == $assignment->questionid && ($assignment->qtype == 4))
            <?php
                    
                    $right_ans[] = $correct_answer;
                ?>
            @elseif($key == $assignment->questionid && (($assignment->qtype == 1)|| ($assignment->qtype == 2)))
            @foreach($correct_answer as $key1 => $value )
                <?php
                    
                    $right_ans[] = $arr[$value];
                ?>
            @endforeach
            @endif
        @endforeach

        <?php
//dd($right_ans);
        if($pre_qid=='' || $pre_qid!=$assignment->questionid){
            if($pre_qid!='' && $pre_qid!=$assignment->questionid && $pre_qtype!= ''){

                echo '<td>';
                if($pre_qtype == 3)
                {
                    foreach ($temp_your_ans as $key => $value) {
                    echo $value;
                    }
                }
                else
                {
                foreach ($temp_your_ans as $key => $value) {
                    if(in_array($value, $pre_right_ans)){
                        echo '<span class="glyphicon glyphicon-ok"></span>'.($value)."&nbsp;&nbsp;&nbsp;";
                    }else
                echo '<span class="glyphicon glyphicon-remove"></span>'.($value)."&nbsp;&nbsp;&nbsp;";

                }
                }

                
                echo '</td> </tr><tr>';

                $temp_your_ans = [];
            }

            echo '<td>'
                .$assignment->qst_text.'
            </td>';
            if(($assignment->qtype == 2) || ($assignment->qtype == 1))
            {
                //dd($right_ans);
                echo '<td>'.implode(',', $right_ans).'</td>';
            }
            elseif(($assignment->qtype == 4))
            {
                //dd($right_ans);
                echo '<td>'.implode(',', $right_ans[0]).'</td>';
            }
            else
            {
               echo '<td> essay </td>'; 
            }
            

        }
            if(($assignment->qtype == 2) || ($assignment->qtype == 1))
            {
                $temp_your_ans[] = $assignment->your_answer;
                $pre_right_ans = $right_ans;

            }
            elseif($assignment->qtype == 4)
            {
                $temp_your_ans[] = $assignment->answer_text;
                $pre_right_ans = $right_ans[0];
            }
            else
            {
               $temp_your_ans[] = $assignment->essaypoints;
                $pre_right_ans = array('essay'); 
            }

        ?>
        
                 

        <?php

        $pre_qid = $assignment->questionid;
        $pre_qtype = $assignment->qtype;
        ?>

        @endforeach
        <?php
        if($pre_qid!=''){
          

            echo '<td>';
             if($pre_qtype == 3)
                {
                    foreach ($temp_your_ans as $key => $value) {
                    echo $value;
                    }
                }
                else
                {
                foreach ($temp_your_ans as $key => $value) {
                    if(in_array($value, $right_ans)){
                        echo '<span class="glyphicon glyphicon-ok"></span>'.($value)."&nbsp;&nbsp;&nbsp;";
                    }else
                echo '<span class="glyphicon glyphicon-remove"></span>'.($value)."&nbsp;&nbsp;&nbsp;";

                
                }
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