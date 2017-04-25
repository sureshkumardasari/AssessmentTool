@extends('default')
@section('header-assets')
@parent
{!! HTML::script(asset('plugins/tinymce/plugins/tiny_mce_wiris/core/display.js')) !!}
{!! HTML::script(asset('plugins/tinymce/tinymce.min.js')) !!}    

@stop
<!--<style>
 .fancybox-overlay{z-index: 99999 !important;}
 #fancybox-loading{z-index: 99999 !important;}

</style>-->
@section('content')

<div class="container-fluid">
 <div class="row">
  <div class="col-md-8 col-md-offset-2">
   <div class="panel panel-default">
   <?php 
    $operation = ($passage->id) ? "Modify" : "Create";

   ?>
    <div class="panel-heading">{{$operation}} Passage</div>
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

     <form class="form-horizontal" role="form" method="POST" action="{{ url('/resources/passageupdate') }}">
      <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
      <input type="hidden" name="id" value="{{ $passage->id }}">
      @if ($passage->id > 0)
      <div class="form-group required">
       <label class="col-md-2 control-label">Institution</label>
       <div class="col-md-10">
        <select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">
         <option value="0">--Select Institution--</option>
          @foreach($inst_arr as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach 
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Category</label>
       <div class="col-md-10">
        <select class="form-control" name="category_id" id="category_id" onchange="change_category()">
         <option value="0">--Select Category--</option>
          @foreach($category as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $sCategory) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Subject</label>
       <div class="col-md-10">
        <select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">
         <option value="0">--Select Subject--</option>
          @foreach($subjects as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $sSubject) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Lessons</label>
       <div class="col-md-10">
        <select class="form-control" name="lessons_id" id="lessons_id">
         <option value="0">--Select Lessons--</option>
         @foreach($lessons as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $sLesson) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label" >Passage Title</label>
       <div class="col-md-10">
        <input type="text" class="form-control" name="passage_title" id="passage_title" value="{{ $passage->title }}">
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label" >Passage Text</label>
       <div class="col-md-6">
        <textarea class="form-control" id="passagetext"   name="passage_text"  style="max-width: 150% !important;" >{{ $passage->passage_text }}</textarea>
       </div>
      </div>
       <div class="form-group required">
       <label class="col-md-2 control-label">Passage Lines</label>
       <div class="col-md-6">
        <textarea class="form-control" id="passagelines" name="passage_lines" style="max-width: 150% !important;">{{ $passage->passage_lines }}</textarea>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Status</label>
       <div class="col-md-6">
      @if($status == 1)
        <input type="radio" class="" name="status" id="status_yes" value="1" checked="checked"> Active
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="0"> Inactive
      @else
      <input type="radio" class="" name="status" id="status_yes" value="1" > Active
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="0" checked="checked"> Inactive
      @endif
       </div>
      </div>
      @else
      <div class="form-group required">
       <label class="col-md-2 control-label">Institution</label>
       <div class="col-md-10">
        <select class="form-control" name="institution_id" id="institution_id" onchange="change_institution()">
         <option value="0">--Select Institution--</option>
          @foreach($inst_arr as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $institution_id) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach 
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Category</label>
       <div class="col-md-10">
        <select class="form-control" name="category_id" id="category_id" onchange="change_category()">
         <option value="0">--Select Category--</option>
         <!--  @foreach($category as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $sCategory) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach -->
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Subject</label>
       <div class="col-md-10">
        <select class="form-control" name="subject_id" id="subject_id" onchange="change_lessons()">
         <option value="0">--Select Subject--</option>
         <!--  @foreach($subjects as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $sSubject) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach -->
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Lessons</label>
       <div class="col-md-10">
        <select class="form-control" name="lessons_id" id="lessons_id">
         <option value="0">--Select Lessons--</option>
         <!-- @foreach($lessons as $id=>$val)
         <option value="{{ $id }}" {{ ($id == $sLesson) ? 'selected = "selected"' : '' }}>{{ $val }}</option>
         @endforeach -->
        </select>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label" >Passage Title</label>
       <div class="col-md-10">
        <input type="text" class="form-control" name="passage_title" id="passage_title" value="{{ $passage->title }}">
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label" >Passage Text</label>
       <div class="col-md-6">
        <textarea class="form-control" id="passagetext"   name="passage_text"  style="max-width: 150% !important;" >{{ $passage->passage_text }}</textarea>
       </div>
      </div>
       <div class="form-group required">
       <label class="col-md-2 control-label">Passage Lines</label>
       <div class="col-md-6">
        <textarea class="form-control" id="passagelines" name="passage_lines" style="max-width: 150% !important;">{{ $passage->passage_lines }}</textarea>
       </div>
      </div>
      <div class="form-group required">
       <label class="col-md-2 control-label">Status</label>
       <div class="col-md-6">
        <input type="radio" class="" name="status" id="status_yes" value="1" checked="checked"> Active
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="" name="status" id="status_no" value="0"> Inactive
       </div>
      </div>
      @endif
      <div class="form-group">
       <div class="col-md-6 col-md-offset-4">
       <button  class="btn btn-primary  fancybox fancybox.ajax" onclick="popup()"> Preview </button>
        <button type="submit" class="btn btn-primary">
         Submit
        </button>
        <a type="Cancel"  class="btn btn-danger"  href="{{  url('/resources/passage/') }}">Cancel</a>
       </div>
      </div>
     </form>
    </div>
   </div>
  </div>
 </div>
</div>
<script>
  var elfinderRoute = '{{route('elfinder.tinymce4')}}';
  var fileBrowser = '{{route('launchFileBrowser',['question_attachments'])}}';
  var js = document.createElement("script");
  js.type = "text/javascript";
  js.src = "{{ asset('plugins/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=mathml') }}";
  document.head.appendChild(js);
 </script>
 
<script type="text/javascript">
     $(document).ready(function(){
     setTimeout(function(){
         var csrf=$('Input#csrf_token').val();
         $('#flash').fadeOut();
     }, 5000);
 })
 </script>
 {!! HTML::script(asset('js/custom/passage.js')) !!}
@include('resources::question.qst_js_validation')

@endsection