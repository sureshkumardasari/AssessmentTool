                 <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Assessments
                        </div>
                        <div class="panel-body">
                            <th><!-- <b>List of Assessments</b> --></th>
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
                             <center><a class="btn btn-info" role="button" href="{{ url('/resources/assessment') }}">View More</a></center>
                        </div>
                    </div>
                </div>
 