@extends('default')
@section('content')
<div class="container">
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<ul class="nav nav-tabs" role="tablist">
			    <li><a href="{{ url('/resources/category') }}">Category</a></li>
		        <li><a href="{{ url('/resources/subject') }}">Subjects</a></li>
		        <li><a href="{{ url('/resources/lesson') }}">Lessons</a></li>
		        <li class="active"><a href="{{ url('/resources/question') }}">Questions</a></li>
		        <li><a href="{{ url('/resources/passage') }}">Passages</a></li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">&nbsp;<!-- Lessons -->
					<a href="{{ url('/resources/questionadd/') }}" class="btn btn-primary btn-sm right" ><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
					<a href="{{ route('questionBulkUpload') }}" class="btn btn-primary btn-sm right fancybox fancybox.ajax"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> Bulk Upload </a>	
				</div>
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
				<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
				
				<div class="panel-body">

				<div class="panel panel-default">

					<div class="panel-heading searchfilter pointer"  id="clear">Advanced Filters
						<a href="javascript:;"><span class="glyphicon glyphicon-chevron-up right " aria-hidden="true"></span></a>
					</div>
	
					<div class="panel-body searchfilter-body hide">

					<div class="form-group col-md-6 required">
						<label class="col-md-4 control-label">Institution</label>
						<div class="col-md-6">
							<select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">
								<option value="0">--Select Institution--</option>
								@foreach($inst_arr as $id=>$val)
								<option name="institution_id" value="{{$id}}">{{ $val }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-md-6 required">
						<label class="col-md-4 control-label">Category</label>
						<div class="col-md-6">
							<select class="form-control" name="category_id" id="category_id" onchange="change_category()">
								<option value="0">--Select Category--</option>
								
							</select>
						</div>
					</div>
					<div class="form-group col-md-6 required">
						<label class="col-md-4 control-label">Subject</label>
						<div class="col-md-6">
							<select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">
								<option value="0">--Select Subject--</option>
								
							</select>
						</div>
					</div>
					<div class="form-group col-md-6 required">
						<label class="col-md-4 control-label">Lessons</label>
						<div class="col-md-6">
							<select class="form-control" name="lessons_id" id="lessons_id" onchange="change_question_type()">
								<option value="0">--Select Lesson--</option>
								
							</select>
						</div>
					</div>
					<div class="form-group col-md-6 required">
						<label class="col-md-4 control-label">Question Type</label>
						<div class="col-md-6">
<<<<<<< HEAD
						 <select class="form-control" name="question_type" id="question_type" >
=======
						 <select class="form-control" name="question_type" id="question_type">
>>>>>>> 73836f4f423f317833ded122e9643453f941d870
								<option value="0">Select Question Type</option>
		
					     </select>

					</div>
					<div class="form-group col-md-10">
					</div>

					<div class="form-group col-md-2">
						<div class="col-md-6">
							<div class="move-arrow-box">
<<<<<<< HEAD
								<a class="btn btn-primary" onclick="filter();" href="javascript:;">Go</a>
=======
								<a class="btn btn-primary" onchange="filter();" href="javascript:;" onclick="question_change()">Go</a>
>>>>>>> 73836f4f423f317833ded122e9643453f941d870
							</div>
						</div>
					</div>
					</div>
				</div>
					
					<div class="clearfix"></div>
					<div>
		@if(Session::has('flash_message'))
			<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! Session('flash_message') !!}</em></div>
		@endif
	</div>
	<div>
		@if(Session::has('flash_message_failed'))
			<div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
		@endif
	</div>	
					<table id="example" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Question Title</th>
				                <th>Question Type</th>
				                 <th>Question Passage</th>
								<th style="visibility: hidden;"></th>
				            </tr>
				        </thead>
				        <tbody id="question_list_filer">
				            @foreach( $list as $id => $value )
				            <tr>
				                <td>{{ $value['question_title'] }}</td>
				                 <td>{{ $value['question_type'] }}</td>
								 <td>{{ $value['passage_title'] }}</td>
				                <td>
									<a href="{{ url('/resources/questionview/'.$value['qid']) }}"  class="btn btn-default btn-sm" title="Details" ><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
				                	<a href="{{ url('/resources/questionedit/'.$value['qid']) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
									<a href="javascript:;" data-ref="{{ url('/resources/questiondel/'.$value['qid']) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
								</td>
				            </tr>
				            @endforeach
				        </tbody>
				    </table>
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
{!! HTML::script(asset('/js/custom/confirm.js')) !!}
<?php
$path = url()."/resources/";
?>
<script>
	function filter(){
		alert('hi');
		var csrf=$('Input#csrf_token').val();
		var institution_id=$('#institution_id').val();
		var category_id=$('#category_id').val();
		var subject_id=$('#subject_id').val();
		var lessons_id=$('#lessons_id').val();
		var question_type=$('#question_type').val();
		if(subject_id=='')subject_id=0;
		if(institution_id=='')institution_id=0;
		if(category_id=='')category_id=0;
		if(lessons_id=='')lessons_id=0;
		if(question_type=='')question_type=0;
		var data={'institution':institution_id,'category':category_id,'subject':subject_id,'lessons':lessons_id,'question_type':question_type};
		var url="question_list_filer";
		ajax(url,data,csrf);
	}
	function ajax(url,data,csrf){
		$.ajax(
				{
					url:url,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data,
					success:function(response){
						$('#question_list_filer').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr/>');
							tr.append("<td>" + response[i].title + "");
							tr.append("<a href='assessmentedit/"+ response[i].id +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>");
							tr.append("<a href='assessmentdel/"+ response[i].id +"' class='btn btn-default btn-sm'><span class='glyphicon glyphicon-trash'' aria-hidden='true'></span></a></td>");
							$('#question_list_filer').append(tr);
						}
					}
				}
		);
	}

