/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

        $(function () {
			
            $(document).on('change', '.user-file', function () {
                var fileName = $(this).val();
                if ( fileName ) {
                    fileName = fileName.split("\\");
                    fileName = fileName[ fileName.length - 1 ];
                    $('.file-nm-box').html( fileName );
                } else {
                    $('.file-nm-box').html('Choose a file');
                }
            });
	    });
            
            function downloadTemplate(type){
                var institution_id = null;
                var assignment_id = null;
                var assignment_text = null;
                var institution_text= null;

                if(type == "administrator"){
                    institution_id = $('#gradesimport_institution_id').val();
                    assignment_id = $('#gradesimport_assignment_id').val();
                    assignment_text = $('#gradesimport_assignment_id option:selected').text();
                    institution_text = $('#gradesimport_institution_id option:selected').text();
                    if(institution_id == 0  || assignment_id == 0){
                        return false;
                    }

                }
                else if(type == "admin_teacher"){
                    assignment_id = $('#gradesimport_assignment_id').val();
                    assignment_text = $('#gradesimport_assignment_id option:selected').text();
                    if(assignment_id == 0){
                        return false;
                    }
                }
               
                $('.error-log').empty();
                $.ajax({
                type: "GET",
                url: bulkGradesTemplate,
                data: {userType:type, institution_id:institution_id , assignment_id:assignment_id , assignment_text:assignment_text,institution_text:institution_text},
                dataType: 'json',
                success: function( data ){
                    if ( data ) {
                      if(data.file_name != false){
                        window.location.href = data.file_name;
                       }
                    };
                  }
              });
            }


            $('.uploadBtn').off("click").on("click",function(){
                            
                institutionId = $("#gradesimport_institution_id").val();
                assignmentId = $('#gradesimport_assignment_id').val();
                //alert(assignmentId);
                //userType = 'student';
                if(institutionId == 0 || assignmentId == 0){
                    $('.error-log').html("<p class='error'>Please select all the fields</p>");
                    return;
                }
                // if(assignmentId==0 || assignmentId == "null" || assignmentId == "undefined"){
                //     $('.error-log').html("<p class='error'>Please select an Assignment</p>");
                //     return;
                // }
                if($('.user-file').val() == ''){
                    $('.error-log').html("<p class='error'>Please select a file to upload</p>");
                    return;
                }

                if ($(this).hasClass('disabled')) {
                    return;
                } else {
                    $(this).addClass('disabled');
                }

                // Create a new FormData object.
                var file = $(".user-file");
                var formData = new FormData();
                  formData.append('file', file[0].files[0]);
 
                formData.append("_token", $(".hidden-token").val());
                formData.append("institutionId", institutionId);
                formData.append("assignmentId", assignmentId);
               // formData.append("userType", userType);
                // Set up the request.
                var xhr = new XMLHttpRequest();

                toggleMsg('Please wait');
                // Open the connection.
                xhr.open('POST', bulkGradesUpload, true);

                // Set up a handler for when the request finishes.
                xhr.onload = function () {
                    toggleMsg();
                    $('.uploadBtn').removeClass('disabled');
                    if (xhr.status === 200) {
                    } else {
                        $('.user-file').val('');
    //                    alert('An error occurred!');
                    }
                }
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {

                        result = jQuery.parseJSON(xhr.responseText);

                        if(result.status == 'error'){
                            if(typeof result.error_log != 'undefined'){
                                $('.error-log').html("<a class='error_blue' style='margin-left:20px;' href='"+result.error_log+"'>Download Error Log</a>");
                                $('.user-file').val('');
                            }else if(typeof result.msg != 'undefined'){
                                $('.error-log').html("<p class='error'>"+result.msg+"</p>");
                                $('.user-file').val('');
                            }                            
                        }
                        else if(result.status == 'success'){
                            
                            $('.error-log').empty();
                            if(result.duplicate_msg!=null){
                                alert(result.duplicate_msg);
                            }
                            else{
                                alert(result.msg);
                            }
                            parent.$(".fancybox-close").click();
                           // parent.window.location.reload();
                        }
                    }
                }
                ;
                $('.error-log').empty();
                // Send the Data.
                xhr.send(formData); 
            })

            
function toggleMsg(msg) {
    if ($('.userSuccMSG').is(':hidden')) {
        $('.userSuccMSG').html(msg);
        $('.userSuccMSG').css('display', 'block');
        $('.userSuccMSG').css("top", 320 + "px");
        $('.userSuccMSG').css("left", (($(window).width() / 2 - $('.userSuccMSG').width() / 2) - 38) + "px");
    } else {
        $('.userSuccMSG').hide();
    }
    ;
}

function showMsg(msg) {
    $('.userSuccMSG').html(msg);
    $('.userSuccMSG').css('display', 'block');
    $('.userSuccMSG').css("top", 320 + "px");
    $('.userSuccMSG').css("left", (($(window).width() / 2 - $('.userSuccMSG').width() / 2) - 38) + "px");
    
    $('.userSuccMSG').fadeOut(4000);        

}
