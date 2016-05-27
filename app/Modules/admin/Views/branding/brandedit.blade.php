@extends('default')

@section('content')
<script src="{{ asset('js/jscolor.js')}}"></script>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Branding edit</div>
                <div class="panel-body">
                <form class="form-horizontal" enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="{{ url('user/brandupdate/'.$branding->id) }}" >
                
                <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="form-group ">
                                <label class="col-md-4 control-label">Select Institution</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="institution_id" id="institution_id">
                                        <option value="0">All</option>
                                        @foreach($inst_arr as $id=>$val)
                                        <option value="{{ $id }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Title</label>
                                <div class="col-md-6">
                               <input id="title" name="title"  type="text" class="form-control" value="{{ $branding->title }}" />
                            </div></div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Header Logo</label>
                                 <div class="col-md-6">
                                 <input type="file" name="logo" accept="image/*"/>
                                 </div>
                            </div>
                           
                            <div class="form-group">
                                <label class="col-md-4 control-label">Header-backgroud-color</label>
                                <div class="col-md-6">
                                <input id="hbc" name="hbc" type="text" class="jscolor {onFineChange:'update(this)'}"  class="form-control" value="{{ $branding->header_bg_color }}" />
                                
                    </div>
                            </div>
                            <div class="form-group">
                            <label class="col-md-4 control-label">Header-Text-Color</label>
                            <div class="col-md-6">
                                <input id="htc" name="htc"  type="text" class="jscolor {onFineChange:'update(this)'}" class="form-control" value="{{ $branding->header_text_color }}" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="col-md-4 control-label">Box-Header-backgroud-color</label>
                            <div class="col-md-6">
                                <input id="bhbc" name="bhbc" class="jscolor {onFineChange:'update(this)'}" type="text" class="form-control" value="{{ $branding->box_header_bg_color }}" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="col-md-4 control-label">Box-Header-text-color</label>
                            <div class="col-md-6">
                                <input id="bhtc" name="bhtc" class="jscolor {onFineChange:'update(this)'}" type="text" class="form-control" value="{{ $branding->box_header_text_color }}" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="col-md-4 control-label">Box-Text-Color</label>
                            <div class="col-md-6">
                                <input id="btc"  name="btc" class="jscolor {onFineChange:'update(this)'}" type="text" class="form-control" value="{{ $branding->box_text_color }}" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="col-md-4 control-label">Button-Color</label>
                            <div class="col-md-6">
                                <input id="bc" name="bc" class="jscolor {valueElement: 'color_value'}" type="text" class="form-control" value="{{ $branding->button_color }}" />
                            </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="reset" class="btn btn-default">Reset</button>
                                    <a class="btn btn-default" href="{{  url('user/branding/brandview') }}">Cancel</a>
                                </div>
                            </div>
                     
                            </form>                     
                    </div>
                    
                                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <script>
    var userSearchRoute = "{{URL::route('usersearch')}}";
</script> -->
<script>
   
    $('#hbc, #htc, #bhbc,#bhtc').ColorPicker({
    onSubmit: function(hsb, hex, rgb, el) {
        $(el).val(hex);
        $(el).ColorPickerHide();
    },
    onBeforeShow: function () {
        $(this).ColorPickerSetColor(this.value);
    }
})
.bind('keyup', function(){
    $(this).ColorPickerSetColor(this.value);
});
     
</script>


<!-- <script type="text/javascript">
/*$(document).ready(function(){
    $('#hbc').colorpicker();
    $('#htc').colorpicker();
    $('#bhbc').colorpicker();
    $('#bhtc').colorpicker();
    $('#btc').colorpicker();
    $('#bc').colorpicker();
    $('#logo').colorpicker();
});
    */
 $(".addbtn").click(function(){
   $.ajax({
  url:'/add-catagory',
  data:{
    logo:new FormData($("#upload_form")[0]),
    },
  dataType:'json',
  async:false,
  type:'post',
  processData: false,
  contentType: false,
  success:function(response){
    console.log(response);
  },
 });
 });
</script>
<style type="text/css">
 
/* hides controls for dropzone.js */
.single-dropzone {
  .dz-image-preview, .dz-file-preview {
    display: none;
  }
}
</style>
 -->
@endsection