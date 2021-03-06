<?php
$path = url()."/grading/";
?>
<script>
//    $(document).ready(function(){
//       // alert(JSON.stringify(Question_selected_answers));
//       // alert(JSON.stringify(selected_student_answers));
//        //alert(Answer_ids);
//        $('#drpAssignmentStudent').val(first_user);
//        //alert(JSON.stringify(selected_student_answers));
//        user_answers();
//        $.each(selected_student_answers,function(i,val){
//            $('input[type="radio"][value='+val+']').prop('checked',true);
//        });
//        //alert(JSON.stringify(Question_selected_answers));
//        //myControls.
//    });

</script>
<script>
	function change_user1(){

		var csrf=$('Input#csrf_token').val();
		$.ajax(
				{
					headers: {"X-CSRF-Token": csrf},
					url:'{{$path}}list-student-ajax/'+$('#user').val(),
					type:'post',
					success:function(response){
						
						//alert(str);
						$('#assignmentstable').dataTable().fnDestroy();
 						$('.student_list').empty();
						var tr;
						var v1 = $('#user').val();
						var v2 = $('#assignmentid').val();
						var v3 = $('#assessmentid').val();
						var str = v1+"-"+v2+"-"+v3;
						//alert(str);
						for (var i = 0; i < response.length; i++) {
							//str = v1+"-"+v2+"-"+v3;
							//alert(str);
							tr = $('<tr/>');
 							tr.append("<td>" + response[i].first_name +'\t'+ response[i].last_name +"</td>");
  							tr.append("<td><a href='{{$path}}list-student-question/"+str+"'><i class='icons ico-grade'></i></a></td>");
 							$('.student_list').append(tr);
						}
						$('#assignmentstable').DataTable({
				aoColumnDefs: [
			  {
			     bSortable: false,
			     aTargets: [ -1 ]
			  }
			]
			 });
 					}
				}

			    

		)
	}

	$(document).ready(function(){
 		var url = '{{$path}}grade-student-edit-questions';

		//display modal form for task editing
		$('.open-modal').click(function(){
 			var task_id = $(this).val();

			$.get(url + '/' + task_id, function (data) {
				//success data
				$('#task_id').val(data.id);
				$('#task').val(data.task);
				$('#description').val(data.description);
				$('#btn-save').val("update");

				$('#myModal').modal('show');
			})
		});

		//display modal form for creating new task
		$('#btn-add').click(function(){
			$('#btn-save').val("add");
			$('#frmTasks').trigger("reset");
			$('#myModal').modal('show');
		});

		//delete task and remove it from list
		$('.delete-task').click(function(){
			var task_id = $(this).val();

			$.ajax({

				type: "DELETE",
				url: url + '/' + task_id,
				success: function (data) {
					console.log(data);

					$("#task" + task_id).remove();
				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		});

		//create new task / update existing task
		$("#btn-save").click(function (e) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
				}
			})

			e.preventDefault();

			var formData = {
				task: $('#task').val(),
				description: $('#description').val(),
			}

			//used to determine the http verb to use [add=POST], [update=PUT]
			var state = $('#btn-save').val();

			var type = "POST"; //for creating new resource
			var task_id = $('#task_id').val();;
			var my_url = url;

			if (state == "update"){
				type = "PUT"; //for updating existing resource
				my_url += '/' + task_id;
			}

			console.log(formData);

			$.ajax({

				type: type,
				url: my_url,
				data: formData,
				dataType: 'json',
				success: function (data) {
					console.log(data);

					var task = '<tr id="task' + data.id + '"><td>' + data.id + '</td><td>' + data.task + '</td><td>' + data.description + '</td><td>' + data.created_at + '</td>';
					task += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data.id + '">Edit</button>';
					task += '<button class="btn btn-danger btn-xs btn-delete delete-task" value="' + data.id + '">Delete</button></td></tr>';

					if (state == "add"){ //if user added a new record
						$('#tasks-list').append(task);
					}else{ //if user updated an existing record

						$("#task" + task_id).replaceWith( task );
					}

					$('#frmTasks').trigger("reset");

					$('#myModal').modal('hide')
				},
				error: function (data) {
					console.log('Error:', data);
				}
			});
		});
	});

</script>