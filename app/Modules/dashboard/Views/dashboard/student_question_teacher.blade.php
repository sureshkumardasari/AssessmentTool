
<div class="container">
        <div class="row">
            <div class="col-md-2">
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
                   <center><button><a href="{{ url('/resources/question') }}">view Details</a></button></center>
                </div>
                </div>
                  
            </div>
            <div class="col-md-2">
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
                    <center><button><a href="{{ url('user/users_list/student') }}">view More</a></button></center>
                    </div>
                </div>
                         
            </div>
            <div class="col-md-2">
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
                    <center><button><a href="{{  url('user/users_list/teacher')  }}">view Details</a></button></center>
            
                </div>
                </div>
               
            </div>
        </div>

</div> 
