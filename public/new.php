
<?php
//ini_set(error_reporting, 1);
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit', -1); 
$totals = array();
$x=0;
$minval = 0;
$left = 0;

echo '<pre>';
function getAllCombinations($ind, $denom, $n, $vals=array()){
    global $totals, $x, $minval, $left,$sum;

    //print_r($vals);
    //echo " -- $n -- ";
    if ($n <= $minval){
        foreach ($vals as $key => $qty){
            for(; $qty>0; $qty--){
                $totals[$x][] = $denom[$key];
            }
        }

        $left = $n; 

        $x++;
        return;
    }
    echo $ind+"###<br>";
    if ($ind == count($denom)) return;
    $currdenom = $denom[$ind];
    for ($i=0;$i<=($n/$currdenom);$i++){
        $vals[$ind] = $i;
        echo $ind."###<br>";
        //if($ind<=10)
            getAllCombinations($ind+1,$denom,$n-($i*$currdenom),$vals);
    }
}

//$array = array(2, 5, 7, 14);

$sumarea = 500;
$link = mysql_connect("localhost", "root", "") or die("Could not connect: " . mysql_error());
echo 'Connected successfully';
mysql_select_db("assesstool");
$qry = "select area from residential_plots where area <= ".$sumarea;//." order by area desc";
$result = mysql_query($qry);
$numbers = [];
while($row = mysql_fetch_array($result))
{
    $numbers[] = $row['area'];
}
mysql_close($link);

echo "<pre>";
print_r($numbers);


$minval = $numbers[count($numbers)-1];

getAllCombinations(0, $numbers, $sumarea);
echo "no of records:".count($totals);
//var_dump($totals);
foreach($totals as $total){
    echo "------------<br>";
    foreach($total as $tot){
        echo $tot."<br>";
    }
    echo "------left--".($sumarea - array_sum($total))."---<br>";
}
?>