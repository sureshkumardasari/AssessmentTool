<html>
<head>
    <title>FusionCharts XT - Column 2D Chart - Data from a database</title>
    <link  rel="stylesheet" type="text/css" href="css/style.css" />

    <!-- You need to include the following JS file to render the chart.
    When you make your own charts, make sure that the path to this JS file is correct.
    Else, you will get JavaScript errors. -->

    <!-- <script src="js/fusioncharts.js"></script>-->
    <script src="{{ asset('/js/fusion/js/fusioncharts.js') }}"></script>
    <script src="{{ asset('/js/fusion/js/themes/fusioncharts.theme.fint.js') }}"></script>
</head>

<body>
<?php
dd($result);
/*while($data = pg_fetch_assoc($result))
{
    echo ($data['mnth'].$data['value'].'</br>');
}*/
$arrdata= array(

        "chart"=>array(

                "caption"=> "Monthly revenue for last year",
                "subCaption"=> "Harry's SuperMart",
                "xAxisName"=> "Month",
                "yAxisName"=> "Revenues",
                "theme"=> "fint",
                "exportEnabled"=> "1",
                "exportFormats"=> "PNG=Export as High Quality Image|JPG|PDF=Export as PDF File",
                "exportTargetWindow"=> "_self",
                "exportFileName"=> "Monthly revenue for last year" )


);

$arrdata["data"]=array();


$jasonencodeddata= json_encode($arrdata);

while($row = pg_fetch_assoc($result)) {
    array_push($arrdata["data"], array(
                    "label" => $row["mnth"],
                    "value" => $row["value"]
            )
    );
}

/*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

$jsonEncodedData = json_encode($arrdata);

/*Create an object for the column chart using the FusionCharts PHP class constructor. Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, "div id to render the chart", "data format", "data source")`. Because we are using JSON data to render the chart, the data format will be `json`. The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the data source parameter of the constructor.*/

$columnChart = new FusionCharts("column2d", "myFirstChart" , "100%", "100%", "chart-1", "json", $jsonEncodedData);

// Render the chart
$columnChart->render();

// Close the database connection


?>
<div id="chart-1"> </div>
</body>
</html>
