
@extends('default')
@section('content')
<style type="text/css">
    
    #question-answers {
        min-height: 1154px;
        overflow-y: auto;
        /*width: 255px;*/
        min-width: 300px;
        max-width: 350px;
    }
    .test-form .two-column .column.column-count {width: 817px;}
    #slide-show-container p img {display: block; margin: auto;width: 100%;}
    .pagination {display: none;}
    .test-form .two-column .column .ans-sheet li {float: left; padding: 5px 9px 5px 0; } 
    .sc_1 {padding: 5px 5px 5px 0 !important;}
    .sc_2 {padding:0 !important;}
    .test-form .two-column .column .ans-sheet {display: block; width: 100%;}
    .ssi-tip {z-index: 10000;}
    .questions_passage p {line-height: 28px; margin: 0 0 17px;}    
    section .tab_panel{ margin-top: 0px; }
</style>
<script>window.replacements = [];</script>
<!-- <script src="http://code.jquery.com/jquery-1.4.2.min.js"></script> -->

<div class="container">
  
    <div class="col-md-12 col-md-offset-0">
      <div class="panel panel-default">
        <div class="panel-heading">{{isset($assessment) ? $assessment->name : ''}}         
        </div>
        <div class="panel-body">
          <!--  -->
            <div> 
                <div class="col-md-3">
                    @if ($secs != '0')
                    <span class="time test-timer">Remaining Time: <span class='timer' id='ttr'>00:00:00</span></span>
                    @endif
                </div>
                <div class="col-md-9">
                    <a class="btn btn-primary right" id="next-btn" href="javascript:void(0)">Next</a>
                    <a class="btn btn-primary right" id="prev-btn" href="javascript:void(0)" style="display: none;">Previous</a>                
                </div>             
            </div> 

            <div> 
                <div class="col-md-3" id='question-answers'>
                    <h3>Answer Sheet</h3>
                    {!! ($ansPanel == "undefined") ? '' : $ansPanel !!}
                </div>
                <div class="col-md-9" id="slide-show-container" style="width:70%;">
                    @foreach($filesArr as $path)
                        <p class="test-page">
                            <img src="{{ $path }}" alt="" class='paginate-img'>
                        </p>
                    @endforeach         
                </div>             
            </div> 
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <a href="{{ route('submit-confirm-popup', array('id' => $id)) }}" class="btn btn-primary fancybox fancybox_no_close_click fancybox.ajax" id='btn-submit'>Submit</a>   

                </div>
            </div>           
          <!--  -->
        </div>
      </div>
    </div>
  
</div>
<input type="hidden" name="_token" class="hidden-token" value="{{ csrf_token() }}">
{!! HTML::script(asset('plugins/jquery-backward-timer.min.js')) !!}
{!! HTML::script(asset('plugins/jPaginate.js')) !!}
<script type="text/javascript">

