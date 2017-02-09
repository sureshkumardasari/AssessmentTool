

<div class="container">
        <div class="row" style="margin:-33px -15px -33px -33px;">
            <div class="col-md-4">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Questions
                </div> 
                <div class="panel-body">

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Question Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $list_details as $id => $value )
                            <tr>
                                <td><a href="{{ url('/resources/questionview/'.$value['qid']) }}">{{ $value['question_title'] }}</a></td>
                                 
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

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Students Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $slist as $id => $value )
                            <tr>
                                <td><a href="{{ url('/user/edit/'.$value['id']) }}">{{ $value['name'] }}</a></td>
                                 
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
                <div class="panel-heading">List Of Teachers
                </div>
                <div class="panel-body">

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Teacher Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $tlist as $id => $value )
                            <tr>
                                <td><a href="{{ url('/user/edit/'.$value['uid']) }}">{{ $value['uname'] }}</a></td>
                                 
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

                    <table id="text">
                        <thead>
                             <tr>
                               <th >Name </th>
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

                    <table id="text">
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

                    <table id="text">
                        <thead>
                            <tr>
                               <th>Lesson Title</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $list_lession as $id => $value )
                            <tr>
                                <td><a href="{{ url('/resources/lessonedit/'.$id) }}">{{ $value }}</a></td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                    <center><a class="btn btn-info" role="button" href="{{  url('/resources/lesson')  }}">View More</a></center>
            </div>
        </div>

</div>
<style>
    #text,th
    {
        width: 300px;
        border: 0px solid #000000;
        word-break: break-word;
    }
</style>
