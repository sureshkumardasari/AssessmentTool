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
                        <div class="col-md-2" ></div>
                       <div class="rcorners1 col-md-6" >
                           <a href="#">Scores by Assessments</a>
                       </div>

                        <div class="col-md-3" ></div>
                        <div class="rcorners1 col-md-6" >
                            <a href="#">Student Scores by Assignment</a>
                        </div><br>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-2" ></div>
                        <div class="rcorners1  col-md-6" >
                            <a href="#">Scores by Students</a>
                        </div>
                        <div class="col-md-3" ></div>
                        <div class="rcorners1  col-md-6" >
                            <a href="#">Questions & Answers</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
