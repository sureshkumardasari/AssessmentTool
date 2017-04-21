<div class="panel panel-default">
    <div class="panel-heading">Bulk Import Categories
    </div>
    <div class="panel-body">
        <ul>
            <li>1. Select Institution to get Institution ID in Download templace
                <select class="form-control" name="institution_id" id="categoryimport_institution_id" style="width:150px">
                    <option value="0">--Select--</option>
                    @foreach($inst_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                </select><span class="categoryimport_institution_id" style="font-weight: bolder;"></span>
            </li>
            <li>2. Download the <a href="javascript:;" onclick="downloadTemplate('category')">bulk import template</a> for category use </li>
            <li>3. Click “Browse” to choose the saved file.</li>
            <li>4. Click “Submit” to upload the file.</li>
        </ul>
        <form class="form-horizontal" id="bulkcategoryuploadFrm" role="form" method="POST" action="{{ url('/category/bulkcategoryUpload') }}">
            <div class="form-group required">
                <label class="col-md-5 control-label control-label2">Choose File(.xls)</label>
                <div class="col-md-10">
                    <input type="file" class="category-file" name="file">
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
{!! HTML::script(asset('/js/custom/category_bulk_upload.js')) !!}
<script type="text/javascript">

    bulkcategoryTemplate   = "{{route('bulkcategoryTemplate')}}";
    bulkcategoryUpload     = "{{route('bulkcategoryUpload')}}";

    $('#categoryimport_institution_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#categoryimport_institution_id').val();
        var msg = (thisVal > 0) ? 'Institution Id is <u>"' +thisVal+ '</u>"' : "";
        $(".categoryimport_institution_id").html(msg);
    });
</script>
