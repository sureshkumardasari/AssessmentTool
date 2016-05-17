<style type="text/css">
    /*     JCROP CSS */
    li small {
        color: #f07878;
    }
    .inline-labels label {
        display: inline;
    }
    div#interface.span3 fieldset {
        margin-bottom: 1.5em;
    }
    div#interface.span3 fieldset legend {
        margin-bottom: 2px;
        padding-bottom: 2px;
        line-height: 1.2;
    }
    .article h1 {
        color: #333;
        margin-top: .2em;
    }
    .jc-demo {
        text-align: center;
    }
    .jcropper-holder {
        border: 1px #bbb solid;
    }
    .jc-demo-box {
        text-align: left;
        margin: 2em auto;
        background: white;
        border: 1px #bbb solid;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.25);
        box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.25);
        padding: 1em 2em 2em;
        overflow: hidden;
    }
    form {
        margin: 1.5em 0;
    }
    form.coords label {
        margin-right: 1em;
        font-weight: bold;
        color: #900;
    }
    form.coords input {
        width: 3em;
    }
    .ui-widget-overlay {
        opacity: 0.80;
        filter: alpha(opacity=70);
    }
    .jc-dialog {
        padding-top: 1em;
    }
    .ui-dialog p tt {
        color: yellow;
    }
    .jcrop-light .jcrop-selection {
        -moz-box-shadow: 0px 0px 15px #999;
        Firefox 

        -webkit-box-shadow: 0px 0px 15px #999;
        Safari, Chrome 

        box-shadow: 0px 0px 15px #999;
        CSS3 

    }
    .jcrop-dark .jcrop-selection {
        -moz-box-shadow: 0px 0px 15px #000;
        Firefox 

        -webkit-box-shadow: 0px 0px 15px #000;
        Safari, Chrome 

        box-shadow: 0px 0px 15px #000;
        CSS3 

    }
    .jcrop-fancy .jcrop-handle.ord-e {
        -webkit-border-top-left-radius: 0px;
        -webkit-border-bottom-left-radius: 0px;
    }
    .jcrop-fancy .jcrop-handle.ord-w {
        -webkit-border-top-right-radius: 0px;
        -webkit-border-bottom-right-radius: 0px;
    }
    .jcrop-fancy .jcrop-handle.ord-nw {
        -webkit-border-bottom-right-radius: 0px;
    }
    .jcrop-fancy .jcrop-handle.ord-ne {
        -webkit-border-bottom-left-radius: 0px;
    }
    .jcrop-fancy .jcrop-handle.ord-sw {
        -webkit-border-top-right-radius: 0px;
    }
    .jcrop-fancy .jcrop-handle.ord-se {
        -webkit-border-top-left-radius: 0px;
    }
    .jcrop-fancy .jcrop-handle.ord-s {
        -webkit-border-top-left-radius: 0px;
        -webkit-border-top-right-radius: 0px;
    }
    .jcrop-fancy .jcrop-handle.ord-n {
        -webkit-border-bottom-left-radius: 0px;
        -webkit-border-bottom-right-radius: 0px;
    }
    .description {
        margin: 16px 0;
    }
    .jcrop-droptarget canvas {
        background-color: #f0f0f0;
    }
    /* The Javascript code will set the aspect ratio of the crop
       area based on the size of the thumbnail preview,
       specified here */
    #preview-pane .preview-container {
        width: 200px;
        height: 200px;
        overflow: hidden;
    }
    .jcrop-holder{
        background:none !important;
    }
</style>
<?php 
$image192x192fromS3 = '';
if(!empty($pic_data['image'])){
    if(getenv('s3storage'))
    {   
        $image192x192fromS3 = getS3ViewUrl($pic_data['image'], 'user_profile_pic_192');
    }
    else
    {
        $image192x192fromS3 = asset('/data/uploaded_images/192x192/'.$pic_data['image']);
    }
}
?>
<div style="width: 200px;">
    <img id="image_loader" class="hide" style="margin-left: -118px;margin-top: 66px;" src="{{asset('/images/fancybox_loading@2x.gif')}}"/>
    @if(empty($pic_data['image']) || empty($image192x192fromS3))
    @else
    <a href="javascript:void(0)" style='top:202px !important;' class="pic_button change_button launchEdit btn btn-primary btn-sm">Edit</a>
    @endif
</div>
<a class="fancybox hide" id='launch_resizer' href="#container"></a>

<input type="hidden" value="{{ !empty($pic_data['coords']) ? $pic_data['coords']:'' }}" name="coords" id="coords">
<input type="hidden" value="{{ !empty($pic_data['image']) ? $pic_data['image']:'' }}" name="uploaded_image_name" id="uploaded_image_name">
<input type="hidden" value="{{ !empty($pic_data['id']) ? $pic_data['id'] : 0 }}" name="image_user_id" id="image_user_id">
@if(!empty($pic_data["coords"]))
    <?php $coords = explode(',', $pic_data["coords"]); ?>
