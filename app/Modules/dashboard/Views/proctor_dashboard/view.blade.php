@extends('default')

@section('content')
    <?php
    $now=Date("Y-m-d H:i:s");
    // dd($now);
    ?>
    {{--@if(count($proctor_assignments)>0)--}}
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">&nbsp;
                        Proctor Dashboard
                    </div>
                    <div class="panel-body">
                        <div>
                            <table class="table table-striped table-bordered datatableclass">
                                <thead>
                                <td>Assignments</td>
                                <td>Status</td>
                                <td>Actions</td>
                                </thead>
                                <tbody>
                                @if(count($proctor_assignments)>0)
                                    @foreach($proctor_assignments as $assignment)
                                        <tr>
                                            <td>{{$assignment->name}}</td>
                                            <td>{{$assignment->status}}</td>
                                            <td>
                                                @if($assignment->neverexpires==1 || $assignment->enddatetime >$now)
                                                    @if($assignment->status=="upcoming")
                                                        @if($assignment->neverexpires==1 || $assignment->enddatetime >$now)
                                                            <a href="{{url('launch_test_by_proctor/set_instructions/'.$assignment->id)}}">Set Instructions</a>
                                                        @else
                                                            TimeOut
                                                        @endif
                                                    @elseif($assignment->status=="instructions")
                                                        <a href="{{url('launch_test_by_proctor/start_test/'.$assignment->id)}}">Start</a>
                                                    @elseif($assignment->status=="inprogress")
                                                        <a href="{{url('launch_test_by_proctor/start_test/'.$assignment->id)}}">Resume</a>
                                                    @elseif($assignment->status=="completed")
                                                        Completed
                                                    @endif
                                                @else
                                                    TimeOut
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{--@endif--}}

@endsection
{{--No newline at end of file--}}
