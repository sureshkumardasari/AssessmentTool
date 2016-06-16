 <div class="row">
        <label class="col-md-2" style="word-break: break-all" >Question: </label>
        @foreach($qstn as $question)
        {{ $question['title']}}
            @endforeach
    </div>
 <div class="row">
<label class="col-md-2" style="word-break: break-all">Answers: </label>
@foreach($oldAnswers as $key => $answer)

            <ol type="A"><div class="row">
                @if($answer["is_correct"] == "YES")
                    <div class=col-md-2'>
                <li><b>{!! strip_tags(htmlspecialchars_decode($answer['ans_text'])) !!}</b></li>
            </div>

                    @else
                <div class='col-md-2'>
                    <li>{!! strip_tags(htmlspecialchars_decode($answer['ans_text'])) !!}</li>
                </div>

                @endif
            </div></ol>
 </div>

    <div class=''>
            <div class='col-md-2'>

                <div class='clr'></div>
            </div>
        </div>

@endforeach