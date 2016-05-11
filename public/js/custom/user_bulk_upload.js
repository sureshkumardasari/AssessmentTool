/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

        $(function () {
			$(".custom_slct").SumoSelect();

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
                $('.error-log').empty();
                $.ajax({
                type: "GET",
                url: bulkUserTemplate,
                data: {userType:type},
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
                            
                organizationId = $("select[name='organizationId']").val();
                institutionId = $("select[name='institutionId']").val();
                userType =$("select[name='userType']").val();
                if(organizationId == 0){
                    $('.error-log').html("<p class='error'>Please select an organization</p>");
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
                formData.append("organizationId", organizationId);
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
                            
                            if ( isFromInstitionPage ) {

                                window.location.replace( institutionViewUrl );
                            } else {

                                parent.window.location.reload();
                            }
                        }
                    }
                }
                ;
                $('.error-log').empty();
                // Send the Data.
                xhr.send(formData); 
            })

            $( "select[name='organizationId']" ).off('change').on('change', function() {
            id = $(this).val();
            $.ajax({
            type: "GET",
            url: childInstitutionUrl,
            data: {Id: id},
            dataType: 'text',
            beforeSend: function() {                
            }, success: function( data ){
                if ( data ) {
                    $("select[name='institutionId']").empty().append(data);
                    $("select[name='institutionId']")[0].sumo.unload();
                    $("select[name='institutionId']").SumoSelect();  
                };
            }, complete: function() {
            }
          });
        });


function downloadInstituteIdsXcl(urlStr){
    var institutionIdVal = $('#select2Institution').select2('val');
    console.log(institutionIdVal);
    $.ajax({
        type: "GET",
        url: urlStr,
        data: {institutionId: institutionIdVal},
        dataType: 'json',
        success: function( data ){
            if ( data.response == 'success' ) {
                window.location.href=data.file_name;
            };
        }
    });
}
