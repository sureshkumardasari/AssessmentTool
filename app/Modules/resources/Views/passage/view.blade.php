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

                            <div class="row panel-body">
                                <label class="col-md-3 control-label"><b>Passage Title:</b></label>

                                <div class="col-md-3 control-label">{{ strip_tags(htmlspecialchars_decode($passage->title)) }}</div>

                            </div>
                            <div class="row panel-body">
                                <label class="col-md-3 control-label"><b>Passage Text:</b></label>
                                <div class="col-md-9" style="word-break: break-all">{{ strip_tags(htmlspecialchars_decode($passage->passage_text)) }}</div>
                            </div>


                             <div class="row panel-body">
                            <div>
                                <label class="col-md-3 control-label"><b>Passage Lines:</b></label>
                                <div  class="col-md-9"  style="word-break: break-all">{{ strip_tags(htmlspecialchars_decode($passage->passage_lines)) }}</div>
                            </div>
                             </div>
                                 <div class="row panel-body">
                                     <div>
                                         <label class="col-md-3 control-label"><b>Passage Status:</b></label>
                                         <div  style="word-break: break-all">{{ strip_tags(htmlspecialchars_decode($passage->status)) }}</div>
                                     </div>
                                </div>
                        @endforeach

                </div>
            </div>
        </div>
    </div>
    @endsection
