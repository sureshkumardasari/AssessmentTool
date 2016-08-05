
<div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Questions
                </div> 
                    <table>
                        <thead>
                            <tr>
                               <th>Question Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $list_details as $id => $value )
                            <tr>
                                <td>{{ $value['question_title'] }}</td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                  <button><a href="{{ url('/resources/question') }}">view Details</a></button>
            </div>
            <div class="col-md-2">
                <div class="panel panel-default">
                <div class="panel-heading">List Of Students
                </div>

                    <table >
                        <thead>
                            <tr>
                               <th>Students Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $slist as $id => $value )
                            <tr>
                                <td>{{ $value['name'] }}</td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button><a href="{{ url('user/users_list/student') }}">view More</a></button>            
            </div>
            <div class="col-md-2">
                <div class="panel panel-default" style="height:160px">
                <div class="panel-heading">List Of Teacher
                </div>
                    <table>
                        <thead>
                            <tr>
                               <th>Teacher Title</th>
                            </tr>
                        </thead>
                        <tbody id="question_list_filer">
                            @foreach( $tlist as $id => $value )
                            <tr>
                                <td>{{ $value['uname'] }}</td>
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button><a href="{{  url('user/users_list/teacher')  }}">view Details</a></button>
            
            </div>
        </div>

</div> 
