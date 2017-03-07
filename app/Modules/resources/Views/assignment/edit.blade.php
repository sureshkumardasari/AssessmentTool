@extends('default')

@section('header-assets')
@parent
{!! HTML::style(asset('/css/bootstrap-datetimepicker.min.css')) !!}
{!! HTML::script(asset('/js/moment.min.js')) !!}
{!! HTML::script(asset('/js/bootstrap-datetimepicker.min.js')) !!}
{!! HTML::script(asset('/js/dual-list-box.min.js')) !!}
@stop
@section('content')

<?php 
$dtFormat = 'Y/m/d g:i:s A';
$id = (old('id') != NULL) ? old('id') : $assignment->id;
$grader_id=(old('grader_id') != NULL) ? old('grader_id') : $assignment->grader_id;
//dd($grader_id);
$institution_id = (old('institution_id') != NULL) ? old('institution_id') : $assignment->institution_id; 
$name =  (old('name') != NULL) ? old('name') : $assignment->name;
$description = (old('assignment_text') != NULL) ? old('assignment_text') : $assignment->description; 
$assessment_id = (old('assessment_id') != NULL) ? old('assessment_id') : $assignment->assessment_id; 
$startdatetime =  (old('startdatetime') != NULL) ? old('startdatetime') : $assignment->startdatetime;
$enddatetime = (old('enddatetime') != NULL) ? old('enddatetime') : $assignment->enddatetime; 
$neverexpires = (old('neverexpires') != NULL) ? old('neverexpires') : $assignment->neverexpires; 
$launchtype =  (old('launchtype') != NULL) ? old('launchtype') : $assignment->launchtype;
$proctor_user_id =  (old('proctor_user_id') != NULL) ? old('proctor_user_id') : $assignment->proctor_user_id;
$proctor_instructions =  (old('proctor_instructions') != NULL) ? old('proctor_instructions') : $assignment->proctor_instructions;
$delivery_method =  (old('delivery_method') != NULL) ? old('delivery_method') : $assignment->delivery_method;
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			<?php 
				$operation = ($assignment->id) ? "Update" : "Create";
			?>
				<div class="panel-heading">{{$operation}} Assignment</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/resources/assignmentupdate') }}">
						<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" id="id" value="{{ $id }}">
						<div class="form-group required">
							<label class="col-md-3 control-label">Assignment Name </label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ $name }}">
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label">Description </label>
							<div class="col-md-6">
								<textarea class="form-control" id="assignment_text" name="assignment_text">{{ $description }}</textarea>
							</div>
						</div>


