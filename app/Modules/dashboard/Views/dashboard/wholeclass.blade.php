    
@if(Auth::user()->role_id==1)
<div class="container">
    <div class="row">
    <select style="align:left;" id="change">
              <option value="10" id="var">10</option>
              <option value="20" id="var">20</option>
              <option value="30" id="var">30</option>
              <option value="40" id="var">40</option>
            </select>
        <div class="col-lg-4 col-sm-6 ">
            <h5><b>Whole Class Score Report</b></h5>
            <div id="third">
            FusionCharts XT will load here!
            </div>
        </div>
    </div>
 </div>
 <script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<script>

    FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'third',
            width: '720',
            height: '500',
            dataSource: {
                "chart": {
                    "caption": "Most Recent Whole class score report",
                    "subCaption": "",
                    "xAxisName": "Subject Name",
                    "yAxisName": "Average Marks",
                    "numberPrefix": "",
                    "theme": "fint"
                },
               
                
                "dataset": [
                    {
                        "data": [
                        @foreach($students as $user_id => $assignment)
                        {
                        'label' : '{{$assignment->sname}}',
                        'value' : '{{$assignment->score}}'

                         },
                    @endforeach
                          //      {
                               
                          //       'value' : '{{$score}}'
                          // }
                    ]
                    }
                ]
            }
        }).render();
    });
    </script>
    @elseif(Auth::user()->role_id==3 ||Auth::user()->role_id==4)

<div class="col-md-4">
            <h5><b>Whole Class Score Report:</b></h5>
            <div id="third">    
            FusionCharts XT will load here!
            </div>
        </div>
 <script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<script>

     FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'third',
            width: '720',
            height: '500',
            dataSource: {
                "chart": {
                    "caption": "Most Recent Whole class score report",
                    "subCaption": "",
                    "xAxisName": "Subject Name",
                    "yAxisName": "Average Marks",
                    "numberPrefix": "",
                    "theme": "fint"
                },
               
                "dataset": [
                    {
                        "data": [
                        @foreach($assignments as $user_id => $assignment)
                        {
                        'label' : '{{$assignment->sname}}',
                        'value' : '{{$score}}'

                         },
                    @endforeach
                          //      {
                               
                          //       'value' : '{{$score}}'
                          // }
                    ]
                    }
                ]
            }
        }).render();
    });
    </script>
    @endif
     <?php
$path = url()."/dashboard/";
?>
    <script type="text/javascript">
         $(document).ready(function($) {

    $("#change").change(function() {
        
        var search_id = $(this).val();
        
        var wc='wc';
        
        $.ajax({
           
            url:'{{$path}}ClassLists/'+wc+'/'+search_id ,
            type:'get',
            success: function (response) {
               $( "#fourth" ).remove();
              $("#third").append(response);  
                
            }
        });
    });
});