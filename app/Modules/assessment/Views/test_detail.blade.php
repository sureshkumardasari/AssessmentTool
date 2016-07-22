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
    $(document).ready(function() {
        var _token = $(".hidden-token").val();
        var _ids = '{{ $id }}';
        var ids = _ids.split('-');

        @if ($secs != '0')
        $('#ttr').backward_timer({
            seconds: {{ $secs }},
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


</script>
@endsection