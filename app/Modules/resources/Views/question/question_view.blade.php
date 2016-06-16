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
                            @if (isset($answersLisitng) && !empty($answersLisitng))
                                {!! $answersLisitng !!}
                            @endif
                        </div>

                    </div>


                </div>
                </div>
            </div>
        </div>
    </div>
@endsection