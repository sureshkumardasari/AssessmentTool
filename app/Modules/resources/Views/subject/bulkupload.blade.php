<div class="panel panel-default">
    <div class="panel-heading">Bulk Import Subjects
    </div>
    <div class="panel-body">
            <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">

        <ul>
            <li>1. Select Institution to get Institution ID in Download templace
                <select class="form-control" name="institution_id" id="subjectimport_institution_id" style="width:170px" onchange="change_institution()">
                    <option value="0">--Select Institution--</option>
                    @foreach($inst_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                </select><span class="subjectimport_institution_id" style="font-weight: bolder;"></span>
            </li>
            <li>2. Select Category to get Category ID in Download template</br>
                <select class="form-control" name="category_id" id="subjectimport_category_id" style="width:170px">
                    <option value="0">--Select Category--</option>
                    {{--@foreach($cate_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach--}}
                </select><span class="subjectimport_category_id" style="font-weight: bolder;"></span>
            </li>
            <li>2. Download the <a href="javascript:;" onclick="downloadTemplate('subject')">bulk import template</a> for subject use </li>
            <li>3. Click “Browse” to choose the saved file.</li>
            <li>4. Click “Submit” to upload the file.</li>
        </ul>
        <form class="form-horizontal" id="bulksubjectuploadFrm" role="form" method="POST" action="{{ url('/subject/bulksubjectUpload') }}">
            <div class="form-group required">
                <label class="col-md-5 control-label control-label2">Choose File(.xls)</label>
                <div class="col-md-10">
                    <input type="file" class="subject-file" name="file">
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

<input type="hidden" name="_token" class="hidden-token" value="{{csrf_token()}}">
{!! HTML::script(asset('/js/custom/subject_bulk_upload.js')) !!}
<script type="text/javascript">
 <?php
            $path = url()."/resources/";?>
    bulksubjectTemplate   = "{{route('bulksubjectTemplate')}}";
    bulksubjectUpload     = "{{route('bulksubjectUpload')}}";

    $('#subjectimport_institution_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#subjectimport_institution_id').val();
        var msg = (thisVal > 0) ? 'Institution Id is <u>"' +thisVal+ '</u>"' : "";
         if(thisVal > 0){
        $(".subjectimport_institution_id").html(msg);
         //$("#subjectimport_category_id").val();
         $(".subjectimport_category_id").html("");
    }else{
        $(".subjectimport_institution_id").html("");
          $(".subjectimport_category_id").html("");
    }
    });

    

function change_institution(){
        var csrf=$('Input#csrf_token').val();
        $.ajax(
                {

                    headers: {"X-CSRF-Token": csrf},
                    url:'{{$path}}categoryList/'+$('#subjectimport_institution_id').val(),
                    type:'post',
                    success:function(response){
                        var a=response.length;
                        $('#subjectimport_category_id').empty();
                        
                        var opt=new Option('--Select Category--','');
                        //opt.addClass('selected','disabled','hidden');
                        $('#subjectimport_category_id').append(opt);
                        for(i=0;i<a;i++){
                            var opt=new Option(response[i].name,response[i].id);
                            $('#subjectimport_category_id').append(opt);
                        }
                    }
                }
        )
    }
    $('#subjectimport_category_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#subjectimport_category_id').val();
        var msg = (thisVal > 0) ? 'Category Id is <u>"' +thisVal+ '</u>"' : "";
        $(".subjectimport_category_id").html(msg);
    });
</script>
