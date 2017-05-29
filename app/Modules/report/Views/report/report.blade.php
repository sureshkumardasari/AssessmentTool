@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Reports
                        {{--<a href="{{ url('/user/roleadd/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>--}}
                    </div>

                    <div class="panel-body">
                        <div class="col-md-2 col-sm-1" ></div>
                        <div class="rcorners1 col-md-6 col-sm-6" >
                            <a href="{{ url('/report/class_average_and_student_scores_report') }}">Class Averages And Student Scores Report</a>
                        </div>

                        <div class="col-md-3 col-sm-8" ></div>
                        <div class="rcorners1 col-md-6 col-sm-4" >
                            <a href="{{ url('/report/wholeclass') }}">Whole Class Score Report</a>
                        </div><br>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-2" ></div>
                        <div class="rcorners1  col-md-6 " >
                            <a href="{{ url('/report/Subject_wise') }}">Subject wise Report</a>
                       </div>
                        <div class="col-md-3 col-sm-1" ></div>
                        <div class="rcorners1  col-md-6 col-sm-6" >
                            <a href="{{ url('/report/answer') }}">Questions & Answers</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-2 col-sm-1" ></div>
                        <div class="rcorners1  col-md-6 col-sm-6" >
                            <a href="{{ url('/report/test-history') }}">Test History Class Averages</a>
                        </div>
                        <div class="col-md-3 col-sm-8" ></div>
                        <div class="rcorners1  col-md-6 col-sm-4" >
                            <a href="{{ url('/report/student-answer-report') }}">Student Answer Report</a>
                        </div>
                    </div>
                    <div class="panel-body">
                    <div class="col-md-5 col-sm-1" ></div>
                    <div class="rcorners1 col-md-6 col-sm-6" >
                        <a href="{{ url('/report/leastscore') }}">Least Scores Report</a>
                    </div><br>
                </div>
                </div>

                   <!--  <div class="panel-body">
                        <div class="col-md-2" ></div>
                        <div class="rcorners1  col-md-6" >
                            <a href="{{ url('/report/wholeclasstile') }}">whole score tile</a>
                        </div> 
                    </div> -->
                   <!--  <div class="col-md-3" ></div>
                    <div class="rcorners1  col-md-6" >
                        <a href="{{ url('/report/report123') }}"> Dashboard</a>
                    </div>
                        <div class="panel-body">
                        <div class="col-md-2" ></div>
                        <div class="rcorners1  col-md-6" >
                            <a href="{{ url('/report/studentquestionteacher') }}">student_teacher_quection</a>
                        </div>
                        <div class="col-md-3" ></div>
                        
                    </div> -->
                    

                </div>
                    {{--<div class="panel-body">--}}
                        {{--<div class="col-md-2" ></div>--}}
                        {{--<div class="rcorners1  col-md-6" >--}}
                            {{--<a href="{{ url('/report/student-answer-report') }}">Student Answer Report</a>--}}
                        {{--</div> --}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </div>

@endsection