@endif
<script type="text/javascript">
    // Create variables (in this scope) to hold the API and image size
    var jcrop_api = '',
            boundx,
            boundy,
            image_name,
            d = new Date(),
            coords = $("#coords").val(),
            // Grab some information about the preview pane
            $preview = $('#preview-pane'),
            $pcnt = $('#preview-pane .preview-container'),
            $pimg = $('#preview-pane .preview-container img'),
            xsize = $pcnt.width(),
            ysize = $pcnt.height();

    function updatePreview(c) {
        if (parseInt(c.w) > 0) {
            var rx = xsize / c.w;
            var ry = ysize / c.h;
            $pimg.css({
                width: Math.round(rx * boundx) + 'px',
                height: Math.round(ry * boundy) + 'px',
                marginLeft: '-' + Math.round(rx * c.x) + 'px',
                marginTop: '-' + Math.round(ry * c.y) + 'px'
            });
        }
    }
    function attachJcrop(type) {
        $("#image_loader, #image_loader_popup").hide();
        if(type == 'image'){
//            $('#id_launchUploader input[type="file"]').attr('disabled',false);
        }else{
//            $('#id_launchUploaderPopup input[type="file"]').attr('disabled',false);
        }
        var selection = [0, 0, 200, 200];
        if (jcrop_api != '') {
            jcrop_api.destroy();
        }
        $('#target').Jcrop({
            onChange: updatePreview,
            onSelect: updatePreview,
            setSelect: selection
        }, function () {
            // Use the API to get the real image size
            var bounds = this.getBounds();
            boundx = bounds[0];
            boundy = bounds[1];
            // Store the API in the jcrop_api variable
            jcrop_api = this;
            if(coords != '' && coords != undefined){
                if(!$.isArray(coords)){
                    coords = coords.split(',');
                }
                animateTo = [coords[0],coords[1], parseInt(coords[0]) + parseInt(coords[2]), parseInt(coords[1]) + parseInt(coords[3])]
                jcrop_api.animateTo(animateTo);
            }
        });
        if(type != 'image_new'){
            $("#launch_resizer").click();
            //$('#container').toggleClass('hide show');
        }
    }
    $('#saveImage').click(function () {
        coords = jcrop_api.tellSelect();
        $.ajax({
            type:'post',
            url:'{{route('save_crop')}}',
            data:{coords:coords, image_name:$("#uploaded_image_name").val(), user_id:$("#image_user_id").val(), "_token":$(".hidden-token").val()},
            dataType:'json',
            success: function(response){
                $("#pic_coords").val(response.coords);
                $("#profile_picture").val(response.image_name);
                
                $("#coords").val(response.coords);
                $("#uploaded_image_name").val(response.image_name);
//                $('#photo').replaceWith('<img id="photo" src="' + '{{asset("data/uploaded_images/192x192")}}/' + response.image_name + '?'+Math.floor(Math.random()*1000)+'" />');
                $('#photo').attr('src',response.image_path_s3+ '?'+Math.floor(Math.random()*1000));
                coords = response.coords;
                // if(response.user_id == 0){
                    $('.change_button').replaceWith("<a href='javascript:void(0)' style='top:215px !important;' class='pic_button change_button launchEdit btn btn-primary btn-sm'>Edit</a>")
                // }
                $.fancybox.close();
            }
        });
    });
    
    function uploadProfilePics(fileObj,formToSubmit){
        var type = fileObj.id;
        var file = $(fileObj);
        // Create a new FormData object.
        $("#"+formToSubmit).ajaxForm({dataType: 'json',success:function(response){
            //success here
            if (response.filename != undefined) {
//                response = $.parseJSON(xhr.response);
                $('#target').replaceWith('<img id="target" src="'+ response.file_path + '"/>');
                $(".jcrop-preview").attr('src', response.file_path);
                image_name = response.filename;
                file.val('');
                $("#uploaded_image_name").val(response.filename)
                $("#coords").val(response.coords)
                coords = response.coords;
                attachJcrop(type);
            } else {
                error = response;
                var html = '';
                $.each(error.image, function(key,val){
                    html += '<li>'+val+'</li>';
                });
                $('#error_container ul').html(html);
                if(type != 'image_new'){
//                    $('#id_launchUploader input[type="file"]').attr('disabled',false);
                    $('#show_error_popup').click();
                }else{
//                    $('#id_launchUploaderPopup input[type="file"]').attr('disabled',false);
                    $(".image_error").text(error.image[0]).show();
                }
            }
            $("#image_loader, #image_loader_popup").hide();
        }}).submit();
    }
    
    $('.uploadImage').off("change").on("change", function () {
        $("#image_loader").show();
        $(".image_error").empty();
        $('.error-log').html('');
            
        
        if ($(this).val() == '') {
            return false;
        }
//        $('#id_launchUploader input[type="file"]').attr('disabled',true);
        // calling funciton to upload profilepic.
        uploadProfilePics(this,'ajaxuploadprofilepic');
    });
    
    $('.uploadImageFromPopup').off("change").on("change", function () {
        $("#image_loader_popup").show();
        $(".image_error").empty();
        $('.error-log').html('');
        if ($(this).val() == '') {
            return false;
        }
//        $('#id_launchUploaderPopup input[type="file"]').attr('disabled',true);
        // calling funciton to upload profilepic.
        uploadProfilePics(this,'ajaxuploadprofilepicpopup');
    });
    
    $(document).on('click','.launchEdit',function(){
        attachJcrop();
    });
    
//    $(document).on('click','.launchUploader',function(){
//        $('.uploadImage').click();
//    });
//    
//    $(document).on('click','.launchUploaderPopup',function(){
//        $('.uploadImageFromPopup').click();
//    });
</script>