<!-- https://eonasdan.github.io/bootstrap-datetimepicker/#minimum-setup -->
						<div class="form-group required">
							<label class="col-md-3 control-label">Start Date Time </label>
							<div class="col-md-6">
							 <div class='input-group date'>
								<input type="text" class="form-control date" id="startdatetime" name="startdatetime" value="{{ ($startdatetime) ? date($dtFormat, strtotime($startdatetime)) : ''}}">
								<span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
							 </div>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label">End Date Time </label>
							<div class="col-md-6">
							 <div class='input-group date'>
								<input type="text" class="form-control date" id="enddatetime" name="enddatetime" value="{{ ($enddatetime) ? date($dtFormat, strtotime($enddatetime)) : ''}}}}">
								<span class="input-group-addon">
			                        <span class="glyphicon glyphicon-calendar"></span>
			                    </span>
							 </div>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label">Never Expires </label>
							<div class="col-md-6 checkbox">
								<label><input type="checkbox" id="neverexpires" name="neverexpires" value="1" {{ ($neverexpires == 1 ) ? 'checked="checked"' : '' }} ></label>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label" >Institution </label>
							<div class="col-md-6">
								<select class="form-control" name="institution_id" id="institution_id" onchange="getGrader(),getassessment(),getProctor()" >@if(getRole() == "administrator")
									<option value="0">Select</option>
									@endif
									@foreach($institution_arr as $id=>$val)
										<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label" >Assessment</label>
							<div class="col-md-6">
								<select class="form-control" name="assessment_id" id="assessment_id" >
									<option value="0">Select</option>
									@foreach($assessments_arr as $assess_id=>$val)
										<option value="{{ $assess_id }}" {{ ($assess_id == $assessment_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>


						<div class="form-group required">
							<label class="col-md-3 control-label">Launch Type </label>
							<div class="col-md-6">
								<label class="radio-inline"><input type="radio" class="" name="launchtype" id="launchtype_no" value="system" {{ ($launchtype == "system" || $launchtype == "") ? 'checked="checked"' : '' }}> System </label>
								<label class="radio-inline"><input type="radio" name="launchtype" id="launchtype_yes" value="proctor" {{ ($launchtype == "proctor" ) ? 'checked="checked"' : '' }}> Proctor </label>							
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label" >Proctor </label>
							<div class="col-md-6">
								<select class="form-control" id="proctor_id" name="proctor_id" @if($launchtype == "system" || $launchtype == "") disabled @endif>
									<option value="0">Select</option>
									@foreach($grader as $id=>$val)
										<option value="{{ $id }}" {{ ($id == $grader_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3 control-label">Proctor Instructions </label>
							<div class="col-md-6">
								<textarea class="form-control" id="proctor_instructions" name="proctor_instructions"  @if($launchtype == "system" || $launchtype == "") disabled @endif>
									{{ $proctor_instructions }}</textarea>
							</div>
						</div>




						<div class="form-group required">
							<label class="col-md-3 control-label" ></label>
							<div class="col-md-6">
								<div id="dual-list-box" class="form-group row">
						            <select id="student_ids" name="student_ids[]" multiple="multiple" data-title="Students" data-source="{{ url('/user/usersjson') }}" data-value="id" data-text="username" data-horizontal="false">

						            </select>
						        </div>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label" >Grader </label>
							<div class="col-md-6">
								<select class="form-control" id="grader_id" name="grader_id">
									<option value="0">Select</option>
									@foreach($grader as $id=>$val)
										<option value="{{ $id }}" {{ ($id == $grader_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label">Delivary Method </label>
							<div class="col-md-6">
								<label class="radio-inline"><input type="radio" name="delivery_method" id="delivery_method_yes" value="online" {{ ($delivery_method == "online" || $delivery_method == "" ) ? 'checked="checked"' : '' }}> Online </label>
								<label class="radio-inline"><input type="radio" class="" name="delivery_method" id="delivery_method_no" value="print" {{ ($delivery_method == "print") ? 'checked="checked"' : '' }}> Print </label>
							</div>
						</div>

						<!-- <div class="form-group required">
							<label class="col-md-3 control-label">Status</label>
							<div class="col-md-6">
								<input type="radio" class="" name="status" id="status_yes" value="1" {{ ($assignment->status == 1 ) ? 'checked="checked"' : '' }}> Active 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="0" {{ ($assignment->status == 0) ? 'checked="checked"' : '' }}> Inactive 
							</div>
						</div> -->
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">							
								<button type="submit" class="btn btn-primary">Submit</button>
									<a type="Cancel"  class="btn btn-danger"  href="{{  url('/resources/assignment/') }}">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>
<script type="text/javascript">
//var selected = new Array(1,2,3);

 $('#student_ids').DualListBox('');




$(function () {
    $('.date').datetimepicker({format: 'YYYY/MM/DD hh:mm:ss A'});
    //$('#enddatetime').datetimepicker();
});

var dates = $("input[id$='startdatetime'], input[id$='enddatetime']");

$('input:checkbox[name="neverexpires"]').change(
    function(){
    	//alert($(this).is(':checked'))
    	if ($(this).is(':checked')) {
           $('#enddatetime').removeAttr('value');
           $('#enddatetime').val('');
           $('#enddatetime').prop('readonly', true);
           $(this).val(1);
	     }
        else
        {        	
        	$('#enddatetime').prop('readonly', false);  
        	$(this).val(0);
        }
 	});

$('input:radio[name="launchtype"]').change(
    function(){
    	//alert($(this).is(':checked'))
        if ($(this).is(':checked') && $(this).val() == 'system') {
            // append goes here
             $('#proctor_id').val(0);
              $('#proctor_id').prop('disabled', true);
              $('#proctor_instructions').prop('readonly', true);  
          }
        else
        {        	
        	$('#proctor_id').prop('disabled', false);
        	$('#proctor_instructions').prop('readonly', false);
        	$('#proctor_instructions').prop('disabled', false);
        }
    });
	
	$(function () {
		var selected_institution="{{$assignment->institution_id}}";
        $("#institution_id").change(function () {
			if($('#institution_id').val()==selected_institution){
				loadunselectedusers({{$assignment->id}});
				loadselectedusers();
			}
			else {
				loadunselectedusers(0);
			}
        });

<?php   if($assignment->id > 0){ ?>
  		
  		loadunselectedusers({{$assignment->id}});
   		loadselectedusers();	

<?php   } else {    ?>   
		
		loadunselectedusers(0);

<?php   }     
		if($neverexpires == 1 ){ ?> 
		$('#enddatetime').removeAttr('value');
		$('#enddatetime').val('');
		$('#enddatetime').prop('readonly', true);

<?php   }     ?>
$('#grader_id').val("{{$grader_id}}"); 

        function loadunselectedusers(id)
        {
       		//usersListChange(); //refer to function stated in the original question

       		if(id > 0)
       		{
       			var loadurl = "{{ url('/resources/unassignedusersjson') }}" ;//+ $(this).val(); 
       			var postdata = { institution_id: $("#institution_id").val(),assignment_id: $("#id").val() };
       		}
       		else
       		{
       			var loadurl = "{{ url('/user/usersjson') }}" ;//+ $(this).val(); 
       			var postdata = { institution_id: $("#institution_id").val() };
       		}
            
            console.log(loadurl);
			//unselected
			//$('#student_ids').attr('data-source', loadurl);
			var html = '';
			$(".unselected, .selected").html('');
			$.getJSON(loadurl, postdata, function (data) {
                var items;
                $.each(data, function (i, item) {
                    $(".unselected").append("<option value=" + item.id + ">" + item.username + "</option>");
                });
                $(".unselected-count").html(data.length);
                $(".atr, str").prop('disabled', false);              
            });
       }
       function loadselectedusers()
       {
       	
       		var loadurl = "{{ url('/resources/assignedusersjson') }}" ;//+ $(this).val(); 
            console.log(loadurl);
			//unselected
			//$('#student_ids').attr('data-source', loadurl);
			var html = '';
			$(".selected").html('');
			$.getJSON(loadurl,{ assignment_id: $("#id").val() }, function (data) {
                var items;
                $.each(data, function (i, item) {
                    $(".selected").append("<option value=" + item.id + ">" + item.username + "</option>");
                });
                $(".selected-count").html(data.length);
                $(".atl, stl").prop('disabled', false);              
            });
       }
    });

	function getGrader()
	{
		var csrf=$('Input#csrf_token').val();
		var loadurl = "{{ url('/resources/assignment') }}/" ;
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:loadurl+$('#institution_id').val(),
					type:'get',
					success:function(response) {
						var a = response.length;
						$('#grader_id').empty();
						var opt = new Option('--Select Grader--', '0');
						$('#grader_id').append(opt);
						for (i = 0; i < a; i++) {
							var opt = new Option(response[i].name, response[i].id);
							$('#grader_id').append(opt);
						}
					}
				}
		)

	}


function getProctor()
{
	var csrf=$('Input#csrf_token').val();
	var loadurl = "{{ url('/resources/assignment') }}/" ;
	$.ajax(
			{

				headers: {"X-CSRF-Token": csrf},
				url:loadurl+$('#institution_id').val(),
				type:'get',
				success:function(response) {
					var a = response.length;
					$('#proctor_id').empty();
					var opt = new Option('--Select Proctor--', '0');
					$('#proctor_id').append(opt);
					for (i = 0; i < a; i++) {
						var opt = new Option(response[i].name, response[i].id);
						$('#proctor_id').append(opt);
					}
				}
			}
	)

}
	function getassessment()
	{
		var csrf=$('Input#csrf_token').val();
		var loadurl = "{{ url('/resources/assignment/getassessment') }}/" ;
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:loadurl+$('#institution_id').val(),
					type:'get',
					success:function(response) {
						var a = response.length;
						$('#assessment_id').empty();
						var opt = new Option('--Select Assessment--', '');
						$('#assessment_id').append(opt);
						for (i = 0; i < a; i++) {
							var opt = new Option(response[i].name, response[i].id);
							$('#assessment_id').append(opt);
						}
					}
				}
		)

	}
  </script>
@endsection