function getAlert(){
   //   alert($('#alert').val());
     var retVal = confirm("Exam will be submitted if you perform this action?");
               if( retVal == true ){
                 // document.write ("User wants to continue!");
                 //return true;
                  stopTicking();
                    var _token = $(".hidden-token").val();
                    
         
                    $.ajax({
                        url: "{{ route('submit-test') }}",
                        data: {id: "{{$id}}", retaking: '{{ $retaking }}',_token: _token},
                        method: "POST",
                        success: function(response) {

                            var redirectUrl = response;
                            @if (!empty($from) && $from == 'lessons')
                                <?php $_id=getFirstTestTypeId(); ?>
                                redirectUrl = "{{ route('resourcelisting',array('student',$_id))}}";
                            @endif
                            window.location.href = redirectUrl;
                        }
                    });
               }
               else{
                  //document.write ("User does not want to continue!");
                  return false;
               }


}
    $(document).ready(function() {
        var _token = $(".hidden-token").val();
        var _ids = '{{ $id }}';
        var ids = _ids.split('-');
        var secounds={{$secs}}*60;
         @if ($secs != '0')
        $('#ttr').backward_timer({
            seconds: secounds,
            on_exhausted: function() {
                submitTest(true);
            }
        });
        $('#ttr').backward_timer('start');
        @endif

        @if (!isset($from) || empty($from))
        // update test time every 30 secs
        window.timerUpdate = setInterval(function() {
                                $.ajax({
                                    url: '{{ route("update-test-time") }}',
                                    data: {id: "{{$id}}",_token: _token},
                                    method: 'POST',
                                    success: function(response) {
                                        if (response == "Complete" || response == "Completed") {
                                            submitTest(true);
                                        }
                                    }
                                });
                            }, 30000);
        @endif
        
        if($('#slide-show-container .test-page').length == 1 || $('#slide-show-container .test-page').length == 0){
            $('#next-btn').hide();
            $('#prev-btn').hide();
        }else {
            $("#slide-show-container").jPaginate({items: 1, minimize: true,cookies: false});
        }
            
       // $("#slide-show-container").jPaginate({items: 1, minimize: true,cookies: false});

        $(document).on('click', '#next-btn', function() {
            $('.goto_next').trigger('click');
            $('.two-column').find('br').remove();
            tooglePrevNextBtns();
        });

        $(document).on('click', '#prev-btn', function() {
            $('.goto_previous').trigger('click');
            $('.two-column').find('br').remove();
            tooglePrevNextBtns();
        });

        $('.single_sel_radio').on('click', function() {
            var name = $(this).attr('name');
            var val = $('input[name="'+ name +'"]:checked').val();
            var opts = val.split('_');
            //$(this).closest('li').addClass('active');
            saveAnswer([{
                'SubsectionQuestionId': opts[1],
                'QuestionAnswerId': opts[2],
                'AssessmentAssignmentId': ids[1],
                'AssessmentId': ids[0],
                'Option': opts[3],
                'QuestionAnswerText': null,
                'UserId': '{{Auth::user()->id}}',
            }]);
        });

        $('.multi_chk_box').on('click', function() {
            var name = $(this).attr('name');
            var vals = $('input[name="'+ name +'"]:checked').map(function() {  return $(this).val() });
            
            var credentials = [];
            if (vals.length > 0) {
                $.each(vals, function(index, elem){
                    var opts = elem.split('_');
                    credentials.push({
                        'SubsectionQuestionId': opts[1],
                        'QuestionAnswerId': opts[2],
                        'AssessmentAssignmentId': ids[1],
                        'AssessmentId': ids[0],
                        'Option': opts[3],
                        'QuestionAnswerText': null,
                        'UserId': '{{Auth::user()->id}}',
                    });
                });
            } else {                
                var vals = $('input[name="'+ name +'"]').map(function() {  return $(this).val() });
                $.each(vals, function(index, elem){
                    var opts = elem.split('_');
                    credentials.push({
                        'SubsectionQuestionId': opts[1],
                        'QuestionAnswerId': null,
                        'AssessmentAssignmentId': ids[1],
                        'AssessmentId': ids[0],
                        'Option': null,
                        'QuestionAnswerText': null,
                        'UserId': '{{Auth::user()->id}}',
                    });
                });
            }

            saveAnswer(credentials);
        });

        $('.oer_txtbox').on('blur', function() {
            var val = $(this).data('value');

            var opts = val.split('_');
            saveAnswer([{
                'SubsectionQuestionId': opts[1],
                'QuestionAnswerId': 0,
                'AssessmentAssignmentId': ids[1],
                'AssessmentId': ids[0],
                'Option': null,
                'QuestionAnswerText': $(this).val().trim(),
                'OriginalAnswerValue' : $(this).data('originalAnswer'),
                'UserId': '{{Auth::user()->id}}',
            }]); 
        });

        $(document).on('click', '#btn-save', function() {
            
            var val = $('textarea[name="essay_text"]').val().trim().replace(/\n\r?/g, '<br />');
            saveAnswer([{
                'SubsectionQuestionId': $('textarea[name="essay_text"]').data('subsecquestionid'),
                'QuestionAnswerId': 0,
                'AssessmentAssignmentId': ids[1],
                'AssessmentId': ids[0],
                'Option': null,
                'QuestionAnswerText': val,
                'UserId': '{{Auth::user()->id}}',
                //'question_type':'Fill in the blank'
            }]); 
        });

        $(document).on('click', '#btn-fib-save', function() {
            
            var val = $('textarea[name="fib_text"]').val().trim().replace(/\n\r?/g, '<br />');
            saveAnswer([{
                'SubsectionQuestionId': $('textarea[name="fib_text"]').data('subsecquestionid'),
                'QuestionAnswerId': 0,
                'AssessmentAssignmentId': ids[1],
                'AssessmentId': ids[0],
                'Option': null,
                'QuestionAnswerText': val,
                'UserId': '{{Auth::user()->id}}',
                'question_type':'Fill in the blank'
            }]); 
        });

        window.essay_isDirty = false;
        $(document).on("change", 'textarea[name="essay_text"]', function(){
            window.essay_isDirty = true;
        });

        $(document).on('click', '.btn-close', function() {            
            if (window.essay_isDirty) {

                var tipTarget = $(this);
                myDialog(tipTarget, {
                      headerText: 'Confirm Close',
                      message: 'Are you sure you want to leave this page, as you have unsaved work?',
                      buttons: [
                        {
                            text: 'Yes',
                            className: 'btn-delete',
                            click: function(e) {  
                                e.preventDefault();
                                $.fancybox.close();
                            }
                        }, {
                            text: 'No',
                            className: 'btn-cancel',
                            click: function(e) {
                                e.preventDefault();
                            }
                        }
                     ]
                });
            } else {
                $.fancybox.close();
            }
        });


    });

    function submitTest(auto) {
        stopTicking();
        var _token = $(".hidden-token").val();
        $.ajax({
            url: "{{ route('submit-test') }}",
            data: {id: "{{$id}}", retaking: '{{ $retaking }}',_token: _token},
            method: "POST",
            success: function(response) {

                var redirectUrl = response;
                @if (!empty($from) && $from == 'lessons')
                    <?php $_id=getFirstTestTypeId(); ?>
                    redirectUrl = "{{ route('resourcelisting',array('student',$_id))}}";
                @endif
                window.location.href = redirectUrl;
            }
        });
    }

    // stop death race and clear interval
    function stopTicking() {
        clearInterval(window.timerUpdate);
        $('#ttr').backward_timer('cancel');
    }

    function getUnansweredCount() {

        var count = 0;
        // get multiple choices count
        $('.multiple_choice').each(function(index, elem) {
            if ($(elem).find('.mc:checked').size() == 0) {
                count++;
            }
        });
        // get open ended response count
        $('.oer_txtbox').each(function(index, elem) { 
            if($(elem).val().trim() == "") 
                ++count;
        });
        // get free form count
        $('.ff_essay').each(function(index, elem) {
            if($(elem).data('essay').trim() == "") 
                ++count; 
        });

        return count;
    }
    
    function saveAnswer(credentials) {
        var _token = $(".hidden-token").val();
        $.ajax({
            url: '{{ route("save-answer") }}',
            data: {credentials: credentials,_token: _token, id: "{{$id}}", retaking: '{{ $retaking }}'},
            method: 'POST',
            success: function(response) {
                $.fancybox.close();
            }
        });
    }

    if ($('.page').length == 1) {
        $('#next-btn').hide();
    }

    function tooglePrevNextBtns() {
        var curIndex = $('#slide-show-container p:visible').index();
        var total = $('#slide-show-container p').length;

        if (total == 1 || total == 0) {
            $('#next-btn').hide();
            $('#prev-btn').hide();
        } else {
            if (curIndex == (total - 1)) {
                $('#next-btn').hide();
                $('#prev-btn').show();
            } else {
                $('#next-btn').show();
                $('#prev-btn').show();
            }

            if (curIndex == 0) {
                $('#next-btn').show();
                $('#prev-btn').hide();   
            }            
        }

    }
    $(document).ready(function(){
         window.essay_isDirty = false;

    });
   /* window.onbeforeunload = function() { return "Are you sure you want to leave this page."; };*/
