@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('assets/js/common.js')) !!}
{!! HTML::script(asset('plugins/tinymce/plugins/tiny_mce_wiris/core/display.js')) !!}
{!! HTML::script(asset('plugins/tinymce/tinymce.min.js')) !!}
{!! HTML::script(asset('assets/js/bootstrap-checkbox.min.js')) !!}
@stop
<style>
	.fancybox-overlay{z-index: 99999 !important;}
	#fancybox-loading{z-index: 99999 !important;}

</style>
@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Question Details</div>
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

					<form class="form-horizontal" name="qst_form" id="qst_form" role="form" method="POST" action="{{ url('/resources/questionupdate') }}">
						<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="{{ $id }}">
						<div class="form-group required">
							<label class="col-md-2 control-label">Institution</label>
							<div class="col-md-10">
								<select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">
									<option value="0">--Select Institution--</option>
									@foreach($inst_arr as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-2 control-label">Category</label>
							<div class="col-md-10">
								<select class="form-control" name="category_id" id="category_id" onchange="change_category()">
									<option value="0">--Select Category--</option>
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-2 control-label">Subject</label>
							<div class="col-md-10">
								<select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">
									<option value="0">--Select Subject--</option>
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-2 control-label">Lessons</label>
							<div class="col-md-10">
								<select class="form-control" name="lessons_id" id="lessons_id" onchange="change_passage()">
									<option value="0">--Select Lessons--</option>
								</select>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-2 control-label">Question Type</label>
							<div class="col-md-10">
								<select class="form-control" name="question_type" id="question_type">
									<option value="0">--Select Question Type--</option>
									@foreach($qtypes as $id=>$val)
									<option value="{{ $id }}" {{ ($id == $subject_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option> 
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Passage</label>
							<div class="col-md-10">
								<select class="form-control" name="passage" id="passage">
									<option value="0">--Select Passage--</option>
									
								</select>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-2 control-label">Question Title</label>
							<div class="col-md-10">
								<input type="text" class="form-control" name="question_title" id="question_title" value="{{ $name }}">
							</div>
						</div>
						<div class="form-group required">
							<label class="col-md-2 control-label">Question Text</label>
							<div class="col-md-10">
								<textarea class="form-control" id="question_textarea" name="question_textarea"></textarea>
							</div>
						</div>

						<div class="form-group required">
							<label class="col-md-3 control-label">Status</label>
							<div class="col-md-6">
								<input type="radio" class="" name="status" id="status_yes" value="1" checked="checked" > Active
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="0" > Inactive
							</div>
						</div>
						<?php $path = url()."/resources/"; ?>	
						<input type="hidden" value="<?php echo $path?>" id="base_path">	 
						<div class="form-group">
						<p class="mb18 mr0">
			            	<label class="mr20 mt8 w200"></label>
			            </p>
			            <div id="answer_append">
						    <p class="w815 fltL answer_add">
 						            <a href="javascript:void(0)" class="upload_btn clr btn btn-primary col-md-offset-10 create_answer">Add New Answer</a>
 						    </p>

			            </div>
			            <input type="hidden" name="ans_flg" id="ans_flg" value="0">
			             @if($question_type == "Fill in the blank")
				                <script>
				                $('#ans_flg').val(1);
				                $('.create_answer').hide();
				                </script>
			                @endif	
						<div class="clr"></div>
						<div class="answers mt20 col-md-12">
							@if (isset($answersListing) && !empty($answersListing))
								{!! $answersListing !!}
							@endif
						</div>

						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-6">
								<button type="submit" class="btn btn-primary">
									Submit
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$('#question_type').change(function () {
		var tr;
		var question_type=$('#question_type').val(); 
		var question_type_text=$('#question_type').find('option:selected').text(); 
   		if(question_type_text=="Essay"){
			// $(".answer_add").remove(); 
			  $(".create_answer").hide(); 
			  $(".answers").children().each(function(){
			  	$(this).remove();
			  });
 		}
 		else if(question_type_text=="Fill in the blank"){
			$(".create_answer").hide(); 
			  $(".answers").children().each(function(){
			  	$(this).remove();
			  });
			 // var answer=document.createElement('textarea');
			 // answer.className("")
			  var temp="<div class='answer_container mb40'>" +
                    "<div class='mb18 mr10 mt20 pos_rel'>" +
                    "<div class='col-md-2'><label class='mr20 mt8 w200 question_answer_count control-label'>Answer<i>*</i></label>" +
                    "<input type='hidden' name='answerIds[]' class='hanswerId' value=''>" +
                    "<input type='hidden' name='is_correct[]' id='is_correct[]' value='true'/>" +
                    // "<i class='switch_off icons L0 correct' data-answer_selection=''></i>" + 
                    "</div><div class='col-md-10'><p style='w93 fltL'>" +
                    "<textarea name='answer_textarea[]' id='answer_textarea' class='required w722 hgt125 create_inpt alphanumeric' data-type='tinymce' data-name='Answer Text' data-read_only='false'></textarea>" +
                    "<div class='clr'></div>" +
                    "</p></div>" +
                    "<div class='clr'></div>" +
                    "</div>"+
                    "<div class='mb18 mr10 mt20'><div class='col-md-2'>" +
                    "<label class='mr20 mt8 w200'>Explanation</label></div>" +
                    "<div class='col-md-10'><div class='w742 fltL'>" +
                    "<textarea name='explanation[]' class='textarea textarea_explanation w722 hgt125 create_inpt alphanumeric' maxlength='1500'></textarea>" +
                    "<div class='clr'></div></div>" +
                 //   "<p class='exp_links Lht30 mt15 mr0 fltR'><i class='del icons mr10 delBtn' id='del_" + randomId + "'></i> Delete</p>" +
                  //  "<p class='exp_links mt20 mr30 fltR'><i class='" + moveDir + " icons mr20 upDownBtn'></i> Move " + (moveDir[0].toUpperCase() + moveDir.slice(1)) + "</p>" +                    "<div class='clr'></div>" +
                    "</div></div>" ;
						temp.toString();
                     $(temp).appendTo('.answers');
                     $('#ans_flg').val(1);
			//  $('.answers').append(answer);
 		}
		else{
			 $(".create_answer").show(); 
			 $(".answers").children().each(function(){
			  	$(this).remove();
			  });
			//var c=$("#answer_append").is("create_answer");
//    			if($('#answer_append .answer_add').length > 0){
//   			}else{
//   				var p=document.createElement("p");
//   				p.className='w815 fltL answer_add';
//   				var a=document.createElement("a");
//   				a.setAttribute('href','javascript:void(0)');
//   				var txt = document.createTextNode("Add New Answer");
//   				a.appendC(txt);
//   				a.className="upload_btn clr btn btn-primary col-md-offset-10 create_answer";
//   				p.appendChild(a);
//   				$('#answer_append').append(p);
// //  			$('#answer_append').append("<p class='w815 fltL answer_add'><a href='javascript:void(0)' class='upload_btn clr btn btn-primary col-md-offset-10 create_answer'>Add New Answer</a></p>");
// //			$('#answer_append').append("<a href='javascript:void(0)' class='upload_btn clr btn btn-primary col-md-offset-10 create_answer'>Add New Answer</a>"); 
// //			$('#answer_append').append("</p>");
//  			}
 		}
});
	var elfinderRoute = '{{route('elfinder.tinymce4')}}';
	var fileBrowser = '{{route('launchFileBrowser',['question_attachments'])}}';
	var js = document.createElement("script");
	js.type = "text/javascript";
	js.src = "{{ asset('plugins/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=mathml') }}";
	document.head.appendChild(js);
</script>
    {!! HTML::script(asset('js/custom/question.js')) !!}
@include('resources::question.qst_js_validation')
@endsection