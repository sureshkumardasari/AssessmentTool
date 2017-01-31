<div class="panel panel-default">
    <div class="panel-heading">Bulk Import Lesson
    </div>
    <div class="panel-body">
        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
        <ul>
            <li>1. Select Institution to get Institution ID in Download template
                <select class="form-control" name="institution_id" id="lessonimport_institution_id" style="width:150px" onchange="change_institution()">
                    <option value="0">Select</option>
                    @foreach($inst_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                </select><span class="lessonimport_institution_id" style="font-weight: bolder;"></span>
            </li>
            <li>2. Select Category to get Category ID in Download template</br>
                <select class="form-contro2" name="category_id" id="lessonimport_category_id" style="width:150px" onchange="change_category()">
                    <option value="0">Select</option>
                    {{--@foreach($cate_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach--}}
                </select><span class="lessonimport_category_id" style="font-weight: bolder;"></span>
            </li>
            <li>3. Select subject to get subject ID in Download template
                <select class="form-control" name="subject_id" id="lessonimport_subject_id" style="width:150px" >
                    <option value="0">Select</option>
                    {{--@foreach($inst_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach--}}
                </select><span class="lessonimport_subject_id" style="font-weight: bolder;"></span>
            </li>
            <li>2. Download the <a href="javascript:;" onclick="downloadTemplate('Lesson')">bulk import template</a> for lesson use </li>
            <li>3. Click “Browse” to choose the saved file.</li>
            <li>4. Click “Submit” to upload the file.</li>
        </ul>
        <form class="form-horizontal" id="bulklessonuploadFrm" role="form" method="POST" action="{{ url('/lesson/bulklessonUpload') }}">
            <div class="form-group required">
                <label class="col-md-5 ">Choose File(.xls)</label>
                <div class="col-md-10">
                    <input type="file" class="lesson-file" name="file">
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
{!! HTML::script(asset('/js/custom/lesson_bulk_upload.js')) !!}


<script type="text/javascript">
    <?php
            $path = url()."/resources/";?>
    bulklessonTemplate   = "{{route('bulklessonTemplate')}}";
    bulklessonUpload     = "{{route('bulklessonUpload')}}";

    $('#lessonimport_institution_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#lessonimport_institution_id').val();
        var msg = (thisVal > 0) ? 'Institution Id is <u>"' +thisVal+ '</u>"' : "";
        $(".lessonimport_institution_id").html(msg);
    });

    function change_institution(){
        var csrf=$('Input#csrf_token').val();
        $.ajax(
                {

                    headers: {"X-CSRF-Token": csrf},
                    url:'{{$path}}categoryList/'+$('#lessonimport_institution_id').val(),
                    type:'post',
                    success:function(response){
                        var a=response.length;
                        $('#lessonimport_category_id').empty();
                        var opt=new Option('--Select Category--','');
                        //opt.addClass('selected','disabled','hidden');
                        $('#lessonimport_category_id').append(opt);
                        for(i=0;i<a;i++){
                            var opt=new Option(response[i].name,response[i].id);
                            $('#lessonimport_category_id').append(opt);
                        }
                    }
                }
        )
    }
    function change_category(){
        var csrf=$('Input#csrf_token').val();
        $.ajax(
                {

                    headers: {"X-CSRF-Token": csrf},
                    url:'{{$path}}subjectList/'+$('#lessonimport_category_id').val(),
                    type:'post',
                    success:function(response){
                        var a=response.length;
                        $('#lessonimport_subject_id').empty();

                        var opt=new Option('--Select Subject--','');
                        $('#lessonimport_subject_id').append(opt);
                        for(i=0;i<a;i++){
                            var opt=new Option(response[i].name,response[i].id);
                            $('#lessonimport_subject_id').append(opt);
                        }
                    }
                }
        )
    }

    $('#lessonimport_category_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#lessonimport_category_id').val();
        var msg = (thisVal > 0) ? 'category Id is <u>"' +thisVal+ '</u>"' : "";
        $(".lessonimport_category_id").html(msg);
    });
    $('#lessonimport_subject_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#lessonimport_subject_id').val();
        var msg = (thisVal > 0) ? 'subject Id is <u>"' +thisVal+ '</u>"' : "";
        $(".lessonimport_subject_id").html(msg);
    });

</script>
