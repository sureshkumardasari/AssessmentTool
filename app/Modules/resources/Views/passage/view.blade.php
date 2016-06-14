@extends('default')

@section('header-assets')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Passage Details</div>
                    <div class="panel-body">
                        <div class="row">
                            <div>
                                <label class="col-md-3 control-label">Passage Title: </label>
                                @foreach($passages as $passage)
                                <div class="col-md-3 control-label">{{ $passage->title }}</div>
                                @endforeach
                            </div>
                            <div>
                                <label class="col-md-3 control-label">Passage Text: </label>
                                <div class="col-md-3 control-label">{{ $passage->passage_text }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div>
                                <label class="col-md-3 control-label">Passage Lines: </label>
                                <div class="col-md-3 control-label">{{ $passage->passage_lines }}</div>
                            </div>
                            <div>
                                <label class="col-md-3 control-label">Status:  </label>
                                <div class="col-md-3 control-label">{{ $passage->status }}</div>
                            </div>
                        </div>
                       {{-- <div class="row">
                            <div class="col-md-6 col-md-offset-4">
                                <!-- <button type="button" class="btn btn-primary">  --><a target="_blank" href="#" ><button type="button" class="btn btn-primary"> Print Test</button></a> <!-- </button> -->
                                <button type="button"  class="btn btn-primary btnAnswerKeys">Print Answer Key</button>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
