@extends('default')
@section('content')
<?php 
$class="col-md-12";
    if($length == 1){
        $class="col-md-12";    
    }
    elseif($length ==2){
        $class="col-md-6";
    }
    elseif($length ==3){
        $class="col-md-4";
    }
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center; ">Least Scores Report</div>
                    <div class="panel-body">
                    @if($length !=0)
                        <div class="{{$class}}">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="text-align:center; ">{{$assignmentname[0]}}</div>
                                    <div class="panel-body">
                                        <table class="table">
                                            <thead>
                                                <th>Student Name</th>
                                                <th>Score</th>
                                            </thead>
                                            <tbody>
                                            @if(count($report_data)>0)
                                                @foreach($report_data as $assignment)
                                                    <tr>
                                                        <td>{{$assignment->user_name}}</td>
                                                        <td>{{$assignment->rawscore}}</td>
                                                    </tr>
                                                 @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" align="center">No data Available</td>
                                                </tr>
                                            @endif

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                         <div class="col-md-6"></div>
                          <div class="col-md-6">
                        <a href="#" class="btn btn-primary btnexp" id="pdf">Export Pdf</a>
                        <a href="#" class="btn btn-primary btnexx" id="xls">Export xls</a>
                        </div>
                    </div>
                        </div>
                        @if($length >1)
                        <div class="{{$class}}">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="text-align:center; ">{{$assignmentname[1]}}</div>
                                    <div class="panel-body">
                            <table class="table">
                                <thead>
                                <th>Student Name</th>
                                <th>Score</th>
                                </thead>
                                <tbody>
                                @if(count($report_data1)>0)
                                    @foreach($report_data1 as $assignment)
                                        <tr>
                                            <td>{{$assignment->user_name}}</td>
                                            <td>{{$assignment->rawscore}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" align="center">No data Available</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($length >2)
                        <div class="{{$class}}">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading" style="text-align:center; ">{{$assignmentname[2]}}</div>
                                    <div class="panel-body">
                            <table class="table">
                                <thead>
                                <th>Student Name</th>
                                <th>Score</th>
                                </thead>
                                <tbody>
                                @if(count($report_data2)>0)
                                    @foreach($report_data2 as $assignment)
                                        <tr>
                                            <td>{{$assignment->user_name}}</td>
                                            <td>{{$assignment->rawscore}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" align="center">No data Available</td>
                                    </tr>
                                @endif

                                </tbody>
                            </table>
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                         <div class="col-md-6"></div>
                          <div class="col-md-6">
                        <a href="#" class="btn btn-primary" id="pdf" style="margin:7px;">Export Pdf</a>
                        
                        <a href="#" class="btn btn-primary" id="xls">Export xls</a>
                        </div>
                    </div> -->
                    @endif

                    
                    @else
                    <div class="{{$class}}"><p class="pull-middle" align="center" style="color:red"><b>!..OOPS..! No Assignments To Show.</b></p></div>
                    @endif

                </div>
            </div>
        </div>
    </div>
<script>
    $('#pdf').on('click',function(){
        window.open("{{ url('report/leastscoreexportPDF/')}}");

    });
    $('#xls').on('click',function(){
        window.open("{{ url('report/leastscoreexportXLS/')}}");

    });
</script>

@endsection