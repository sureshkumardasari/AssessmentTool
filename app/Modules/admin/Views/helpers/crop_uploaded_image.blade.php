<style>
     #partialContainer {
        position: relative;
    }
    
    .lbl-img-bbtn {
        width: 86px;
        background: #ecf0f1 !important;
        cursor: pointer;
        position: absolute;
        top: 215px;
        left: 93px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        z-index: 1;
        -webkit-box-shadow: 0px 3px 0px 0px #dddddd;
        -moz-box-shadow: 0px 3px 0px 0px #dddddd;
        box-shadow: 0px 3px 0px 0px #dddddd;
        font: 14px "droid_sansbold";
        color: #6d6f75;
        line-height: 30px;
        height: 30px;
        text-align: center;
    }
</style>
<!--Form to upload profilepic using ajax-->
@if(!empty($pic_data['image']) || empty($image400x400fromS3))
    {!! Form::open(array('route' => 'upload_image','files'=>true, 'id' =>'ajaxuploadprofilepic')) !!}
        <a href="javascript:void(0)" class="change_button">
            <label for="image" style="" id="id_launchUploader" class="lbl-img-bbtn">
                Add
                <input type='file' name='image' class="uploadImage" id='image' style="margin: 0 0 10px;  position: relative;  left: -20000000px;" />
            </label>
        </a>
    {!! Form::close() !!}
@endif
<!--Form to upload profilepic using ajax-->
<section class="msgs_box hide" id='container'>
    <section class="drop_downs no-bdr">
        <!--place your body content here.-->
        <div class="score_pop w895 mb0">
            <h1 class="txt_32_b pL30 Lht62 bdr_bottom">Profile Photo</h1>
            <div class="prsn_info pL35 w337 pt35 mL8 brd_r2 fltL pb30">
                <p class="mb23">
                    <label class="mr0 w200 txt_18_b fltL">Add a Photo</label>
                <div class="clr"></div>
                </p>
                <p class="mb23 pt40">
                    <img id="image_loader_popup" style="position:absolute; margin-left: 70px; margin-top: 97px;" src="/assets/images/fancybox_loading@2x.gif"/>
                <div id="preview-pane">
                    <div class="preview-container">
                        @if(!empty($pic_data['image']) && !empty($image400x400fromS3))
                        <img src="{{$image400x400fromS3}}"  class="jcrop-preview"/>
                        @else
                        <img src=""  class="jcrop-preview"/>
                        @endif
                    </div>
                </div>
                <span class="image_error error fltL mt10" style="font-size: 14px"></span>
                <div class="clr"></div>
                <span class="dsply_b pt16">
                    You can upload a JPG, JPEG, GIF or PNG file<br />
                    (File size limit  is 4MB)
                </span>
                <div class="clr"></div>
                </p>
                <div class="clr"></div>
                <div class="btns_all fltL mL0 mt5 mb25 pos_rel">
                    <!--<a href="javascript:;" class="create_new_btn mR0 mt0 mL0 pL55 pR55 launchUploaderPopup" id="id_launchUploaderPopup">Browse</a>-->
                    {!! Form::open(array('route' => 'upload_image','files'=>true, 'id' =>'ajaxuploadprofilepicpopup')) !!}
                    <a href="javascript:;" class="create_new_btn mR0 mt0 mL0 pL55 pR55 uploadImagepopup">Browse
                        <input type='file' name='image' class="uploadImageFromPopup brows_btn" id='image_new' />
                    </a>
                    {!! Form::close() !!}
                </div>
                <div class="btns_all fltL mL0 mt5 mb25 clr">
                    <a href="javascript:;" id='saveImage' class="upload_btn clr mL0 pL65 pR65 mt3" >Save</a>
                    <a href="javascript:;" onclick='$.fancybox.close()'  class="cncl_link pt13 popup-img-cancel">Cancel</a>
                </div>
                <div class="clr"></div>
            </div>
            <div class="crop_panel fltL mt35 mL35">
                @if(!empty($pic_data['image']) && !empty($image400x400fromS3))
                <img src="{{ $image400x400fromS3 }}" id="target"/>
                @else
                <img src="" id='target'/>
                @endif
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </section>
    <div class="clr"></div>
</section>