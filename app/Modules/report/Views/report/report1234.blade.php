 <div class="container">
        <div class="row">
            <div>
                <div class="col-md-4 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Dashboard
                        </div>
                        <div class="panel-body">
                            <th><b>List of Assessments</b></th>
                             <div>
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
                            <button><a href="{{ url('/resources/assessment') }}">View More</a></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
