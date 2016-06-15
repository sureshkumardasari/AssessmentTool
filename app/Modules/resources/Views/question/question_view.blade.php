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
                                <div class="col-md-2 control-label">{{ $question->inst_name }}</div>
                            </div>
                            <div>
                                <label class="col-md-2 control-label" align="right">Category: </label>
                                <div class="col-md-2 control-label">{{ $question->category_name}}</div>
                            </div>
                            <div>
                                <label class="col-md-2 control-label" align="right">Lesson: </label>
                                <div class="col-md-2 control-label">{{ $question->lesson_name}}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <label class="col-md-2 control-label" align="right">Subject Name: </label>
                                <div class="col-md-2 control-label">{{ $question->subject_name }}</div>
                            </div>
                            <div>
                                <label class="col-md-2 control-label" align="right">Question Type:  </label>
                                <div class="col-md-2 control-label">{{ $question->qst_type_text }}</div>
                            </div>
                            <div>
                                <label class="col-md-2 control-label" align="right">Question Title:  </label>
                                <div class="col-md-2 control-label">{{ $question->title }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <label class="col-md-2 control-label" align="right">Question Text: </label>
                                <div class="col-md-2 control-label">{{ $question->qst_text }}</div>
                            </div>
                            <div>
                                <label class="col-md-2 control-label" align="right">Correct Answer:  </label>
                                <div class="col-md-2 control-label">{{ $question->ans_text }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection