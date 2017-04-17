<?php
$path = url()."/resources/";?>
<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
<input type="hidden" name="url" id="url" value="<?php echo $path;?>">

<?php
if (count($errors) > 0){?>
// <script>
// 	var oldvalues = '{{old('institution_id')}}';
// 	var catoldvalues = '{{old('category_id')}}';
// 	var suboldvalues = '{{old('subject_id')}}';
//  	var lessonoldvalues = '{{old('lessons_id')}}';
//  	function htmlDecode(value) {
// 		return $("<textarea/>").html(value).text();
// 	};
// 	$('#institution_id').val(oldvalues);
// 	if(oldvalues!=null){
// 		var csrf=$('Input#csrf_token').val();
// 		$.ajax(
// 				{
// 					headers: {"X-CSRF-Token": csrf},
// 					url:'{{$path}}categoryList/'+$('#institution_id').val(),
// 					type:'post',
// 					success:function(response){
// 						var a=response.length;
// 						$('#category_id').empty();
// 						var opt=new Option('--Select Category--','');
// 						$('#category_id').append(opt);
// 						for(i=0;i<a;i++){
// 							var opt=new Option(response[i].name,response[i].id);
// 							$('#category_id').append(opt);
// 						}

// 						//category
// 						if(catoldvalues!=null)
// 						{
// 							$('#category_id').val(catoldvalues);
// 							$.ajax(
// 									{

// 										headers: {"X-CSRF-Token": csrf},
// 										url:'{{$path}}subjectList/'+$('#category_id').val(),
// 										type:'post',
// 										success:function(response){
// 											var a=response.length;
// 											$('#subject_id').empty();
// 											var opt=new Option('--Select Subject--','');
// 					 						$('#subject_id').append(opt);
// 											for(i=0;i<a;i++){
// 												var opt=new Option(response[i].name,response[i].id);
// 												$('#subject_id').append(opt);
// 											}

// 											//sub
// 											if(suboldvalues!=null){
// 												$('#subject_id').val(suboldvalues);
// 												$.ajax(
// 														{
// 															headers: {"X-CSRF-Token": csrf},
// 															url:'{{$path}}lessonsList/'+$('#subject_id').val(),
// 															type:'post',
// 															success:function(response){
// 																var a=response.length;
// 																$('#lessons_id').empty();
// 																var opt=new Option('--Select Lesson--','');
// 																$('#lessons_id').append(opt);
// 																for(i=0;i<a;i++){
// 																	var opt=new Option(response[i].name,response[i].id);
// 																	$('#lessons_id').append(opt);
// 																}
// 																$('#lessons_id').val(lessonoldvalues);
// 															}
// 														}
// 												)
// 											}//sub end
// 										}
// 									}
// 							)
// 						}//end category
						

// 					}
// 				}
// 		)
		
		
// 		$('#question_type').val(question_type);		
// 		$('#question_title').val(question_title);
//  		$("#question_textarea")
// 				.text(htmlDecode(question_textarea));
		
// 		$('#passage').val(passage);
	
//   	}

// </script>
<?php }?>
<script>
	function change_institution(){
 		var url='{{$path}}categoryList/'+$('#institution_id_filter').val();
  		var csrf=$('Input#csrf_token').val();
   		// alert($('#institution_id_filter').val());
			$.ajax(
					{

						headers: {"X-CSRF-Token": csrf},
						url:url,
						type:'post',
						success:function(response){
 							var a=response.length;
 							$('#category_id_filter').empty();
 							$('#subject_id_filter').empty();
 							$('#lessons_id_filter').empty();
 							$('#question_type_filter').val("0");
 							var opt=new Option('--Select Category--','');
							$('#category_id_filter').append(opt);
							var opt1=new Option('--Select Subject--','');
							$('#subject_id_filter').append(opt1);
							var opt2=new Option('--Select Lesson--','');
							$('#lessons_id_filter').append(opt2);
							//opt.addClass('selected','disabled','hidden');

 							for(i=0;i<a;i++){
								var opt=new Option(response[i].name,response[i].id);
								$('#category_id_filter').append(opt);
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
					url:'{{$path}}subjectList/'+$('#category_id_filter').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#subject_id_filter').empty();
						var opt=new Option('--Select Subject--','');
 						$('#subject_id_filter').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#subject_id_filter').append(opt);
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
					url:'{{$path}}lessonsList/'+$('#subject_id_filter').val(),
					type:'post',
					success:function(response){
						var a=response.length;
						$('#lessons_id_filter').empty();
						var opt=new Option('--Select Lesson--','');
						$('#lessons_id_filter').append(opt);
						for(i=0;i<a;i++){
							var opt=new Option(response[i].name,response[i].id);
							$('#lessons_id_filter').append(opt);
						}
					}
				}
		)
	}
		function change_question_type(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{

					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}questiontypeList/'+$('#lessons_id_filter').val(),
					type:'post',
					success:function(response){
						var a=response['question_type'].length;
						$('#question_type_filter').empty();
						var opt=new Option('--Select Question Type--','');
						$('#question_type_filter').append(opt);
						for(i=0;i<a;i++){
						var opt=new Option(response['question_type'][i].qst_type_text,response['question_type'][i].question_type_id);
						$('#question_type_filter').append(opt);
						}
					}
				}
		)
	}
  
</script>