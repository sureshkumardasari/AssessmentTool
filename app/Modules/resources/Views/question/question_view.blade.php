@extends('default')

@section('header-assets')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Question Details</div>
                    <div class="panel-body">
                        <div class="row">
                            <div>
                                <label class="col-md-2 control-label" align="right">Institution Name: </label>
                                <div class="col-md-2 control-label" style="word-break: break-all">{{ $question->inst_name }}</div>
                            </div>
                            <div>
                                <label class="col-md-2 control-label" align="right">Category: </label>
                                <div class="col-md-2 control-label" style="word-break: break-all">{{ $question->category_name}}</div>
                            </div>
                            <div>
                                <label class="col-md-2 control-label" align="right">Lesson: </label>
                                <div class="col-md-2 control-label" style="word-break: break-all">{{ $question->lesson_name}}</div>
                            </div>
                        </div>


                            <div class="row">
                                <label class="col-md-2 control-label" align="right">Subject Name: </label>
                                <div class="col-md-2 control-label" style="word-break: break-all">{{ $question->subject_name }}</div>
                                <label class="col-md-2 control-label" align="right">Question Type:  </label>
                                <div class="col-md-2 control-label" style="word-break: break-all">{{ $question->qst_type_text }}</div>
                            </div>



                            <div class="row">
                                <label class="col-md-2 control-label" align="right">Passage Title:  </label>
                                <div class="col-md-2 control-label" style="word-break: break-all">{{ $question->psg_title }}</div>
                            </div>

                        <div class="answers mt20 col-md-12">
                            <div class="row">
                             <?php $qstn_arr=['1','2','3','4','5','6','7','8','9','10']; 
                                    $a=0;?>
                            <label class="col-md-2" style="word-break: break-all" ><b>{{$qstn_arr[$a++]}}.Question:</b></label>
                                 @foreach($qstn as $question)
                                    {{ $question['title']}}
                                 @endforeach
                            </div>
                            <div class="row">
                                    <?php $ans_arr=['A','B','C','D','E']; 
                                    $i=0;?>
                                <label class="col-md-2 control-label" style="word-break: break-all"><b>Answers: </b></label>
                                 <div class="col-md-6">
                                @foreach($oldAnswers as $key => $answer)
                               <div>
                                    <b>{{$ans_arr[$i++]}}</b>.
                                  @if($answer["is_correct"] == "YES")
                                  <span style="color:green;font-weight:bold "><?php echo($answer['ans_text'])?></span>
                                  @else
                                 <?php echo($answer['ans_text'])?>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>


                </div>
                </div>
            </div>
        </div>
    </div>
@endsection