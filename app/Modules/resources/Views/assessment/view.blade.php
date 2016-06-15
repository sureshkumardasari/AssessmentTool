@extends('default')

@section('header-assets')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Assessment Details</div>

                    <div class="panel-body">

                        <div class="row">
                            <div>
                                <label class="col-md-3 control-label">Assessment Title: </label>
                                <div class="col-md-3 control-label">{{ $title[0]->name}}</div>
                            </div>

                        </div>

                            <div class="row">
                                <label class="col-md-3 control-label">Assessment Questions:</label>
                                <table>
                                @foreach($assessments as $assessment)
                                    <tr>
                                    <td>{{ $assessment->qstn_title }}</td>
                                    </tr>
                                @endforeach
                                </table>
                            </div>

                        <div class="row">
                            <div>
                                <label class="col-md-3 control-label">Assessment Passages: </label>
                                <table>
                                    @foreach($assessments as $assessment)
                                        <tr>
                                            <td>{{ $assessment->psg_title }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                     </div>

                </div>

            </div>
        </div>
    </div>
@endsection
