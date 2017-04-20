@extends('default')
@section('content')


                <div class="col-md-10 col-md-offset-1">
					@if(Session::has('flash_message'))
						<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! Session('flash_message') !!}</em></div>
					@endif
				</div>
				<div class="col-md-10 col-md-offset-1">
					@if(Session::has('flash_message_failed'))
						<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
					@endif
				</div>

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<input type="hidden" name="_token" class="hidden-token" value="{{csrf_token()}}">
				<div class="panel-heading">Users
				@if (Auth::user()->id == 1)
					<a href="{{ url('/user/add/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add </a>
					<a href="{{ route('userBulkUpload') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Bulk Upload </a>
					<a href="{{ URL::to('user/download') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax">Download XLS</a>
					@else
					<a href="{{ URL::to('user/download') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax">Download XLS</a>
					@endif
				</div>
				
				<div class="panel-body">
                      @if (Auth::user()->id == 1)
					<div class="panel panel-default">
						<div class="panel-heading searchfilter pointer">Advanced Filters
							<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
						</div>
                         
						<div class="panel-body searchfilter-body hide">
						<form class="form-inline" role="form">
						<input type="hidden" name="page" id="page" value="role_id">
							<div class="form-group col-md-6  required">
								<label class="col-md-6 control-label">Select Institution</label>
								<div class="col-md-6">
								<input type="hidden" name="page" id="page" value="role_id">
									<select class="form-control" name="institution_id" id="institution_id">
										<option value=0>All</option>
										@foreach($inst_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<br><br>
							<!-- <?php getInstitutionsSelectBox('institution_id', 'institution_id', 0, '','All'); ?>  -->
							<br><br>
							<div class="form-group col-md-6">
								<label class="col-md-6">Select Role<span style="color:red;">*</span></label>
								<div class="col-md-6">
									<select class="form-control" name="role_id" id="role_id">
										<option value=0>Select</option>
										@foreach($roles_arr as $id=>$val)
										<option value="{{ $id }}">{{ $val }}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group col-md-12">
								<div class="col-md-6 col-md-offset-6">
									<button type="button" class="btn btn-primary" onclick="filter();" >
										Go
									</button>
								</div>
							</div>
							<div class="form-group" id="loadingdiv"></div>	
							</form>	
							</div>
						</div>
					</div>
					@endif
					<div id="user-list" style="min-height:50px;"> {!! $usersList !!} </div>					
				</div>
			</div>
		</div>
	</div>
</div>

<script>
  	var userSearchRoute = "{{URL::route('usersearch')}}";
</script>
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 });

     function filter(){ 
      
  if($('#institution_id').val() == 0 || ($('#role_id').val() == 0)){
                alert("please select all the fields");
    }
            else{
     var institution_id = $('#institution_id').val();
     var role_id = $('#role_id').val();
       console.log( "ready!" ); 

     
      $targetList = $('div#user-list'); 
   
     
     var data = $( "#institution_id, #role_id").serializeArray();
     //console.log('selecte instId ' + instId);
     $targetList.html('');
     $("#loadingdiv").html('<img src="/images/fancybox_loading.gif" border="0"> Loading...');
     $.ajax({
            type: "GET",
            data: data,
            url: userSearchRoute,
            success: function (data) {
                if (data.toLowerCase().indexOf("code") >= 0 && data.toLowerCase().indexOf("401") >= 0) {
                    window.location.reload();
                    return false;
                }
                if(data != ''){             
                    $targetList.html(data);
                }
                $("#loadingdiv").html('');
            }
        });
     }
 };
 </script>
 {!! HTML::script(asset('/js/custom/users.js')) !!}
@endsection