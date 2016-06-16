@extends('default')

@section('header-assets')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Passage Details</div>
                    <div class="panel-body">
                        @foreach($passages as $passage)

                            <div class="row">
                                <label class="col-md-3 control-label">Passage Title:</label>

                                <div class="col-md-3 control-label">{{ strip_tags(htmlspecialchars_decode($passage->title)) }}</div>

                            </div>
                            <div class="row">
                                <label class="col-md-3 control-label">Passage Text:</label>
                                <div style="word-break: break-all">{{ strip_tags(htmlspecialchars_decode($passage->passage_text)) }}</div>
                            </div>


                             <div class="row">
                            <div>
                                <label class="col-md-3 control-label">Passage Lines:</label>
                                <div  style="word-break: break-all">{{ strip_tags(htmlspecialchars_decode($passage->passage_lines)) }}</div>
                            </div>
                                 <div class="row">
                                     <div>
                                         <label class="col-md-3 control-label">Passage Status:</label>
                                         <div  style="word-break: break-all">{{ strip_tags(htmlspecialchars_decode($passage->status)) }}</div>
                                     </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