$( document ).ready(function() {
	$('.searchfilter').on("click",function(e){    	
    	//console.log('searchfilter ');
    	$(".searchfilter span")
        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
        $('.searchfilter-body').toggleClass('hide show');
    });
});    

	function change_institution(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}categoryList/'+$('#institution_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
							$('#category_id').empty();
							$('#subject_id').empty();
							$('#lessons_id').empty();
							var opt = new Option('--Select Category--', '');
							//opt.addClass('selected','disabled','hidden');
							$('#category_id').append(opt);
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);
								$('#category_id').append(opt);
							}
						}
					});
	}

	function change_category(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}subjectList/'+$('#category_id').val(),
					type:'post',
					success:function(response) {
						var a = response.length;
							$('#subject_id').empty();
							$('#lessons_id').empty();
							var opt = new Option('--Select Subject--', '');
							$('#subject_id').append(opt);
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);
								$('#subject_id').append(opt);
							}
						}
					});
		
	}

	function change_lessons(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}lessonsList/'+$('#subject_id').val(),
					type:'post',
					success:function(response){
						var a=response.length	
							$('#lessons_id').empty();
							$('#question_type').empty();
							var opt=new Option('--Select Lesson--','');
							$('#lessons_id').append(opt);
							for(i=0;i<a;i++){
								var opt=new Option(response[i].name,response[i].id);
								$('#lessons_id').append(opt);
							}
						}
					});

		
	}

	function change_question_type(){
		var csrf=$('Input#csrf_token').val();
		passage_Ids=[];
		var passage_id=document.getElementsByName('passageIds[]');
		for (var i = 0; i < passage_id.length; i++) {
			passage_Ids.push(passage_id[i].value);
		}
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}questiontypeList/'+$('#lessons_id').val(),
					type:'post',
					data:{'passages':passage_Ids},
					success:function(response){
						var a=response['question_type'].length;
						//$('#question_type').multiselect('destroy');
						$('#question_type').empty();
						$('#passage_table').dataTable().fnDestroy();
						$('#passages-list').empty();

						//$('#question_type').multiselect();
						var opt=new Option('Select QuestionType','');
						$('#question_type').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response['question_type'][i].qst_type_text,response['question_type'][i].question_type);
							$('#question_type').append(opt);
						}
						$.each(response['passages'],function(index,val){
							//alert('enter');

							tr = $('<tr/>');
							tr.append("<td><input type='checkbox' value='"+val['pid']+"' class='assess_qst check-passage' data-group-cls='btn-group-sm'></td>");
//							tr.append("<input type='hidden' value='"+response[i].id+"' name='QuestionIds[]' id='QuestionIds'>");
							tr.append("<td>" + val['passage_title'] + "</td>");
							$('#passages-list').append(tr);
						});
						//$('#question_table').dataTable();
						$('#passage_table').dataTable();
						/*$('#question_type').multiselect();
						 $('#question_type').multiselect('refresh');*/
					}
				}
		)
	}
	/*$('#clear').click(function(){
		$("#institution_id").val($("#institution_id option:first").val());
		$("#category_id").val($("#category_id option:first").val());
		$("#subject_id").val($("#subject_id option:first").val());
		$("#lessons_id").val($("#lessons_id option:first").val());
		$("#question_type").val($("#question_type option:first").val());
		// $('#question_list_filer').empty(); 
	}); */
</script>
<script type="text/javascript">
 function question_change(){
            if($('#institution_id').val()==0 || ($('#category_id').val()==0 || ($('#subject_id').val()==0 || ($('#lessons_id').val()==0 || ($('#question_type').val()==0))))){
                alert("please select all the fields");
            }
    }
    </script>
@endsection