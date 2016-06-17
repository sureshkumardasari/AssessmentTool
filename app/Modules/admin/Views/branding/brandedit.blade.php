@extends('default')

@section('content')
    <script src="{{ asset('js/jscolor.js')}}"></script>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Branding edit</div>
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
                        <form class="form-horizontal" enctype="multipart/form-data" id="upload_form" role="form"  method="POST" action="{{ url('user/update/'.$branding->id) }}" >
                            <input type="hidden" name="_token" value="{{ csrf_token()}}">
                            <div class="form-group required">
                                <label class="col-md-4 control-label">Select Institution</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="institution_id">
                                        <option value="">Select</option>
                                        @foreach($inst_arr as $id=>$val)
                                            <option value="{{ $id }}" {{ ($branding->institution_id ==$id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                 <label class="col-md-4 control-label">Title</label>
                                 <div class="col-md-6">
                                <input id="title" name="title"  type="text" class="form-control" value="{{ $branding->title }}" />
                             </div></div>--}}
                            <div class="form-group">
                                <label class="col-md-4 control-label">Header Logo</label>
                                <div class="col-md-6">
                                    <input type="file" name="image" accept="image/*"/>
                                    <a href= "{{ asset('/data/brandingimages/'.$branding->filepath.'') }}" target="_blank">Preview</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Header-backgroud-color</label>
                                <div class="col-md-6">
                                    <input id="hbc" name="hbcolor" type="text" class="jscolor {onFineChange:'update(this)'}"  class="form-control" value="{{ $branding->header_bg_color }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Header-Text-Color</label>
                                <div class="col-md-6">
                                    <input id="htc" name="headertc"  type="text" class="jscolor {onFineChange:'update(this)'}" class="form-control" value="{{ $branding->header_text_color }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Box-Header-backgroud-color</label>
                                <div class="col-md-6">
                                    <input id="bhbc" name="boxhbc" class="jscolor {onFineChange:'update(this)'}" type="text" class="form-control" value="{{ $branding->box_header_bg_color }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Box-Header-text-color</label>
                                <div class="col-md-6">
                                    <input id="bhtc" name="boxhtcolor" class="jscolor {onFineChange:'update(this)'}" type="text" class="form-control" value="{{ $branding->box_header_text_color }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Box-Text-Color</label>
                                <div class="col-md-6">
                                    <input id="btc"  name="btextc" class="jscolor {onFineChange:'update(this)'}" type="text" class="form-control" value="{{ $branding->box_text_color }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Button-Color</label>
                                <div class="col-md-6">
                                    <input id="bc" name="buttonc" class="jscolor {valueElement: 'color_value'}" type="text" class="form-control" value="{{ $branding->button_color }}" />
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

    <script type="text/javascript">
        $(".upload").click(function(){
            var data=new FormData($('form#upload_form')[0])
            var csrf_token=$('#csrf_token').val();
            $.ajax({
                headers: {"X-CSRF-Token": csrf_token},
                url:"{{url('user/add-category')}}",
                data:data,
                async:false,
                type:'post',
                processData: false,
                contentType: false,
                success:function(response){
                    console.log(response);
                },
            });
        });

@endsection
