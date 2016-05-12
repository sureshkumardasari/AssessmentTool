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
                var institution_id = $('#userimport_institution_id').val();
                $('.error-log').empty();
                $.ajax({
                type: "GET",
                url: bulkUserTemplate,
                data: {userType:type, institution_id:institution_id},
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
                            
                institutionId = $("#userimport_institution_id").val();
                userType = 'student';
                if(institutionId == 0){
                    $('.error-log').html("<p class='error'>Please select an institution</p>");
                    return;
                }
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
                formData.append("userType", userType);
                // Set up the request.
                var xhr = new XMLHttpRequest();

                toggleMsg('Please wait');
                // Open the connection.
                xhr.open('POST', bulkUserUpload, true);

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
                            alert(result.msg);
                            parent.window.location.reload();
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
