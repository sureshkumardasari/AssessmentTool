
<div class="container">
        <div class="row" style="margin:-33px -15px -33px -33px;">
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Questions
                </div> 
                <div class="panel-body">

                    <table>
                        <thead>
                            <tr>
                               <th>Question Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $list_details as $id => $value )
                            <tr>
                                <td><a href="#">{{ $value['question_title'] }}</a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                        <center><a class="btn btn-info" role="button" href="{{ url('/resources/question') }}">View More</a></center>
                  
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Students
                </div>
                <div class="panel-body">

                    <table >
                        <thead>
                            <tr>
                               <th>Students Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $slist as $id => $value )
                            <tr>
                                <td><a href="#">{{ $value['name'] }}</a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table> 
                    </div>
                </div>
                <center><a class="btn btn-info" role="button" href="{{ url('user/users_list/student') }}">View More</a></center>
                         
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Teacher
                </div>
                <div class="panel-body">

                    <table>
                        <thead>
                            <tr>
                               <th>Teacher Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $tlist as $id => $value )
                            <tr>
                                <td><a href="#">{{ $value['uname'] }}</a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                    <center><a class="btn btn-info" role="button" href="{{  url('user/users_list/teacher')  }}">View More</a></center>
            </div>
            <div class="row" style="margin-bottom:20px;"> </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List of Assignments
                </div>
                <div class="panel-body">

                    <table>
                        <thead>
                             <tr>
                               <th>Name</th>
                                <th>StartDateTime</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach( $assignments_user as $id => $row )
                                <tr>
                                    <td><a href="{{ url('/resources/assignmentview/'.$row->id) }}">{{  $row->name }}</a></td>
                                    <td>{{$row->startdatetime}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
              <center><a class="btn btn-info" role="button" href="{{ url('/resources/assignment') }}">View More</a></center>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List of Assessments
                </div>
                <div class="panel-body">

                    <table>
                        <thead>
                            <tr>
                               <th>Name</th>
                            </tr>
                        </thead>
                       <tbody id="question_list_filer">
                        @foreach( $assessment as $name )
                            <tr>
                                <td><a href="{{ url('/resources/assessmentview/'.$name['id']) }}">{{ $name['name'] }}</a></td>
                            </tr>
                        @endforeach
                     </tbody>
                    </table>
                </div>
                </div>
                    <center><a class="btn btn-info" role="button" href="{{ url('/resources/assessment') }}">View More</a></center>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Lessons
                </div>
                <div class="panel-body">

                    <table>
                        <thead>
                            <tr>
                               <th>Lesson Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $list_lession as $id => $value )
                            <tr>
                                <td><a href="#">{{ $value }}</a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                    <center><a class="btn btn-info" role="button" href="{{  url('/resources/lesson')  }}">View All</a></center>
            </div>
        </div>

</div> 
