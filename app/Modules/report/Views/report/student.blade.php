@extends('default')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Scores By Students</div>
                    <div class="panel-body">


                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Select institution:</label>
                                <div class="col-md-2">
                                    <select name="inst_id" class='form-control' id="institution_id" onchange="inst_change()">
                                        <option value="0" selected >-Select-</option>
                                        @foreach($inst_arr as $id=>$val)
                                            <option value="{{ $id }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Select Student:</label>
                                <div class="col-md-2">
                                    <select name="student_id" class='form-control' id="student">
                                        <option value="0" selected >-Select-</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="Report">
                        <table class="table" id="assessment">
                            <thead>
                            <tr>
                                <th>Assignment</th>
                                <th>Assessment</th>
                                <th>Date</th>
                                <th>Total Questions</th>
                                <th>Correct Questions</th>
                                <th>Percentage(%)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>


                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
