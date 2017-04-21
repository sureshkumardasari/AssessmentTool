<?php
$path = url()."/resources/";
?>
<script>
	remove_passage_ids=[];
	//var state=0;
	function filter(flg=0){
		question_Ids=[];
		passage_Ids=[];
		var csrf=$('Input#csrf_token').val();
		var question_id=document.getElementsByName('QuestionIds[]');
		for (var i = 0; i < question_id.length; i++) {
			question_Ids.push(question_id[i].value);
		}
		var passage_id=document.getElementsByName('passageIds[]');
		for (var i = 0; i < passage_id.length; i++) {
			passage_Ids.push(passage_id[i].value);
		}
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
		if(question_id=='')question_id=0;
		var data={'institution':institution_id,'category':category_id,'subject':subject_id,'lessons':lessons_id,'questions':question_Ids,'passages':passage_Ids,'question_type':question_type};
		var url='{{$path}}filter_data_assessment';
		if(flg!=1){
			ajax(url,data,csrf);

		}
		//state=1;

		if(flg=='1'){
			var data2={'questions':question_Ids};
			var data3={'passages':passage_Ids};
			//alert(JSON.stringify(data2));
			var url2='{{$path}}get_assessment_qst';
			var url3='{{$path}}get_assessmentold_pass';
			if(question_Ids.length != 0){
				selected_qst_ajax(url2,data2,csrf);
				if(passage_Ids.length !=0){
					selected_old_pass_ajax(url3,data3,csrf);
				}

			}


			var url4='{{$path}}get_assessment_remove_old_pass';
			var data4={'passages':passage_Ids};
			// selected_remove_old_pass_ajax(url4,data4,csrf);

			//desible show entires & pagination in datatables
			
				


		}

		

	}

	function ajax(url,data,csrf){
		$.ajax(
				{
					url:url,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data,
					success:function(response){
						
						$('#questions-list').empty();

						var tr;
						for (var i = 0; i < response['questions'].length; i++) {
							tr = $('<tr/>');
							tr.append("<td><input type='checkbox' value='"+response['questions'][i].qid+"' subject_id="+response['questions'][i].sub_id+" lesson_id="+ response['questions'][i].less_id +" passage_id="+response['questions'][i].pid +" class='assess_qst check-question' data-group-cls='btn-group-sm'></td>");
//							tr.append("<input type='hidden' value='"+response[i].id+"' name='QuestionIds[]' id='QuestionIds'>");
							tr.append("<td>" + response['questions'][i].question_title + "</td>");
							$('#questions-list').append(tr);
//							if(){
//
//							}
//alert('test');
 						
						}
						
						//desible show entires & pagination in datatables
						$('#QuestionIds').prop('checked', false);
						$('.test input[type="checkbox"]').on('change', function () 
					    {
					            var allChecked = $(' .test input:checked').length === $(' .test input').length;
					            $('#QuestionIds').prop('checked', allChecked);
					    });

					}

				}

		);

	}

	function selected_qst_ajax(url,data,csrf){
		$.ajax(
				{
					url:url,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data,
					success:function(response){
						$('#selected-questions'+' .child-grid').empty();
						var tr;
						subjects_list=[];
						lessons_list=[];
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr/>');
							tr.append("<td><input  class='assess_qst check-selected-question' type='checkbox' passage_id="+ response[i].passage_id+" subject_id='"+response[i].subject_id + "' lesson_id="+ response[i].lesson_id +" value='"+response[i].id+"'></td>");
							tr.append("<td>" + response[i].title + "</td>");
							// tr.append("<td>" + response[i].title +'<input type="hidden" name="QuestionIds[]" id="" value="'+response[i].id+'">'+ "</td>");
							$('#questions-list').append(tr);
							$('#selected-questions'+' .child-grid').append(tr);
							var value=response[i].id;
							var subject_id=response[i].subject_id;
							var lesson_id=response[i].lesson_id;
							var passage_id=response[i].passage_id;
							if($.inArray(subject_id,subjects_list)==-1){
								subjects_list.push(subject_id);
							}
							if($.inArray(lesson_id,lessons_list)==-1){
								lessons_list.push(lesson_id);
							}

							if( passage_id != "null" && passage_id != null && passage_id != undefined && passage_id !=0 ) {
								passage_id=passage_id.toString();
								if ($.inArray(passage_id, selected_passage_ids) == -1) {
									selected_passage_ids.push(passage_id);
								}
								if (passage_id in selected_question_ids) {
									selected_question_ids[passage_id].push(value.toString());
								}
								else {
									selected_question_ids[passage_id] = [];
									selected_question_ids[passage_id].push(value.toString());
								}
							}

						}
						//desible show entires & pagination in datatables
						

					}
				}
		);
	}
	function selected_old_pass_ajax(url3,data3,csrf){
		$.ajax(
				{
					url:url3,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data3,
					success:function(response){
						
						$('#selected-passage'+' .child-grid').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr id=passage_'+response[i].id+'/>');
							tr.append("<td><input type='checkbox' value='"+ response[i].id +"' id='passages-list' class='assess_qst check-selected-passage' data-group-cls='btn-group-sm' name='passage[]'></td>");
							tr.append("<td>" + response[i].title + "</td>");
							$('#selected-passage'+' .child-grid').append(tr);
							remove_passage_ids.push(""+ response[i].id +"");
							if ($.inArray(response[i].id, selected_passage_ids) == -1)
							{
								selected_passage_ids.push(response[i].id);
							}
						}
						//
						url4='{{$path}}get_assessment_remove_old_pass';
						data4={'passages':remove_passage_ids};
						csrfT=$('Input#csrf_token').val();
						
					}
				}
		);
	}

	var url4='{{$path}}get_assessment_remove_old_pass';
	var data4={'passage':remove_passage_ids};
	function selected_remove_old_pass_ajax(url4,data4,csrf){
		$.ajax(
				{
					url:url4,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data4,
					success:function(response){
						$('#passages-list').empty();
						// $('#selected-passage'+' .child-grid').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr/>');
							tr.append("<td><input type='checkbox' value='"+ response[i].id +"' id='passages-list'  class='assess_qst check-passage' data-group-cls='btn-group-sm' name='passage[]'></td>");
							tr.append("<td>" + response[i].title + "</td>");
							// $('#passages-list'+' .child-grid').append(tr);
							$('#passages-list').append(tr);

						}
					}
				}
		);
	}
