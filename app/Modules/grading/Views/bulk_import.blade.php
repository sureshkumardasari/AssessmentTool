
<div class="panel panel-default">
    <div class="panel-heading">Bulk Import Grades
    </div>
    <div class="panel-body">
        <ul>
        <?php $i=1; 

        $role=getRole();
        ?>
        @if($role =="administrator" || $role =="teacher")
         <li>{{$i++}}. Select Institution to get Institution ID's in Download Template                               
                <select class="form-control" name="institution_id" id="gradesimport_institution_id" style="width:150px">
                    <option value="0">--Select--</option>
                    @foreach($institution_arr as $id=>$val)
                    <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                         @endif
                </select><span class="gradesimport_institution_id" style="font-weight: bolder;"></span>
            </li>
   
        
            <li>{{$i++}}. Select  to get Assignment ID in Download template                               
                <select class="form-control" name="assignment_id" id="gradesimport_assignment_id" style="width:150px">
                    @if($role != "administrator")
                    <option value="0">--Select--</option>
                    @foreach($assignments_arr as $id=>$val)
                    <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                    @endif
                </select><span class="gradesimport_assignment_id" style="font-weight: bolder;"></span>
            </li>
            <?php  if($role =='administrator' ){
                        $param="administrator";
                    }
                    else{
                        $param="admin_teacher";
                    }
            ?>
            <li>{{$i++}}. Download the <a href="javascript:;" onclick="downloadTemplate('{{$param}}')">bulk import template</a> for Student use </li>            
            <li>{{$i++}}. Click “Browse” to choose the saved file.</li>
            <li>{{$i++}}. Click “Upload” to upload the file.</li>
        </ul>
        <form class="form-horizontal" id="bulkuseruploadFrm" role="form" method="POST" action="{{ url('/user/bulkuserupload') }}">
            <div class="form-group required">
                <label class="col-md-5 control-label control-label2">Choose File(.xls)</label>
                <div class="col-md-10">
                    <input type="file" class="user-file" name="file">
                </div>
            </div>
            <p class="error-log"></p>
            <div class="form-group">
                <div class="col-md-5 right">
                    <button type="button" class="btn btn-primary uploadBtn">
                        Submit
                    </button>
                    <button type="button" class="btn btn-primary" onclick="parent.$.fancybox.close()">
                        Cancel
                    </button>
                    <div class="userSuccMSG"></div> 
                </div>           
            </div>
        </form>
    </div>
</div>

<input type="hidden" id="_token" name="_token" class="hidden-token" value="{{csrf_token()}}">
<script type="text/javascript">
    
    bulkGradesTemplate    = "{{route('bulkGradeTemplate')}}";
    bulkGradesUpload      = "{{route('bulkGradeUpload')}}";

//for Institution selection
    $('#gradesimport_institution_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var csrf=$('Input#csrf_token').val();
        var thisVal = $('#gradesimport_institution_id').val();
        var msg = (thisVal > 0) ? 'Institution Id is <u>"' +thisVal+ '</u>"' : "";
        $(".gradesimport_institution_id").html(msg);    
         var loadurl = "{{ url('/report/assessment_inst/') }}/" ;
        $.ajax({
            headers: {"X-CSRF-Token": csrf},
            url:loadurl+ $('#gradesimport_institution_id').val(),
            type:"post",
            success:function(response){
                $('#gradesimport_assignment_id').empty();
                var option = new Option('--Please Select--',0);
                $('#gradesimport_assignment_id').append(option);
                $.each(response,function(index,val){
                    var option= new Option(val['name'],val['id']);
                    $('#gradesimport_assignment_id').append(option);
                });
            }
        });    
    });
    //for Assignment selection
    $('#gradesimport_assignment_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#gradesimport_assignment_id').val();
        var msg = (thisVal > 0) ? 'Assignment Id is <u>"' +thisVal+ '</u>"' : "";
        $(".gradesimport_assignment_id").html(msg);        
    });
</script>  
{!! HTML::script(asset('/js/custom/grades_bulk_import.js')) !!}