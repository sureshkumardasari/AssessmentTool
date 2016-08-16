
<div class="panel panel-default">
    <div class="panel-heading">Bulk Import Users
    </div>
    <div class="panel-body">
        <ul>

            <li>1. Download the <a href="javascript:;" onclick="downloadTemplate('institution')">bulk import template</a> </li>
            <li>2. Click “Browse” to choose the saved file.</li>
            <li>3. Click “Upload” to upload the file.</li>
        </ul>
        <form class="form-horizontal" id="bulkuseruploadFrm" role="form" method="POST" action="{{ url('/user/bulkinstitutionUpload') }}">
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
    bulkInstitutionTemplate    = "{{url('user/bulkinstitutionTemplate')}}";
    bulkInstitutionUpload      = "{{url('user/bulkinstitutionUpload')}}";
</script>  
{!! HTML::script(asset('/js/custom/institution_bulk_upload.js')) !!}