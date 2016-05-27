@extends('default')

@section('content')
<script src="{{ asset('js/jscolor.js')}}"></script>
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Branding</div>
				<div class="panel-body">
				<form class="form-horizontal" enctype="multipart/form-data" id="upload_form" role="form" method="POST" action="{{ url('user/branding/brandd') }}" >
				
				<input type="hidden" name="_token" value="{{ csrf_token()}}">
							<div class="form-group ">
								<label class="col-md-4 control-label">Select Institution</label>
								<div class="col-md-6">
									<select class="form-control" name="institution_id" id="institution_id">
									<option disabled selected hidden>--Select --</option>
                                        @foreach($inst_arr as $id => $name)
                                            <option value="{{$id}}">{{$name}}</option>
                                        @endforeach
									</select>
								</div>
							</div>
 							
							 <div class="form-group">
					            <label class="col-md-4 control-label">Title</label>
					            <div class="col-md-6">
					           <input id="title" name="title"  type="text" class="form-control" value="" />
					        </div></div>
							<div class="form-group">
					            <label class="col-md-4 control-label">Header Logo</label>
					             <div class="col-md-3">
					             <input type="file" name="logo" accept="image/*"/>
					             </div>
					              <div class="col-md-3">
					             <input type="button" name="logo"  class="upload"  value="upload" />
					             </div>
					        </div>
							<div class="form-group">
								<label class="col-md-4 control-label">Header-backgroud-color</label>
								<div class="col-md-6">
								<input id="hbc" name="hbc" type="text"  class="jscolor {onFineChange:'update(this)'}" value="" />
								
  								</div>
							</div>
							<div class="form-group">
							<label class="col-md-4 control-label">Header-Text-Color</label>
							<div class="col-md-6">
								<input id="htc" name="htc"  type="text" class="jscolor {onFineChange:'update(this)'}" value="" />
							</div>
							</div>
							<div class="form-group">
							<label class="col-md-4 control-label">Box-Header-backgroud-color</label>
							<div class="col-md-6">
								<input id="bhbc" name="bhbc"  type="text"  class="jscolor {onFineChange:'update(this)'}" class="form-control" value="" />
							</div>
							</div>
							<div class="form-group">
							<label class="col-md-4 control-label">Box-Header-text-color</label>
							<div class="col-md-6">
								<input id="bhtc" name="bhtc" class="jscolor {onFineChange:'update(this)'}" type="text" class="form-control" value="" />
							</div>
							</div>
							<div class="form-group">
							<label class="col-md-4 control-label">Box-Text-Color</label>
							<div class="col-md-6">
								<input id="btc"  name="btc" type="text" class="jscolor {onFineChange:'update(this)'}" value="" />
							</div>
							</div>
							<div class="form-group">
							<label class="col-md-4 control-label">Button-Color</label>
							<div class="col-md-6">
								<input id="bc" name="bc"  type="text"  class="jscolor {valueElement: 'color_value'}" value="" />
							</div>
							</div>
							
					 <div class="col-md-6 col-md-offset-4" >
					  <button type="submit"  class="btn btn-primary" class="addbtn">Save</button>
			           
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
function update(jscolor) {
    // 'jscolor' instance can be used as a string
    document.getElementById('hbc').style.backgroundColor = '#' + jscolor
    document.getElementById('htc').style.backgroundColor = '#' + jscolor
    document.getElementById('bhbc').style.backgroundColor = '#' + jscolor
    document.getElementById('bhtc').style.backgroundColor = '#' + jscolor
    document.getElementById('btc').style.backgroundColor = '#' + jscolor
    document.getElementById('hbc').style.backgroundColor = '#' + jscolor
    document.getElementById('bc').style.backgroundColor = '#' + jscolor
}
</script>
<script type="text/javascript">
 $(".upload").click(function(){
   $.ajax({
  url:'user/add-catagory',
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
@endsection