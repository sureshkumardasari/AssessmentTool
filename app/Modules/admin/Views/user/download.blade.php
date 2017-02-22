<form class="form-horizontal" id="bulkuseruploadFrm" role="form" method="GET" action="{{url('user/downloadExcelforusers/xls') }}">
<input type="hidden" name="_token" class="hidden-token" value="{{csrf_token()}}">
<div class="panel panel-default">
    <div class="panel-heading">Download Users
    </div>
    <div class="panel-body">
        <ul>
            <li>1. Select Institution
                <select class="form-control" name="institution_id" id="userimport_institution_id" style="width:150px">
                    <option value="0">All</option>
                    @foreach($inst_arr as $id=>$val)
                        <option value="{{ $id }}">{{ $val }}</option>
                    @endforeach
                </select><span class="UserImportInstitutionId" style="font-weight: bolder;"></span>
            </li>
        </ul>
        <ul>
            <li>2. Select Role
            <select class="form-control" name="role_id" id="role_id" style="width:150px">
                <option value="0">All</option>
                @foreach($roles_arr as $id=>$val)
                    <option value="{{ $id }}">{{ $val }}</option>
                @endforeach
            </select>
            </li>
        </ul>


                <div class="form-group">
                <div class="col-md-5 right">
                    <button type="submit" class="btn btn-primary btn-sm right" style="margin: 2px;">Download</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    $('#userimport_institution_id').on("change",function(){
        //console.log('userimport_institution_id ');
        var thisVal = $('#userimport_institution_id').val();
        var msg = (thisVal > 0) ? 'Institution Id is <u>"' +thisVal+ '</u>"' : "";
        $(".UserImportInstitutionId").html(msg);
    });
</script>
{!! HTML::script(asset('/js/custom/user_bulk_upload.js')) !!}