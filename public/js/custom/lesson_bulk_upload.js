/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {

    $(document).on('change', '.lesson-file', function () {
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

function downloadTemplate(type) {


    var institution_id = $('#lessonimport_institution_id').val();
    var category_id=$('#lessonimport_category_id').val();
    var subject_id=$('#lessonimport_subject_id').val();
    //alert(institution_id);
    $('.error-log').empty();
    if($('#lessonimport_subject_id').val() == ''){
        alert('Input can not be left blank');
    }
    //var thisVal = $('#lessonimport_category_id').val();
    //var msg = (thisVal > 0) ? 'category Id is <u>"' +thisVal+ '</u>"' : "";
    //$(".lessonimport_category_id").html(msg);
    $.ajax({
        type: "GET",
        url: bulklessonTemplate,
        data: {lessonType: type, institution_id: institution_id, category_id: category_id, subject_id: subject_id},
        dataType: 'json',
        success: function (data) {
            //alert(JSON.stringify(data));
            if (data) {
                if (data.file_name != false) {

                    window.location.href = data.file_name;

                }
            }
            ;
        }
    });
}


$('.uploadBtn').off("click").on("click",function(){

    institutionId = $("#lessonimport_institution_id").val();
    categoryId = $("#lessonimport_category_id").val();
    subjectId = $("#lessonimport_subject_id").val();

    lessonType = 'lessonType';
    if(institutionId == 0 || categoryId == 0 || subjectId == 0){
        $('.error-log').html("<p class='error'>Please select all the fields</p>");
        return;
    }
    if($('.lesson-file').val() == ''){
        $('.error-log').html("<p class='error'>Please select a file to upload</p>");
        return;
    }

    if ($(this).hasClass('disabled')) {
        return;
    } else {
        $(this).addClass('disabled');
    }

    // Create a new FormData object.
    var file = $(".lesson-file");
    var formData = new FormData();
    formData.append('file', file[0].files[0]);

    formData.append("_token", $(".hidden-token").val());
    formData.append("institutionId", institutionId);
    formData.append("lessonType", lessonType);
    // Set up the request.
    var xhr = new XMLHttpRequest();

    toggleMsg('Please wait');
    // Open the connection.
    xhr.open('POST', bulklessonUpload, true);

    // Set up a handler for when the request finishes.
    xhr.onload = function () {
        toggleMsg();
        $('.uploadBtn').removeClass('disabled');
        if (xhr.status === 200) {
        } else {
            $('.lesson-file').val('');
            //                    alert('An error occurred!');
        }
    }
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {

            result = jQuery.parseJSON(xhr.responseText);

            if(result.status == 'error'){
                if(typeof result.error_log != 'undefined'){
                    $('.error-log').html("<a class='error_blue' style='margin-left:20px;' href='"+result.error_log+"'>Download Error Log</a>");
                    $('.lesson-file').val('');
                }else if(typeof result.msg != 'undefined'){
                    $('.error-log').html("<p class='error'>"+result.msg+"</p>");
                    $('.lesson-file').val('');
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
    if ($('.lessonSuccMSG').is(':hidden')) {
        $('.lessonSuccMSG').html(msg);
        $('.lessonSuccMSG').css('display', 'block');
        $('.lessonSuccMSG').css("top", 320 + "px");
        $('.lessonSuccMSG').css("left", (($(window).width() / 2 - $('.lessonSuccMSG').width() / 2) - 38) + "px");
    } else {
        $('.lessonSuccMSG').hide();
    }
    ;
}

function showMsg(msg) {
    $('.userlessonSuccMSGSuccMSG').html(msg);
    $('.lessonSuccMSG').css('display', 'block');
    $('.lessonSuccMSG').css("top", 320 + "px");
    $('.lessonSuccMSG').css("left", (($(window).width() / 2 - $('.lessonSuccMSG').width() / 2) - 38) + "px");

    $('.lessonSuccMSG').fadeOut(4000);

}