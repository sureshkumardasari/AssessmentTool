
<div class="container">
        <div class="row">
            <div class="col-md-3">
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
                        <center><button><a href="{{ url('/resources/question') }}">View More</a></button></center>
                  
            </div>
            <div class="col-md-3 col-md-offset-1">
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
                <center><button><a href="{{ url('user/users_list/student') }}">View More</a></button></center>
                         
            </div>
            <div class="col-md-3 col-md-offset-1">
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
                    <center><button><a href="{{  url('user/users_list/teacher')  }}">View More</a></button></center>
            </div>
        </div>

</div> 
