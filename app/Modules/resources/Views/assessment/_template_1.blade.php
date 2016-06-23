<!-- Template 1 Format --> 
<div class="content template-bg col-1" id="content">
    
    @if (!empty($titlePage) || !empty($beginInstructions))
        <div class="page-header" id='title_bi'>
            @if (!empty($titlePage))
                <div class='mb10'>
                    {!! $titlePage !!}
                </div> 
            @endif
            @if (!empty($beginInstructions))
                <div class='mb10'>
                    {!!($beginInstructions) !!}
                </div> 
            @endif
        </div>
    @endif
    {{--*/ 
        $count = 0; 
        $lastPassageId = "";
        $questionCount = 0;
        
    /*--}}
    @foreach($questions as $passage_id => $questionsdata)
    <div class='question mb10 other_question ' data-passage-type="" data-passage-id="{{ !empty($passage_id) ? $passage_id : '0' }}" data-passage-type="{{ !empty($passage_id) ? 'PassageLines' : 'none' }}">
        <div>
        
            @if(isset($passage_id)) 
            <div class='questions_passage passage_content_{{ $passage_id }}' data-passage-id="{{ $passage_id }}" data-passage-type="{{ !empty($passage_id) ? 'PassageLines' : 'none' }}">
                {!! $questionsdata['psg_title'] !!} <br/>
                {!! strip_tags(htmlspecialchars_decode($questionsdata['psg_txt'])) !!}
            </div>    
            
            @endif

            <div class="other_content question_content" style="margin-top: 15px;" >
                @foreach($questionsdata['questions'] as $questions)
                <!-- NEVER DELETE THIS DIV other wise test experience will BREAK  >:( -->
                <div class='question_order' style="display:none;">{{ ++$questionCount }}</div>

                <div class="other_content_data" data-id="{{ $questions['title'] }}">

                    <div class="qst-item-text" contenteditable='false' style="float: left; width: 36px; position: relative;">
                        {{ $questionCount }}.
                    </div>

                    @if (isset($questions['qst_text']) && !empty($questions['qst_text']))
                    <div style="padding: 0 0 15px 40px;" class="qst-item-text">
                        <!-- {!! $questions['title'] !!} -->
                        {!! strip_tags(htmlspecialchars_decode($questions['qst_text'])) !!}
                    </div>
                    @endif

                    @foreach($questions['answers'] as $key => $answer)
                        <div class="bullet-answer-elem" style='padding: 0 0 8px 93px;'>
                                    {!! $answer['ans_text'] !!}
                        </div>
                        <div class="clr"></div>                        
                    @endforeach

                    
                </div>
                <div class="clr"></div>                        

                @endforeach
            </div>
        </div>
    </div>
    {{--*/ 
        $count = $count + 1;            
    /*--}}
    
    @endforeach 

    @if (!empty($endInstructions))
    <div class='mb10 page-footer' id="endInstructions">
        {!! ($endInstructions) !!}
    </div> 
    @endif
</div>