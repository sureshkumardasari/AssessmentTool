<div id="recentupdated">FusionCharts XT will load here!</div>
<script src="{{asset('/js/fusion/js/fusioncharts.js') }}"></script>
<script type="text/javascript" src="{{asset('/js/fusion/js/themes/fusioncharts.theme.ocean.js')}}"></script>
<?php 

$score=($score/$user);


?>



<script type="text/javascript">
	 FusionCharts.ready(function(){
        var salesChart = new FusionCharts({
            type: 'column2d',
            dataFormat: 'json',
            renderAt: 'recentupdated',
            width: '950',
            height: '350',
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
                               {
                               
                                'value' : '{{$score}}'
                          }
                    ]
                    }
                ]
            }
        }).render();
    });
</script>