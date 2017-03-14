<?php
$path = url()."/resources/";?>
<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
<?php
if (count($errors) > 0){?>
<script>
	var oldvalues = '{{old('institution_id')}}';
	var catoldvalues = '{{old('category_id')}}';
	var suboldvalues = '{{old('subject_id')}}';
	console.log(suboldvalues);
	var lessonoldvalues = '{{old('lessons_id')}}';
 	var question_type = '{{old('question_type')}}';
 	var question_title = '{{old('question_title')}}';
	var question_textarea = '{{old('question_textarea')}}';
	var passage = '{{old('passage')}}';
	var answers='{{old('answers')}}';
	var answer_textarea=$('#answerIds').val();
	//alert(answer_textarea);
	function htmlDecode(value) {
		return $("<textarea/>").html(value).text();
	};
	$('#institution_id').val(oldvalues);
	if(oldvalues!=null){
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
						var opt=new Option('--Select --','');
						$('#category_id').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#category_id').append(opt);
						}

						//category
						if(catoldvalues!=null)
						{
							$('#category_id').val(catoldvalues);
							$.ajax(
									{

										headers: {"X-CSRF-Token": csrf},
										url:'{{$path}}subjectList/'+$('#category_id').val(),
										type:'post',
										success:function(response){
											var a=response.length;
											$('#subject_id').empty();
											$('#lessons_id').empty();
											var opt=new Option('--Select Subject--','');
					 						$('#subject_id').append(opt);
											for(i=0;i<a;i++){
												var opt=new Option(response[i].name,response[i].id);
												$('#subject_id').append(opt);
											}

											//sub
											if(suboldvalues!=null){
												$('#subject_id').val(suboldvalues);
												$.ajax(
														{
															headers: {"X-CSRF-Token": csrf},
															url:'{{$path}}lessonsList/'+$('#subject_id').val(),
															type:'post',
															success:function(response){
																var a=response.length;
																$('#lessons_id').empty();
																var opt=new Option('--Select Lesson--','');
																$('#lessons_id').append(opt);
																for(i=0;i<a;i++){
																	var opt=new Option(response[i].name,response[i].id);
																	$('#lessons_id').append(opt);
																}
																$('#lessons_id').val(lessonoldvalues);
															}
														}
												)
											}//sub end
										}
									}
							)
						}//end category
						

					}
				}
		)
		
		
		$('#question_type').val(question_type);		
		$('#question_title').val(question_title);
 		$("#question_textarea")
				.text(htmlDecode(question_textarea));
		
		$('#passage').val(passage);
	
  	}

</script>
<?php }?>
<script>
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
							var opt=new Option('--Select Category--','');
							//opt.addClass('selected','disabled','hidden');
							$('#category_id').append(opt);
 							for(i=0;i<a;i++){
								var opt=new Option(response[i].name,response[i].id);
								$('#category_id').append(opt);
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
					url:'{{$path}}subjectList/'+$('#category_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#subject_id').empty();
						$('#lessons_id').empty();
						var opt=new Option('--Select Subject--','');
 						$('#subject_id').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#subject_id').append(opt);
						}
					}
				}
		)
	}
	function change_lessons(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}lessonsList/'+$('#subject_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#lessons_id').empty();
						var opt=new Option('--Select Lesson--','');
						$('#lessons_id').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#lessons_id').append(opt);
						}
					}
				}
		)
	}
		function change_passage(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}passageList/'+$('#lessons_id').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#passage').empty();
						var opt=new Option('--Select Passage--','');
						$('#passage').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].title,response[i].id);
							$('#passage').append(opt);
						}
					}
				}
		)
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
						var opt=new Option('--Select QuestionType--','');
						$('#question_type').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response['question_type'][i].qst_type_text,response['question_type'][i].question_type_id);
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
	function popup(){
		var institution_id=$('#institution_id').val();
		var category_id=$('#category_id').val();
		var subject_id=$('#subject_id').val();
		var lessons_id=$('#lessons_id').val();
		var passage_text=$('#passagetext').val();
		var passage_title=$('#passage_title').val();
		var passage_lines=$('#passagelines').val();
		var csrf=$('Input#csrf_token').val(); 
		if(subject_id=='')subject_id=0;
		if(institution_id=='')institution_id=0;
		if(category_id=='')category_id=0;
		if(lessons_id=='')lessons_id=0;
        if(passage_text=='')passage_text=0;
		if(passage_title=='')passage_title=0;
		if(passage_lines=='')passage_lines=0;
		//alert(passage_text);
		var data={'institution':institution_id,'category':category_id,'subject':subject_id,'lessons':lessons_id,'passagetext':passage_text,'passagelines':passage_lines,'passagetitle':passage_title};
		//alert(data);
		var url='{{$path}}passagepopup';
		$.ajax(
				{
					url:url,
					headers: {"X-CSRF-Token": csrf},
					type:"get",
					data:data,
					success:function(response){

						$.fancybox(response, {
                type: "html"
            }); 
				}
			}
		);
	}

	/*$(document).ready(function() {	
		 $('.ans-chk').checkboxpicker({
		  html: true,
		  offLabel: '<span class="glyphicon glyphicon-remove">',
		  onLabel: '<span class="glyphicon glyphicon-ok">'
		});

		$('.searchfilter').on("click",function(e){    
			//console.log('searchfilter ');
	    	$(".searchfilter span")
	        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
	        $('.searchfilter-body').toggleClass('hide show');
	    });

	} );	
*/
</script>