</script>
<?php
if (count($errors) > 0){?>
<script>
	var oldvalues = '{{old('institution_id')}}';

	var catoldvalues = '{{old('category_id')}}';
	{{--var suboldvalues = '{{old('subject_id[]')}}';--}}
	{{--var lessonoldvalues = '{{old('lessons_id[]')}}';--}}
	{{--var question_type = '{{old('question_type')}}';--}}
	{{--var question_textarea = '{{old('question_textarea')}}';--}}
	var passage = '{{old('passage')}}';
	var QuestionIds=$('#QuestionIds').val();
	var passageIds=$('#passageIds').val();
	//var Question_ids=[];
	//var Passage_ids=[];
	//state=0;
	filter('1');
	//addOrRemoveInGrid('', "add");
	$('#institution_id').val(oldvalues);
	state=1;
	if(oldvalues!=null) {
		var csrf = $('Input#csrf_token').val();
		$.ajax(
				{
					headers: {"X-CSRF-Token": csrf},
					url: '{{$path}}categoryList/' + $('#institution_id').val(),
					type: 'post',
					success: function (response) {
						var a = response.length;
						$('#category_id').empty();
						var opt = new Option('--Select Category--', '');
						$('#category_id').append(opt);
						for (i = 0; i < a; i++) {
							var opt = new Option(response[i].name, response[i].id);
							$('#category_id').append(opt);
						}
						if (catoldvalues != null) {
							$('#category_id').val(catoldvalues);
						}

						//category
						if (catoldvalues != null) {
							$('#category_id').val(catoldvalues);
							change_category("question");
							change_institute=2;
							change_categry=2;
							//alert(JSON.stringify(subjects_list));
							//alert(JSON.stringify(lessons_list));

							{{--$.ajax(--}}
									{{--{--}}

										{{--headers: {"X-CSRF-Token": csrf},--}}
										{{--url: '{{$path}}subjectList/' + $('#category_id').val(),--}}
										{{--type: 'post',--}}
										{{--success: function (response) {--}}
											{{--var a = response.length;--}}
											{{--$('#subject_id').empty();--}}
{{--//											var opt=new Option('--Select Subject--','');--}}
{{--//											$('#subject_id').append(opt);--}}
											{{--for (i = 0; i < a; i++) {--}}
												{{--var opt = new Option(response[i].name, response[i].id);--}}
												{{--$('#subject_id').append(opt);--}}
											{{--}--}}
											{{--$('#subject_id').multiselect();--}}
											{{--//sub--}}
{{--//											if (suboldvalues != null) {--}}
{{--//												$('#subject_id').val(suboldvalues);--}}
{{--//												$('#subject_id').multiselect();--}}
{{--//											}--}}
										{{--}--}}
									{{--}--}}
								{{--);--}}
						}
					}
				//}
			//);
	//	}
												{{--$.ajax(--}}
														{{--{--}}
															{{--headers: {"X-CSRF-Token": csrf},--}}
															{{--url:'{{$path}}lessonsList/'+$('#subject_id').val(),--}}
															{{--type:'post',--}}
															{{--success:function(response){--}}
																{{--var a=response.length;--}}
																{{--$('#lessons_id').empty();--}}
{{--//																var opt=new Option('--Select Lesson--','');--}}
{{--//																$('#lessons_id').append(opt);--}}
																{{--for(i=0;i<a;i++){--}}
																	{{--var opt=new Option(response[i].name,response[i].id);--}}
																	{{--$('#lessons_id').append(opt);--}}
																{{--}--}}
																{{--$('#lessons_id').val(lessonoldvalues);--}}
																{{--$('#lessons_id').multiselect();--}}
															{{--}--}}
														{{--}--}}
												{{--)--}}
												{{--if(lessonoldvalues!=null){--}}
													{{--$('#lessons_id').val(lessonoldvalues);--}}
													{{--$.ajax(--}}
															{{--{--}}
																{{--headers: {"X-CSRF-Token": csrf},--}}
																{{--url:'{{$path}}lessonsList/'+$('#lessons_id').val(),--}}
																{{--type:'post',--}}
																{{--success:function(response){--}}
																	{{--var a=response.length;--}}
																	{{--$('#question_type').empty();--}}
																	{{--var opt=new Option('--Select QuestionType--','');--}}
																	{{--$('#question_type').append(opt);--}}
																	{{--for(i=0;i<a;i++){--}}
																		{{--var opt=new Option(response[i].qst_type_text,response[i].question_type_id);--}}
																		{{--$('#question_type').append(opt);--}}
																	{{--}--}}
																	{{--$('#question_type').val(question_type);--}}
																{{--}--}}
															{{--}--}}
													{{--)--}}
												{{--}//sub end--}}
											{{--}--}}
										{{--});--}}
						{{--}//end category--}}


					//}
				}
		);


//		$('#question_type').val(question_type);
//		$('#question_textarea').val(question_textarea);
//		$('#passage').val(passage);
	}

</script>
<?php }?>

<script>
	//var change_institute=1;
	//var change_categry=1;
	$('#clear_filters').on('click',function(){
		clear_selected();


	});
	function clear_selected(){
		

		
		/*$('#questions-list').empty();
		$('#selected-questions .child-grid').empty();
		$('#passages-list').empty();
		$('#passages-list').empty();
		$('#selected-passage .child-grid').empty();
		$('#subjects_list').empty();
		$('#lessons_list').empty();*/
		$('#question_type').val('');
		$('#institution_id').val('');
		$('#category_id').val('');		
		$('.multipleSelect').val('');
		$('#subject_id').multiselect('refresh');
		$('#lessons_id').multiselect('refresh');
		var myForm = document.forms.assessment_form;
		var question_id = myForm.elements['QuestionIds[]'];
		if(question_id) {
			$.each(question_id,function(){
				//alert($(this).val());
				$(this).val('0');
			});
			//var a=question_id.value;
//			if(question_id.length == undefined){
//				question_Ids.push(a);
//			}
//			else{
//				for (var i = 0; i < question_id.length; i++) {
//					question_Ids.push(question_id[i].value);
//					// alert("kjhbjhhjkbjbkjbkj");
//					// alert(question_id[i].value);
//					//question_id[i].val('');
//					//if (question_id[i].value == $(this).val()) {
//					//    question_id[i].value = '';
//					//}
//				}
//			}

		}
				subjects_list=[];
		lessons_list=[];
	}

	function change_institution(type){
		if(type=="passage"){
			var institution_id=$('#passage_institution_id').val();
		}
		else if(type=="question"){

			var institution_id=$('#institution_id').val();


		}

		//change_institute=2;
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}categoryList/'+institution_id,
					type:'post',
					success:function(response){
						var a=response.length;
						if(type=="passage"){
							$('#passage_category_id').empty();
							$('#passage_subject_id').empty();
							$('#passage_lessons_id').empty();
							var opt = new Option('--Select Category--', '');
							//opt.addClass('selected','disabled','hidden');
							$('#passage_category_id').append(opt);
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);
								$('#passage_category_id').append(opt);
							}
						}
						else if(type=="question") {
							$('#category_id').empty();
							$('#subject_id').multiselect('destroy');
							$('#subject_id').empty();
							$('#subject_id').multiselect();
							$('#lessons_id').multiselect('destroy');
							$('#lessons_id').empty();
							$('#lessons_id').multiselect();
							//$('#question_type').empty();
							var opt = new Option('--Select Category--', '');
							//opt.addClass('selected','disabled','hidden');
							$('#category_id').append(opt);
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);
								$('#category_id').append(opt);
							}
						}
					}
				}
		);
		
	}

	function change_category(type){
		if(type=="passage"){
			var category_id=$('#passage_category_id').val();
		}
		else if(type=="question"){
			var category_id=$('#category_id').val();
			//alert(category_id);
		}
		//if(change_categry !=1){
			
		//}
		//change_categry = 2;

		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}subjectList/'+category_id,
					type:'post',
					success:function(response) {
						var a = response.length;
						if (type == "passage") {
							$('#passage_subject_id').multiselect('destroy');
							$('#passage_subject_id').empty();
							//	$('#passage_lessons_id').multiselect('destroy');
							$('#passage_lessons_id').empty();
							//var opt = new Option('--Select Subject--', '');
							//$('#passage_subject_id').append(opt);
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);
								$('#passage_subject_id').append(opt);
							}
							$('#passage_subject_id').multiselect();
							$('#passage_subject_id').multiselect('refresh');
						}
						else if (type == "question") {


							//$('#subject_id').empty();
							$('#subject_id').multiselect('destroy');
							//$('#subject_id').multiselect("clearSelection");
							//$('#subject_id').multiselect("refresh");
							$('#subject_id').empty();
							$('#lessons_id').multiselect('destroy');
							$('#lessons_id').empty();
							//$('#question_type').empty();
							$('#lessons_id').multiselect();
							//	var opt = new Option('--Select Subject--', '');
							//	$('#subject_id').append(opt);
							for (i = 0; i < a; i++) {
								var opt = new Option(response[i].name, response[i].id);
								$('#subject_id').append(opt);
								//$('#subject_id').multiselect('addOption', { value:response[i].id, text: response[i].name});
							}
							$('#subject_id').multiselect();
							$('#subject_id').multiselect('refresh');
						}
					}
				}
		);
		
	}

	function change_lessons(type){
		if(type=="passage"){
			var lesson_id=$('#passage_subject_id').val();
		}
		else if(type=="question"){
			var lesson_id=$('#subject_id').val();
			//alert(lesson_id);
		}

		//}
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}lessonsList/'+lesson_id,
					type:'post',
					success:function(response){
						var a=response.length;
						if(type=="passage"){
							$('#passage_lessons_id').multiselect('destroy');
							$('#passage_lessons_id').empty();
							//	var opt=new Option('--Select Lesson--','');
							//$('#passage_lessons_id').append(opt);
							for(i=0;i<a;i++){
								var opt=new Option(response[i].name,response[i].id);
								$('#passage_lessons_id').append(opt);
							}
							$('#passage_lessons_id').multiselect();
							$('#passage_lessons_id').multiselect('refresh');
						}
						else if(type=="question"){
							$('#lessons_id').multiselect('destroy');
							$('#lessons_id').empty();
							//$('#question_type').multiselect('destroy');
							//$('#question_type').empty();
							//$('#question_type').multiselect();
							//var opt=new Option('-Select Lesson--','');
							//$('#lessons_id').append(opt);
//							var opt=new Option('select');
//							$('#lessons_id').append(opt);
							for(i=0;i<a;i++){
								var opt=new Option(response[i].name,response[i].id);
								$('#lessons_id').append(opt);
							}
							$('#lessons_id').multiselect();
							$('#lessons_id').multiselect('refresh');
						}
					}
				}
		);
		
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
							 $( "#passageid" ).prop( "checked", false );

							 $('.test1 input[type="checkbox"]').on('change', function () 
                        		{
                        			//alert('1');
                                var allChecked = $(' .test1 input:checked').length === $(' .test1 input').length;

                                $('#passageid').prop('checked', allChecked);
                       			 });
						});
						
						/*$('#question_type').multiselect();
						 $('#question_type').multiselect('refresh');*/
					}
				}
		);
		
	}
	function filter_passage() {
		//alert('hi');
		//question_Ids=[];
		passage_Ids=[];
		var csrf=$('Input#csrf_token').val();
		// var question_id=document.getElementsByName('QuestionIds[]');
		// for (var i = 0; i < question_id.length; i++) {
		// 	question_Ids.push(question_id[i].value);
		// }
		var passage_id=document.getElementsByName('passageIds[]');

		for (var i = 0; i < passage_id.length; i++) {
			passage_Ids.push(passage_id[i].value);
		}
		var passage_institution_id=$('#passage_institution_id').val();
		var passage_category_id=$('#passage_category_id').val();
		var passage_subject_id=$('#passage_subject_id').val();
		var passage_lessons_id=$('#passage_lessons_id').val();
		if(subject_id=='')subject_id=0;
		if(institution_id=='')institution_id=0;
		if(category_id=='')category_id=0;
		if(lessons_id=='')lessons_id=0;
		var data={'institution':passage_institution_id,'category':passage_category_id,'subject':passage_subject_id,'lessons':passage_lessons_id,'passageIds':passage_Ids};
		var url='{{$path}}passage_filter_data_assessment';
		$.ajax(
				{
					url:url,
					headers: {"X-CSRF-Token": csrf},
					type:"post",
					data:data,
					success:function(response){
						
						$('#passages-list').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr/>');
							tr.append("<td><input type='checkbox' id='passages-list' value='"+response[i].pass_id+"' class='assess_qst check-passage' data-group-cls='btn-group-sm'></td>");
//							tr.append("<input type='hidden' value='"+response[i].id+"' name='QuestionIds[]' id='QuestionIds'>");
							tr.append("<td>" + response[i].passage_title + "</td>");
							$('#passages-list').append(tr);
						}
						
					}
				}
		);
		


	}
</script>