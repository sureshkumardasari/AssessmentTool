<style>
     #partialContainer {
        position: relative;
    }
    
    .lbl-img-bbtn {
        width: 86px;
        cursor: pointer;
        position: absolute;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        z-index: 1;
        /*-webkit-box-shadow: 0px 3px 0px 0px #dddddd;
        -moz-box-shadow: 0px 3px 0px 0px #dddddd;
        background: #ecf0f1 !important;
        box-shadow: 0px 3px 0px 0px #dddddd;
        font: 14px "droid_sansbold";
        color: #6d6f75;
        line-height: 30px;
        top: 215px;
        left: 93px;*/
        height: 30px;
        text-align: center;
    }
</style>
<!--Form to upload profilepic using ajax-->
{{-- @if(!empty($pic_data['image']) || empty($image400x400fromS3)) --}}
@if(empty($image400x400fromS3))
    {!! Form::open(array('route' => 'upload_image','files'=>true, 'id' =>'ajaxuploadprofilepic')) !!}
        <a href="javascript:void(0)" class="change_button">
            <label for="image" style="" id="id_launchUploader" class="lbl-img-bbtn btn btn-primary btn-sm">
                Add
                <input type='file' name='image' class="uploadImage" id='image' style="margin: 0 0 10px;  position: relative;  left: -20000000px;"/>
            </label>
        </a>
    {!! Form::close() !!}
@endif
<!--Form to upload profilepic using ajax-->
<div class="panel panel-default" id='container' style="display:none;">
    <div class="panel-heading">Profile Photo
    </div>
    <div class="panel-body">
        <div class="col-md-5">
            <div>
                <div class="">
                    <label class="control-label">Add a Photo</label>  <br />
                    <img id="image_loader_popup" style="position:absolute; margin-left: 70px; margin-top: 97px;" src="{{asset('/images/fancybox_loading@2x.gif')}}"/>                       
                </div>
                <div id="preview-pane">
                    <div class="preview-container">
                        @if(!empty($pic_data['image']) && !empty($image400x400fromS3))
                        <img src="{{$image400x400fromS3}}"  class="jcrop-preview"/>
                        @else
                        <img src=""  class="jcrop-preview"/>
                        @endif
                    </div>
                </div>

                <span class="image_error error fltL mt10" style="font-size: 14px;display:block;"></span>
                <span class="dsply_b pt16">
                    You can upload a JPG, JPEG, GIF or PNG file<br />
                    (File size limit  is 4MB)
                </span>
            </div>
            <div class="btns_all fltL mL0 mt5 mb25 pos_rel">
                <!--<a href="javascript:;" class="create_new_btn mR0 mt0 mL0 pL55 pR55 launchUploaderPopup" id="id_launchUploaderPopup">Browse</a>-->
                {!! Form::open(array('route' => 'upload_image','files'=>true, 'id' =>'ajaxuploadprofilepicpopup')) !!}
                <a href="javascript:;" class="create_new_btn mR0 mt0 mL0 pL55 pR55 uploadImagepopup">
                    <input type='file' name='image' class="uploadImageFromPopup btn btn-primary btn-sm" id='image_new' />
                </a>
                {!! Form::close() !!}
            </div>
            <div class="btns_all fltL mL0 mt5 mb25 clr">
                <a href="javascript:;" id='saveImage' class="btn btn-primary btn-sm upload_btn" >Save</a>
                <a href="javascript:;" onclick='$.fancybox.close()'  class="btn btn-danger btn-sm cncl_link popup-img-cancel">Cancel</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="crop_panel fltL mt35 mL35">
                @if(!empty($pic_data['image']) && !empty($image400x400fromS3))
                <img src="{{ $image400x400fromS3 }}" id="target"/>
                @else
                <img src="" id='target'/>
                @endif
            </div>
        </div>
    </div>
</div>