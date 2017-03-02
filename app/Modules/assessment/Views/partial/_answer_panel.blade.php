<style type="text/css">
    
/********Take Test page related*********/
    #question-answers{background-color: #eeeeee;}
    .ans-sheet > li {   
        display: inline;
        padding: 5px;
    }
    .ans-sheet label{font-weight: bold;}
    .ans-sheet { /*background-color: #dddddd;*/
        line-height: 25px;}
    .answerradio {
        background-color: #ffffff;
        border: 1px solid #ccc;
        border-radius: 10px;
        display: inline;
        margin-right: 10px;
        padding: 5px;
        color : #000000;
        
    }
    .answerradio.active {
        background-color: #008fb3;
        color: #ffffff;

    }
    .test-timer{
      font-weight: bold; padding: 5px; 
      background-color: wheat;
      color: #008fb3;
    }
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
        color: #008fb3;
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
    $index = 0;
/*--}}


@foreach($questions as $question)    
    {{--*/
        $answersBullets = $_answersBullets[ $index % 2 ];
    /*--}}

    @if(in_array($question['question_type'], array('Multiple Choice - Single Answer')))
        <ul class="ans-sheet multiple_choice checkbox-bg" style="padding:0;">
            <li>{{ str_pad(++$index, 2, '0', STR_PAD_LEFT) }}</li>
            
            {{--*/ $counter = 0; /*--}}
            @foreach($question['answers'] as $answer)
            @if ($counter < 5)
                <li class='sc_1 answerradio' data-v='{{ $question['Id'] }}'>
                    <label class="radio-inline" style="width: 25px;">
                        
                        <input type="radio" id="01A_{{ $answer['Id'] }}" name="single_sel_{{ $question['Id'] }}" value="_{{ $question['Id'] }}_{{ $answer['Id'] }}_{{ $answersBullets[$counter] }}" class="customradio single_sel_radio mc" {{ (isset($question[$ansTable][0]['QuestionAnswerId']) && ($question[$ansTable][0]['QuestionAnswerId'] == $answer['Id'])) ? 'checked' : '' }} style="margin-top: 7px;"/>

                        <span class="custom-radio">
                            <label  for="01A_{{ $answer['Id'] }}" class='custom-label sc_2' style="margin-left: -5px;">
                                {{ $answersBullets[$counter] }}</label>
                        </span>
                        
                    </label>
                </li>
            @endif
            {{--*/ $counter++; /*--}}
            @endforeach
            <li style="float:right; margin-right:15px;"><a href="javascript:void(false)" class="reset-answer" style="color:#15448a; text-decoration:underline;">Clear</a></li>
        </ul>    
    
    @elseif(in_array($question['question_type'], array('Multiple Choice - Multi Answer', 'Selection')))

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
            @foreach($question['answers'] as $answer)
            @if($counter > 4)
                {{--*/ break; /*--}}
            @endif
            <li class='answerradio'>
                <label>{{ $answersBullets[$counter] }}</label>
                <input type="checkbox" id="01A_{{ $question['Id'] }}" name="multi_{{ $question['Id'] }}" value="_{{ $question['Id'] }}_{{ $answer['Id'] }}_{{ $answersBullets[$counter] }}" class="multi_chk_box mc" {{ (!empty($ansIds) && in_array($answer['Id'], $ansIds)) ? 'checked' : '' }}/>
            </li>
            {{--*/ $counter++; /*--}}
            @endforeach        
        </ul>
        
    @elseif(($question['question_type'] == "Free Form") || ($question['question_type'] == "Essay") )
        <ul class="ans-sheet">
            <li>{{ str_pad(++$index, 2, '0', STR_PAD_LEFT) }}</li>
            <li>
                <a href="{{ route('essay-popuop', array('subSecQuestionId' => $question['Id'], 'questionId' => $question['Id'])) }}?retake={{ $retaking }}" class="load_more_btn mL0 mt0 mr0 w104 txt-c ff_essay fancybox fancybox.ajax" data-value="_{{ $question['Id'] }}" id="ff_essay_{{ $question['Id'] }}" data-essay="{{ isset($question[$ansTable][0]['QuestionAnswerText']) ? 'good-to-go' : '' }}">Respond</a>
            </li>
        </ul>
        @elseif(($question['question_type'] == "Fill in the blank"))
         <ul class="ans-sheet">
            <li>{{ str_pad(++$index, 2, '0', STR_PAD_LEFT) }}</li>
            <li>
                <a href="{{ route('fib-popuop', array('subSecQuestionId' => $question['Id'], 'questionId' => $question['Id'])) }}?retake={{ $retaking }}" class="load_more_btn mL0 mt0 mr0 w104 txt-c ff_essay fancybox fancybox.ajax" data-value="_{{ $question['Id'] }}" id="ff_essay_{{ $question['Id'] }}" data-essay="{{ isset($question[$ansTable][0]['QuestionAnswerText']) ? 'good-to-go' : '' }}">Respond</a>
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