 <div class="form-group col-md-12">
                                <label class="col-md-6 control-label"></label>
                                <div class="col-md-6">
                        <a href="#" class="btn btn-primary" id="pdf" >Export PDF</a>
                        <a href="#" class="btn btn-primary" id="xls" >Export XLS</a>
                    </div></div>
<table class="table table-bordered table-hover table-striped" id="report">
    <thead>
    <tr>
        <th>Question</th>
        <th>Accuracy Percentage(%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ques as $id=>$question )
    <tr>
        <td style="width:82%">
       <!--  {{$question}} -->

        {{ strip_tags(htmlspecialchars_decode($question)) }}
        </td>
       
        <td>
            {{round(isset($user_answered_correct_count[$id])?(($user_answered_correct_count[$id]/$user_count[$id])*100):'').'%'}}
        </td>
    </tr>
        @endforeach
    </tbody>
</table>
<script type="text/javascript">
    $('#pdf').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assignment').val();
            var sub_id=$('#subject').val();
            window.open("{{ url('report/QuestionsexportPDF/')}}/"+inst_id+"/"+assign_id+"/"+sub_id);

        });
        $('#xls').on('click',function(){
            var inst_id=$('#institution_id').val();
            var assign_id=$('#assignment').val();
            var sub_id=$('#subject').val();
            window.open("{{ url('report/QuestionsexportXLS/')}}/"+inst_id+"/"+assign_id+"/"+sub_id);

        });
</script>