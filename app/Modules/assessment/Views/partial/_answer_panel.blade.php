<style type="text/css">
    input[type="radio"].customradio:checked + .custom-radio {
        border-radius: 100%;
        border-color: #24799C;
    }
    input[type="radio"].customradio + .custom-radio:before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: #24799C;
        border-radius: 100%;
        opacity: 0;
    }
    .custom-radio {
        width: 20px;
        height: 20px;
    }
    .checkbox-bg input[type="radio"].customradio + .custom-radio:before { display: none; }
    .checkbox-bg input[type="radio"].customradio:checked + .custom-radio {
        color: #fff;
        background: #24799C;
        border-color: #24799C;
        cursor: pointer;
    }
    .checkbox-bg .custom-radio label {
        cursor: pointer;
    }
</style>
{{--*/ 
    $_answersBullets = generateBullets($bulletType);
    $ansTable = $retaking ? 'user_answers_retake' : 'user_answers';
/*--}}
@foreach($questions as $index => $question)    
    
        {{--*/
            $answersBullets = $_answersBullets[ $index % 2 ];
        /*--}}
        @if(in_array($question['qbank']['question_type']['Display'], array('Multiple Choice- Single Answer')))
        <ul class="ans-sheet multiple_choice checkbox-bg">
        	<li>{{ str_pad(++$index, 2, '0', STR_PAD_LEFT) }}</li>
            
            {{--*/ $counter = 0; /*--}}
            @foreach($question['qbank']['answers'] as $answer)
            @if ($counter < 5)
            	<li class='sc_1' data-v='{{ $question['Id'] }}'>
                    <label>
                    	
                        <input type="radio" id="01A_{{ $answer['Id'] }}" name="single_sel_{{ $question['Id'] }}" value="_{{ $question['Id'] }}_{{ $answer['Id'] }}_{{ $answersBullets[$counter] }}" class="customradio single_sel_radio mc" {{ (isset($question[$ansTable][0]['QuestionAnswerId']) && ($question[$ansTable][0]['QuestionAnswerId'] == $answer['Id'])) ? 'checked' : '' }} />
                        <span class="custom-radio" style="font-size:14px; margin-right:0; margin-top:-1px; text-align:center;"><label  for="01A_{{ $answer['Id'] }}" class='custom-label sc_2' style="display:block; margin-top:2px;">{{ $answersBullets[$counter] }}</label></span>
                        
                    </label>
                </li>
            @endif
            {{--*/ $counter++; /*--}}
            @endforeach
            <li style="float:right; margin-right:15px;"><a href="javascript:void(false)" class="reset-answer" style="color:#15448a; text-decoration:underline;">Clear</a></li>
        </ul>
        @elseif(in_array($question['qbank']['question_type']['Display'], array('Multiple Choice- Multi Answer', 'Selection')))

        <!-- Iterate through array and flip them -->
        {{--*/ $ansIds = array(); /*--}}
        @if (isset($question[$ansTable][0]))
            @foreach($question[$ansTable] as $ans)
                {{--*/ $ansIds[] = $ans['QuestionAnswerId']; /*--}}
            @endforeach
        @endif

        <ul class="ans-sheet multiple_choice ">
            <li>{{ str_pad(++$index, 2, '0', STR_PAD_LEFT) }}</li>
            {{--*/ $counter = 0; /*--}}
            @foreach($question['qbank']['answers'] as $answer)
            @if($counter > 4)
                {{--*/ break; /*--}}
            @endif
            <li>
                <label for="01A">{{ $answersBullets[$counter] }}</label>
                <input type="checkbox" id="01A" name="multi_{{ $question['Id'] }}" value="_{{ $question['Id'] }}_{{ $answer['Id'] }}_{{ $answersBullets[$counter] }}" class="multi_chk_box mc" {{ (!empty($ansIds) && in_array($answer['Id'], $ansIds)) ? 'checked' : '' }}/>
            </li>
            {{--*/ $counter++; /*--}}
            @endforeach        
        </ul>
        @elseif($question['qbank']['question_type']['Display'] == "Open Ended Response" || $question['qbank']['question_type']['Display'] == 'Student-Produced Response: Math')
        <ul class="ans-sheet">
            <li>{{ str_pad(++$index, 2, '0', STR_PAD_LEFT) }}</li>
            <li>
                <input name="" type="text" placeholder="" id="" value="{{ isset($question[$ansTable][0]['QuestionAnswerText']) ? $question[$ansTable][0]['QuestionAnswerText'] : '' }}" data-value="_{{ $question['Id'] }}" class="create_inpt eval-math w140 pb13 oer_txtbox">
                <input type="hidden" name="original_answer_value[]" value="" class="math-eq">
            </li>
        </ul>
        @elseif(($question['qbank']['question_type']['Display'] == "Free Form") || ($question['qbank']['question_type']['Display'] == "Essay"))
        <ul class="ans-sheet">
            <li>{{ str_pad(++$index, 2, '0', STR_PAD_LEFT) }}</li>
            <li>
                <a href="{{ route('essay-popuop', array('subSecQuestionId' => $question['Id'], 'questionId' => $question['qbank']['Id'])) }}?retake={{ $retaking }}" class="load_more_btn mL0 mt0 mr0 w104 txt-c ff_essay fancybox_no_close_click fancybox.ajax" data-value="_{{ $question['Id'] }}" id="ff_essay_{{ $question['Id'] }}" data-essay="{{ isset($question[$ansTable][0]['QuestionAnswerText']) ? 'good-to-go' : '' }}">Respond</a>
            </li>
        </ul>
        @endif
    
@endforeach


<script type="text/javascript">
    $(document).ready(function () {
        $('.reset-answer').on('click', function(){
            var a = $(this).closest('ul').first().find('input[type=radio]:checked').prop('checked', false);;
            console.log(a);
        });
    });
</script>