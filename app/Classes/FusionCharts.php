<?php
namespace App\Classes;

class FusionCharts {

    /*
     * Parameter	Type	Description
     * chartType	String	The type of chart that you intend to plot. e.g. Column3D, Column2D, Pie2D etc.
     * chartId	    String	Id for the chart, using which it will be recognized in the HTML page. Each chart on the page needs to have a unique Id.
     * chartWidth	String	Intended width for the chart (in pixels). e.g. 400
     * chartHeight	String	Intended height for the chart (in pixels). e.g. 300
     * dataFormat	String	Type of the data that is given to the chart. e.g. json, jsonurl, xml, xmlurl
     * dataSource	String	Actual data for the chart. e.g. {"chart":{},"data":[{"label":"Jan","value":"420000"}]}
     */
    public $constructorOptions = array();
    public $constructorTemplate = '
        <script type="text/javascript">
            FusionCharts.ready(function () {
                new FusionCharts(__constructorOptions__);
            });
        </script>';
    public $renderTemplate = '
        <script type="text/javascript">
            FusionCharts.ready(function () {
                FusionCharts("__chartId__").render();
            });
        </script>
        ';
    // constructor
    function __construct($type, $id, $width = 400, $height = 300, $renderAt, $dataFormat, $dataSource) {
        isset($type) ? $this->constructorOptions['type'] = $type : '';
        isset($id) ? $this->constructorOptions['id'] = $id : 'php-fc-'.time();
        isset($width) ? $this->constructorOptions['width'] = $width : '';
        isset($height) ? $this->constructorOptions['height'] = $height : '';
        isset($renderAt) ? $this->constructorOptions['renderAt'] = $renderAt : '';
        isset($dataFormat) ? $this->constructorOptions['dataFormat'] = $dataFormat : '';
        isset($dataSource) ? $this->constructorOptions['dataSource'] = $dataSource : '';
        $tempArray = array();
        foreach($this->constructorOptions as $key => $value) {
            if ($key === 'dataSource') {
                $tempArray['dataSource'] = '__dataSource__';
            } else {
                $tempArray[$key] = $value;
            }
        }

        $jsonEncodedOptions = json_encode($tempArray);

        if ($dataFormat === 'json') {
            $jsonEncodedOptions = preg_replace('/\"__dataSource__\"/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
        } elseif ($dataFormat === 'xml') {
            $jsonEncodedOptions = preg_replace('/\"__dataSource__\"/', '\'__dataSource__\'', $jsonEncodedOptions);
            $jsonEncodedOptions = preg_replace('/__dataSource__/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
        } elseif ($dataFormat === 'xmlurl') {
            $jsonEncodedOptions = preg_replace('/__dataSource__/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
        } elseif ($dataFormat === 'jsonurl') {
            $jsonEncodedOptions = preg_replace('/__dataSource__/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
        }
        $newChartHTML = preg_replace('/__constructorOptions__/', $jsonEncodedOptions, $this->constructorTemplate);
        return $newChartHTML;
    }
    // render the chart created
    // It prints a script and calls the FusionCharts javascript render method of created chart
    public function render() {
        $renderHTML = preg_replace('/__chartId__/', $this->constructorOptions['id'], $this->renderTemplate);
        echo $renderHTML;
    }

    public function renderJSON() {
        $jsonEncodedOptions = json_encode($this->constructorOptions);
        $jsonEncodedOptions = preg_replace('/\"__dataSource__\"/', $this->constructorOptions['dataSource'], $jsonEncodedOptions);
        $newChartHTML = preg_replace('/__constructorOptions__/', $jsonEncodedOptions, $this->constructorTemplate);
        echo $newChartHTML;
    }
}