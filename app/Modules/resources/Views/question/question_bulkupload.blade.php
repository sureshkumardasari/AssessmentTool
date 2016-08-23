
<div class="panel panel-default">
    <div class="panel-heading">Bulk Import Questions
    </div>
    <div class="panel-body"> 
        <form class="form-horizontal" id="bulkuseruploadFrm" role="form" method="POST" action="{{ url('/user/questionBulkUploadFile') }}">
            


            <div class="panel-body searchfilter-body">
            <div class="form-group col-md-6 required">
                <label class="col-md-2 control-label" >Institution</label>
                <div class="col-md-10">
                 <select class="form-control" name="institution_id_filter" id="institution_id_filter" onchange="change_institution()">
                    <option value="0">Select</option>
                    @foreach($inst_arr as $id=>$val)
                    <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                </select>
                    
                </div>
            </div>
            <div class="form-group col-md-6 required">
                <label class="col-md-2 control-label">Category</label>
                <div class="col-md-10">
                   <select class="form-control" name="category_id_filter" id="category_id_filter" onchange="change_category()">
                        {{--<option value="0">--Select Category--</option>--}}
                      
                    </select>
                </div>
            </div>
            <div class="form-group col-md-6 required">
                <label class="col-md-2 control-label">Subject</label>
                <div class="col-md-10">
                   <select class="form-control" name="subject_id_filter" id="subject_id_filter" onchange="change_lessons()">
                        {{--<option value="0">--Select Subject--</option>--}}
                       
                    </select>

                </div>
            </div>
            <div class="form-group col-md-6 required">
                <label class="col-md-2 control-label">Lessons</label>
                <div class="col-md-10">
                    <select class="form-control" name="lessons_id_filter" id="lessons_id_filter"  >
                        {{--<option value="0">--Select Lessons--</option>--}}
                       

                    </select>
                </div>
            </div>
            <div id="question_type_div"class="form-group col-md-6 required">
                <label class="col-md-2 control-label">Question Type</label>
                <div class="col-md-10">
                    <select class="form-control" name="question_type_filter" id="question_type_filter">
                        <option value="0">--Select Question Type--</option>
                        @foreach($qtypes as $id=>$val)
                                    <option value="{{ $id }}">{{ $val }}</option> 
                                    @endforeach
                    </select>

                </div>
            </div>
            <div class="form-group col-md-10">
            </div>

            <!--    <div class="form-group col-md-2">
                    <div class="col-md-6">
                        <div class="move-arrow-box">
                            <a class="btn btn-primary" onclick="filter();" href="javascript:;">Apply Filter</a>
                        </div>
                    </div>
                </div> -->

          <!--   <div class="form-group">
                <div class="col-md-2 " >
                    <button type="button" class="btn btn-danger btn-sm" id="clear_filters">clear</button>
                </div>

            </div> -->
        </div>

          <ul>
           <!--  <li>1. Select Institution to get Institution ID in Download templace                               
                <select class="form-control" name="institution_id" id="userimport_institution_id" style="width:150px">
                    <option value="0">Select</option>
                    @foreach($inst_arr as $id=>$val)
                    <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                </select><span class="UserImportInstitutionId" style="font-weight: bolder;"></span>
            </li> -->
            <li>1. Download the <a href="javascript:;" onclick="downloadTemplate('student')">Bulk Import Question Template </a> for Student use </li>            
            <li>2. Click “Browse” to choose the saved file.</li>
            <li>3. Click “Upload” to upload the file.</li>
        </ul>
        <div class="form-group required">
                <label class="col-md-5 control-label">Choose File(.xls)</label>
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

<input type="hidden" name="_token" class="hidden-token" value="{{csrf_token()}}">
<script type="text/javascript">
    
    questionBulkTemplate    = "{{route('questionBulkTemplate')}}";
    questionBulkUploadFile      = "{{route('questionBulkUploadFile')}}";

    $('#userimport_institution_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#userimport_institution_id').val();
        var msg = (thisVal > 0) ? 'Institution Id is <u>"' +thisVal+ '</u>"' : "";
        $(".UserImportInstitutionId").html(msg);        
    });
</script>  
{!! HTML::script(asset('/js/custom/question_bulk_upload.js')) !!}
@include('resources::question.question_import_data')