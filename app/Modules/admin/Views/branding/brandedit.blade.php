@extends('default')
@section('content')

    <div class="container">
    
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Branding</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form class="form-horizontal" enctype="multipart/form-data" id="upload_form" role="form"  method="POST" action="{{ url('user/brandingupdate/'.$branding->id) }}" >
                            <input type="hidden" name="_token" value="{{ csrf_token()}}" id="csrf_token">
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Select Institution</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="institution_id" id="institution_id">
                                            <option value="{{$institution['id']}}">{{$institution['name']}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-md-4" style="text-align: right;">Header Logo</label>
                                <div class="col-md-5">
                                    <input type="file" name="image" accept="image/*"/>
                                    @if($branding->filepath != "")
                                    <a href= "{{ asset('/data/brandingimages/'.$branding->filepath.'') }}" target="_blank">Preview</a>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Header Background Color</label>
                                <div class="col-md-4">
                                    <div id="" class="input-group colorpicker-component jscolor"> 
                                        <input type="text" name="hbcolor"  value="{{ $branding->header_bg_color }}" class="form-control" /> <span class="input-group-addon"><i></i></span> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Header Text Color</label>
                                <div class="col-md-4">
                                    <div id="" class="input-group colorpicker-component jscolor"> 
                                        <input type="text" name="headertc"  value="{{ $branding->header_text_color }}" class="form-control" /> <span class="input-group-addon"><i></i></span> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Box Header Background Color</label>
                                <div class="col-md-4">
                                    <div id="" class="input-group colorpicker-component jscolor"> 
                                        <input type="text" name="boxhbc"  value="{{ $branding->box_header_bg_color }}" class="form-control" /> <span class="input-group-addon"><i></i></span> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Box Header Text Color</label>
                                <div class="col-md-4">
                                    <div id="" class="input-group colorpicker-component jscolor"> 
                                        <input type="text" name="boxhtcolor"  value="{{ $branding->box_header_text_color }}" class="form-control" /> <span class="input-group-addon"><i></i></span> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required hidden">
                                <label class="col-md-4 control-label">Box Text Color</label>
                                <div class="col-md-4">
                                    <div id="" class="input-group colorpicker-component jscolor"> 
                                        <input type="text" name="btextc"  value="{{ $branding->box_text_color }}" class="form-control" /> <span class="input-group-addon"><i></i></span> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Button Color</label>
                                <div class="col-md-4">
                                    <div id="" class="input-group colorpicker-component jscolor"> 
                                        <input type="text" name="buttonc"  value="{{ $branding->button_bg_color }}" class="form-control" /> <span class="input-group-addon"><i></i></span> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Button Text Color</label>
                                <div class="col-md-4">
                                    <div id="" class="input-group colorpicker-component jscolor"> 
                                        <input type="text" name="buttontc"  value="{{ $branding->button_text_color }}" class="form-control" /> <span class="input-group-addon"><i></i></span> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-4" >
                            <button type="submit" class="btn btn-primary">Update</button>
                                <!-- <button type="reset" class="btn btn-default">Reset</button> -->
                                <button type="reset" value="Reset" onClick="window.location.reload()" class="btn btn-default">Reset
                                </button>
                                <a class="btn btn-danger" href="@if(getRole()=='administrator'){{  url('user/brandings') }}@else {{url('/home')}} @endif">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('assets/js/bootstrap-colorpicker.min.js')}}"></script>
    <link href="{{ asset('css/bootstrap-colorpicker.min.css') }}" rel='stylesheet' type='text/css'> 
    <script> $(function() {
        $('.jscolor').colorpicker({  format: 'hex' }); }); 
    </script> 
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>
@endsection