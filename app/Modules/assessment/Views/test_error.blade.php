@extends('default')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-body">
          <!--  -->
          <div class="content">
            <div class="row">
              <a href="{{ url('/assessment/myassignment') }}" class="btn btn-primary btn-mg right"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back to My Assignments</a>    
            </div>
            <div class="row">
              <h1 style="text-align:center">{{ $message }}</h1>
            </div>
          </div>
          <!--  -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection