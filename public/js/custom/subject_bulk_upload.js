/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {

    $(document).on('change', '.subject-file', function () {
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


    var institution_id = $('#subjectimport_institution_id').val();

    var category_id=$('#subjectimport_category_id').val();
    //alert(category_id);
    $('.error-log').empty();
    if($('#subjectimport_category_id').val() == ''){
        alert('Input can not be left blank');
    }
    $.ajax({
        type: "GET",
        url: bulksubjectTemplate,
        data: {subjectType:type, institution_id:institution_id,category_id: category_id},
        dataType: 'json',
        success: function( data ){
            //alert(JSON.stringify(data));
            if ( data ) {
                //alert(data);
                if(data.file_name != false){

                    window.location.href = data.file_name;

                }
            };
        }
    });
}


$('.uploadBtn').off("click").on("click",function(){

    institutionId = $("#subjectimport_institution_id").val();
    var category_id=$('#subjectimport_category_id').val();
    subjectType = 'subjectType';
    
    if(institutionId == 0 || category_id == 0){
        $('.error-log').html("<p class='error'>Please select all the fields</p>");
        return;
    }
    if($('.subject-file').val() == ''){
        $('.error-log').html("<p class='error'>Please select a file to upload</p>");
        return;
    }

    if ($(this).hasClass('disabled')) {
        return;
    } else {
        $(this).addClass('disabled');
    }

    // Create a new FormData object.
    var file = $(".subject-file");
    var formData = new FormData();
    formData.append('file', file[0].files[0]);

    formData.append("_token", $(".hidden-token").val());
    formData.append("institutionId", institutionId);
    formData.append("subjectType", subjectType);
    // Set up the request.
    var xhr = new XMLHttpRequest();

    toggleMsg('Please wait');
    // Open the connection.
    xhr.open('POST', bulksubjectUpload, true);

    // Set up a handler for when the request finishes.
    xhr.onload = function () {
        toggleMsg();
        $('.uploadBtn').removeClass('disabled');
        if (xhr.status === 200) {
        } else {
            $('.subject-file').val('');
            //                    alert('An error occurred!');
        }
    }
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {

            result = jQuery.parseJSON(xhr.responseText);

            if(result.status == 'error'){
                if(typeof result.error_log != 'undefined'){
                    $('.error-log').html("<a class='error_blue' style='margin-left:20px;' href='"+result.error_log+"'>Download Error Log</a>");
                    $('.subject-file').val('');
                }else if(typeof result.msg != 'undefined'){
                    $('.error-log').html("<p class='error'>"+result.msg+"</p>");
                    $('.subject-file').val('');
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
    if ($('.subjectSuccMSG').is(':hidden')) {
        $('.subjectSuccMSG').html(msg);
        $('.subjectSuccMSG').css('display', 'block');
        $('.subjectSuccMSG').css("top", 320 + "px");
        $('.subjectSuccMSG').css("left", (($(window).width() / 2 - $('.subjectSuccMSG').width() / 2) - 38) + "px");
    } else {
        $('.subjectSuccMSG').hide();
    }
    ;
}

function showMsg(msg) {
    $('.usersubjectSuccMSGSuccMSG').html(msg);
    $('.subjectSuccMSG').css('display', 'block');
    $('.subjectSuccMSG').css("top", 320 + "px");
    $('.subjectSuccMSG').css("left", (($(window).width() / 2 - $('.subjectSuccMSG').width() / 2) - 38) + "px");

    $('.subjectSuccMSG').fadeOut(4000);

}