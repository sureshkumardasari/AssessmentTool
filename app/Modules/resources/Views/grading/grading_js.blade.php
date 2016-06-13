<?php
$path = url()."/grading/";
?>
<script>
	function change_user(){
		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{
					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}list-student-ajax/'+$('#user').val(),
					type:'post',
					success:function(response){
 						$('#student_list').empty();
						var tr;
						for (var i = 0; i < response.length; i++) {
							tr = $('<tr/>');
 							tr.append("<td>" + response[i].first_name +'\t'+ response[i].last_name + "</td>");
  							tr.append("<td></td>");
  							tr.append("<td><a href=''><i class='icons ico-grade'></i></a></td>");
 							$('#student_list').append(tr);
						}
 					}
				}
		)
	}
</script>