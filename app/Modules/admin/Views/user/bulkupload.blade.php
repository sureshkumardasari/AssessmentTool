
<div class="panel panel-default">
    <div class="panel-heading">Bulk Import Users
    </div>
    <div class="panel-body">
        <ul>
            <li>1. Download the <a href="javascript:;" onclick="downloadTemplate('Student')">bulk import template</a> for Student use </li>
            <li>2. Select Institution to get Institution ID                                
                <select class="form-control col-md-4" name="institution_id" id="userimport_institution_id">
                    <option value="0">All</option>
                    @foreach($inst_arr as $id=>$val)
                    <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                </select><span class="UserImportInstitutionId"></span>
            </li>
            <li>3. Click “Browse” to choose the saved file.</li>
            <li>4. Click “Upload” to upload the file.</li>
        </ul>
        <div class="form-group required">
            <label class="col-md-10 control-label">Choose File(csv/xls)</label>
            <div class="col-md-10">
                <input type="file" class="" name="importfile">
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-5 right">
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
                <button type="button" class="btn btn-primary" onclick="parent.$.fancybox.close()">
                    Cancel
                </button>
            </div>           
        </div>
    </div>
</div>    
