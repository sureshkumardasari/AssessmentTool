@extends('default')
@section('header-assets')
@section('content')
    <style>
        p{ display:inline;}
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Assessment Details</div>
                    <div class="panel panel-body">
                            <div class="row">
                                <label class="col-md-3 control-label"><b>Assessment Title:</b> </label>
                                <div class="col-md-6 control-label">{{ $title[0]->name}}</div>
                            </div>
                        </div>
                    <div class="panel-body">
                        <?php
                            $i=0;
                            $a=0;
                            $b=1;


                        $ans_arr = ['A', 'B', 'C', 'D', 'E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                        /*$num_arr=['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30'];*/
                        //dd($assessments);
                        ?>
                        <div class="row">
                            <label class="col-md-3 control-label"><b> Questions List:</b> </label>
                        </div>

                        @foreach($assessments as $passage_id => $questionsdata)
                        
                            @if($passage_id > 0)
                                    <div class="row">
                                        <label class="col-md-3 control-label"><b>Passage Title:</b> </label>
                                        <div class="col-md-6">{{$questionsdata['psg_title']}}</div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3 control-label"><b>Passage Text:</b> </label>
                                        <div class="col-md-6">{!! strip_tags(htmlspecialchars_decode($questionsdata['psg_txt'])) !!}</div>
                                    </div>
                                @endif
                                <?php 
                                $num_arr = [];
                                for($n=0; $n<count($questionsdata['questions'] ); $n++){
                                    $num_arr[$n+1] = $n+1;
                                }
                                //dd(count($questionsdata['questions']) );
                               // print_r($num_arr);
                                ?>
                        @foreach($questionsdata['questions'] as $questions)
                            <?php $i=0;?>
                        <div class="row">
                            <label class="col-md-3 control-label"><b><b>
                            {{$b++}}<b>.Question Title:</b> </label>
                            <div class="col-md-6">{{$questions['title']}}</div>
                        </div>
                        <div class="row">
                            <label class="col-md-3 control-label"><b>Question Text:</b> </label>
                            <div class="col-md-6">{!! strip_tags(htmlspecialchars_decode($questions['qst_text'])) !!}</div>
                        </div>
                        <div class="row">
                            <label class="col-md-3 control-label"><b> Answers:</b> </label>
                            <div class = "col-md-6">
                            @foreach ($questions['answers'] as $key => $answer)
                                <div>
                                <b>{{$ans_arr[$i++]}}</b>.
                                @if($answer["is_correct"] == "YES")
                                <span style="color:green;font-weight:bold"><?php echo($answer['ans_text'])?></span>
                                @else
                                <?php echo($answer['ans_text'])?>
                                @endif
                                </div>
                            @endforeach
                            </div>
                        </div>
                        @endforeach
                        @endforeach
                    </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
@endsection


