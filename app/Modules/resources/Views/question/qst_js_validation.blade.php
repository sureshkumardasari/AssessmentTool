<?php
$path = url()."/resources/";

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
						var opt=new Option('--Select Category--','');
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
		$('#question_textarea').val(question_textarea);
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

	$(document).ready(function() {	
		 $('.ans-chk').checkboxpicker({
		  html: true,
		  offLabel: '<span class="glyphicon glyphicon-remove">',
		  onLabel: '<span class="glyphicon glyphicon-ok">'
		});

		$('.searchfilter').on("click",function(e){    
			alert("ssss");	
	    	//console.log('searchfilter ');
	    	$(".searchfilter span")
	        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
	        $('.searchfilter-body').toggleClass('hide show');
	    });

	} );	

</script>