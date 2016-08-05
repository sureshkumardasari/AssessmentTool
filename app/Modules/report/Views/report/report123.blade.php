@extends('default')
@section('content')
<div class="container">
    <div >
        <div class="col-md-4 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard
                </div>
                <div class="panel-body">
                   <th><b>List of Assignments</b></th>
                      <div>
                        <table >
                          <thead>
                            <tr>
                               <th>Name</th>
                                <th>StartDateTime</th>
                            </tr>
                          </thead>
                           <tbody>
                            @foreach( $assignments as $id => $row )
                                <tr>
                                    <td><a href="{{ url('/resources/assignmentview/'.$row->id) }}">{{  $row->name }}</a></td>
                                    <td>{{$row->startdatetime}}</td>
                                </tr>
                            @endforeach
                           </tbody>
                         </table>
                      </div>
                  <button><a href="{{ url('/resources/assignment') }}">View More</a></button>
                </div>
            </div>
        </div>
    </div>
    <div>
        @include('report::report.report1234')
    </div>
</div>

@endsection