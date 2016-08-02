<style>
    .fancybox-inner {
        min-height: 461px !important;

    }
    .toolbar-area{
        border: 1px solid #aaaaaa;
        background: #cccccc url(images/ui-bg_highlight-soft_75_cccccc_1x100.png);
    }
    .toolbar-area ul li{
        margin-right: 10px;
        display: inline-table;
        cursor: pointer;
    }
    .items-container{
        position: relative;
        text-align: center;
        overflow-y: scroll;
        height: 400px;
        width: 100%;
    }
    .items-container ul{
        margin: 7px;
        overflow: hidden;


    }
    .image-listing{
        margin: 0 3px 12px 0;
        width: 111px;
        height: 80px;
        cursor: pointer;
        width: 110px;
        padding-bottom: 6px;
        border-radius: 2px;
        padding: 3px;
    }
    .image-listing:hover{
        background: rgb(220,220,220);
    }
    .image-viewer img{
        display: block;
        margin: 0 auto;
        width: 100%;
        height: auto;
    }
    .image-viewer{
        display: block;
        margin: 0 auto;
        position: absolute;
        left:50%;
        top:4%;
        background:rgba(0,0,0,0.5);
        margin:0px 0px 0 -241px;
        width: 560px;
        overflow: hidden;
        min-height: 443px;
        border-radius: 4px;
    }
    .image-holder {    
/*        max-width: 403px;
        min-height: 392px;
        margin: 0 auto;
*/  
        max-width: 664px;
        min-height: 392px;
        display: table-cell;
        vertical-align: middle;
        height: 366px;
        margin: 0 auto;
        width: 664px;
        padding: 24px;
    }
    .search {
        border-radius: 20px;
        font-size: 16px;
        line-height: 26px;
        padding: 5px;
        height: 38px;
        border: 1px solid #777;
        width: 236px;
        outline: none;
    }
    .viewer-titlebar{
        background:#777;
        text-align: center;
        padding:5px;
        color:#FFF;
    }
    li.selected span{
        background: #3875d7;
        color: #fff;
        border-radius: 14px;
        margin-top: 10px;
        line-height: 14px;
        padding: 3px;

    }
    .item-name{
        width: 104px !important; 
        display: block;
        margin-top: 5px;
    }
    .bulk_popup ul {
        margin: 0;
        padding: 5px 20px;
        overflow: hidden;
    }
    .img-icon {background:url({{url('/images/icons-big.png')}}) no-repeat -1px -251px; width: 46px;height: 48px; margin: 0 auto; }
    .office-icon {background: url(/public/images/icons-big.png) no-repeat -5px -502px; width: 40px; height: 47px; margin: 0 auto;}
    .unknown-icon {background: url(/public/images/icons-big.png) no-repeat -4px -1px;width: 40px; height: 47px; margin: 0 auto;}
    .audio-icon {background: url(/public/images/icons-big.png) no-repeat -2px -301px;width: 44px; height: 48px; margin: 0 auto;}
    .video-icon {background: url(/public/images/icons-big.png) no-repeat 0 -351px; width: 48px; height: 48px; margin: 0 auto;}
    .compress-icon {background: url(/public/images/icons-big.png)  no-repeat -1px -1051px; width: 46px; height: 48px; margin: 0 auto;}
    .flash-icon {background: url(/public/images/icons-big.png) no-repeat 0 -1251px; width: 47px; height: 47px; margin: 0 auto;}
    .pdf-icon {background: url(/public/images/icons-big.png) no-repeat 0 -451px; width: 48px; height: 47px; margin: 0 auto;}
    .toolbar-area ul li.upload-btn,
    .toolbar-area ul li.download-btn,
    .toolbar-area ul li.select-btn,
    .toolbar-area ul li.view-btn {margin-top: 4px; background: #ccc; border-radius: 2px; border:1px solid #aaaaaa; height: 20px; width: 20px;}
    .area ul li.upload-btn:hover,
    .toolbar-area ul li.download-btn:hover,
    .toolbar-area ul li.select-btn:hover,
    .toolbar-area ul li.view-btn:hover {background: #ddd;}
    .toolbar-area ul li a {background:url({{url('/images/toolbar.png')}}) no-repeat 0 -369px ; height: 15px; width: 16px; display: block; text-indent: -9999px; overflow: hidden; margin: 3px 0 0 2px;}
    .toolbar-area ul li label {background:url({{url('/images/toolbar.png')}}) no-repeat 0 -369px ; height: 15px; width: 16px; display: block; text-indent: -9999px; overflow: hidden; margin: 3px 0 0 2px;}
    .toolbar-area ul li.download-btn a {background-position: 0 -385px;}
    .toolbar-area ul li.select-btn a {background-position: 0 -289px;}
    .toolbar-area ul li.view-btn a {background-position: 0 -355px; width: 16px; height: 10px; margin-top: 6px;}
    .no-results{display:block !important; width: 100% !important; text-align: center; cursor: default !important;}
    .no-results:hover{background: none !important;}

    #popup_box { 
    display:none; /* Hide the DIV */
    position:fixed;  
    _position:absolute; /* hack for internet explorer 6 */  
    height:300px;  
    width:600px;  
    /*background:#FFFFFF;  */
    left: 50%;
    right: 50%;
    top: 50%;
    bottom: 50%;
    margin-top:-222px;
    z-index:100; /* Layering ( on-top of others), if you have lots of layers: I just maximized, you can change it yourself */
    margin-left: -274px;  
    
    /* additional features, can be omitted */
    border:1px solid rgba(0,0,0,1);   
    /*padding:15px;  */
    font-size:15px;  
    -moz-box-shadow: 0 0 5px rgba(0,0,0,1);
    -webkit-box-shadow: 0 0 5px rgba(0,0,0,1);
    box-shadow: 0 0 5px rgba(0,0,0,1);
    background:rgba(0,0,0,0.5);
    width: 560px;
    overflow: hidden;
    min-height: 443px;
    border-radius: 4px;
    
}
#popup_box img{
    display: block;
    margin: 0 auto;
    width: auto;
    height: auto;
    max-height: 380px;
    top:50%;
    bottom:50%;
    max-width: 512px;
}
.main-cross-icon{top:5px !important;}
.disabled{opacity: 0.5;}
.disabled:hover{opacity: 0.5;background: #ccc !important; cursor: default !important;}
.disabled a:hover{cursor: default !important;}
*::-moz-placeholder{line-height: 17px;}
.popupBoxClose{top: 9px !important;}
</style>
{!! HTML::script('assets/js/jquery.hideseek.min.js') !!}
<form method='post' action='{{ url('/resources/file-browser-upload-file') }}' class="file-upload-form" enctype="multipart/form-data">
    <input type='file' name='item' style="display:none;" id="upload_image">
    <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
    <input type='hidden' name='bucket' id="bucket" value="{{ $bucket }}">
</form>
<div class="bulk_popup" style="width:900px;">
    <i class="icons cross_icon main-cross-icon mt12" onclick="$.fancybox.close()"></i>
    <h1 class="">Insert Image</h1>
    <div class="toolbar-area">
        <ul>
            <li id="upload" class="upload-btn fltL"><label for="upload_image" title="Upload File">Up
                </label></li>
            <li id="view" class="view-btn fltL"><a href="javascript:;" title="View">View</a></li>
            <li class="search-area fltR"><input id="search" data-nodata="No results found" data-list=".default_list" autocomplete="off" placeholder="search" type="text" class="search"/></li>
        </ul>
        <div class="clr"></div>
    </div>
    <div class="items-container ">
        <ul class="default_list list-inline">
            @foreach($items as $index => $item)
            <?php $className = getItemIconClass($item['item_name']); ?>
                @if($index > 0)
                    <li item-url="{{ $item['item_path'] }}" file-type="{{ $className }}" item-name="{{ $item['item_name'] }}" item-size="{{ $item['item_size'] }}" title="{{ $item['item_name'] }}" class="fltL image-listing item-linked">
                        <div class="{{ $className }}"></div>
                        <span class='ellipsis item-name'>{{ $item['item_name'] }}</span>
                    </li>
                @endif
            @endforeach
        </ul>
        <!-- div class="image-viewer" style="display:none;">
            <div class="viewer-titlebar"><span style="text-align:center;"></span><i class="icons cross_icon" onclick="closeImageViewer()"></i></div>
            <div class="image-holder"></div>

        </div> --> 
    </div>
    <div class="clr"></div>
    <input type="hidden" name="_token" class="hidden-token" value="{{csrf_token()}}">
    <div class="clr"></div>
</div>
<div id="popup_box">    <!-- OUR PopupBox DIV-->
    <div class="viewer-titlebar"><span style="text-align:center;"></span><i class="icons cross_icon popupBoxClose" onclick="unloadPopupBox()"></i></div>
    <div class="image-holder"></div>
</div>
<script type="text/javascript">

$(document).on('click', 'li.item-linked', function(){
    $('li.item-linked').removeClass('selected');
    $(this).addClass('selected');
    var fileType = $(this).attr('file-type');
    if(fileType != 'img-icon'){
        $('li#view').addClass('disabled');
    }else{
        $('li#view').removeClass('disabled');
    }
});

$(document).on('click','li#select', function(){
    selectFile();
});
$(document).on('dblclick','li.item-linked', function(){
    window.selectedItem = $(this).attr('item-url');
    window.itemName = $(this).attr('item-name');
    window.itemSize = $(this).attr('item-size');
    $.fancybox.close();
});
$('li#download').click(function(){
    if($('li.item-linked').hasClass('selected')){
        var url = $('li.item-linked.selected').attr('item-url');
        var link = document.createElement('a');
            link.href = url;
            link.download = 'Download.jpg';
            document.body.appendChild(link);
            link.click();
    }
});

$('li#view').click(function(){
    if($('li.item-linked').hasClass('selected') && !$(this).hasClass('disabled')){
    var url = $('li.item-linked.selected').attr('item-url');
    var name = $('li.item-linked.selected').attr('item-name');
    var image = document.createElement('img');
        image.src = url;
        image.id = 'viewed_image';
        $('.image-holder').html(image);
        $('.viewer-titlebar span').html(name);
        loadPopupBox();
        // $('.image-viewer').fadeIn();
    }
});

function selectFile(){
    if($('li.item-linked').hasClass('selected')){
        var url = $('li.item-linked.selected').attr('item-url');
        window.selectedItem = url;
        alert(url);
        window.itemName = $('li.item-linked.selected').attr('item-name');
        window.itemSize = $('li.item-linked.selected').attr('item-size');
        $.fancybox.close();
    }
}
$('.bulk_popup').click( function(e) {
    if($(e.target).closest('li').attr('id') != 'view'){
        unloadPopupBox();
    }
});

// function closeImageViewer(){
//     $('.image-holder').empty()
//     $('.image-viewer').fadeOut();
// }

$('#upload_image').change(function(){
    var ext = $(this).val().split('.').pop().toLowerCase();
    if($('#bucket').val() == 'question_attachments' && $.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
        showMsg('Invalid file type is selected.')
    }else{
        // console.log('Please wait...');
        $(".file-upload-form").ajaxForm({dataType: 'json',
            success:function(response){ 
            if(response['status'] == 'success'){
                window.selectedItem = response.item_path;
                window.itemName = response.item_name;
                window.itemSize = response.item_size;
                $.fancybox.close();

            }

        }}).submit();
    }
});
$('#search').hideseek();
$('#search').on("_after", function() {
  if($('li.no-results').length > 0){
    $('li.no-results').removeAttr('item-url').removeClass('item-linked');
  }
});

function unloadPopupBox() { // TO Unload the Popupbox
    $('#popup_box').fadeOut("normal");
    //location.reload();
}   

function loadPopupBox() {   // To Load the Popupbox

    $('#popup_box').fadeIn("normal");       
    // var hgt = $('#viewed_image').height() / 2;
    // alert(hgt);
    // $('#viewed_image').css('margin-top','-'+hgt+'px');
    //location.reload();
}
</script>