</script>
    <script type="text/javascript">
    (function (global) { 

    if(typeof (global) === "undefined") {
        throw new Error("window is undefined");
    }

    var _hash = "!";
    var noBackPlease = function () {
        global.location.href += "#";

        // making sure we have the fruit available for juice (^__^)
        global.setTimeout(function () {
            global.location.href += "!";
        }, 50);
    };

    global.onhashchange = function () {
        if (global.location.hash !== _hash) {
            global.location.hash = _hash;
        }
    };

    global.onload = function () {            
        noBackPlease();

        // disables backspace on page except on input fields and textarea..
        document.body.onkeydown = function (e) {
            var elm = e.target.nodeName.toLowerCase();
            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                e.preventDefault();
            }
            // stopping event bubbling up the DOM tree..
            e.stopPropagation();
        };          
    }

})(window);
/*window.onbeforeunload = function() {
            return "you can not refresh the page";
        }*/
        //window.onbeforeunload = function () {return false;}
</script>

<Script>
/*function disableKey(event) {
  if (!event) event = window.event;
  if (!event) return;
 
  var keyCode = event.keyCode ? event.keyCode : event.charCode;
 
  //window.status = keyCode;
  //alert(keyCode);
  
  // keyCode for F% on Opera is 57349 ?!
  
  if (keyCode == 116) {
   window.status = "F5 key detected! Attempting to disabling default response.";
   window.setTimeout("window.status='';", 2000);
 
   // Standard DOM (Mozilla):
   if (event.preventDefault) event.preventDefault();
 
   //IE (exclude Opera with !event.preventDefault):
   if (document.all && window.event && !event.preventDefault) {
     event.cancelBubble = true;
     event.returnValue = false;
     event.keyCode = 0;
   }
 
   return false;
  }
} 
 
function setEventListener(eventListener) {
  if (document.addEventListener) document.addEventListener('keypress', eventListener, true);
  else if (document.attachEvent) document.attachEvent('onkeydown', eventListener);
  else document.onkeydown = eventListener;
}
 
function unsetEventListener(eventListener) {
  if (document.removeEventListener) document.removeEventListener('keypress', eventListener, true);
  else if (document.detachEvent) document.detachEvent('onkeydown', eventListener);
  else document.onkeydown = null;
}*/
/*function checkKeyCode(evt)
{

var evt = (evt) ? evt : ((event) ? event : null);
var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
if(event.keyCode==116)
{
evt.keyCode=0;
return false
}
}
document.onkeydown=checkKeyCode;*/
/*function disable_f5(e)
{
  if ((e.which || e.keyCode) == 116)
  {
      e.preventDefault();
  }
}

$(document).ready(function(){
    $(document).bind("keydown", disable_f5);    
});*/

/*$(function() {
  if (!navigator.userAgent.toLowerCase().match(/iphone|ipad|ipod|opera/)) {
    return;
  }
  $('a').bind('click', function(evt) {
    var href = $(evt.target).closest('a').attr('href');
    if (href !== undefined && !(href.match(/^#/) || href.trim() == '')) {
      var response = $(window).triggerHandler('beforeunload', response);
      if (response && response != "") {
        var msg = response + "\n\n"
          + "Press OK to leave this page or Cancel to stay.";
        if (!confirm(msg)) {
          return false;
        }
      }
      window.location.href = href;
      return false;
     }
  });
});*/
</script>

@endsection
<?php session()->forget('starttest');
?>