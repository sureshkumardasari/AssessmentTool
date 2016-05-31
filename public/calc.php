<?php
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit', -1); 


$sumarea = 300;
$link = mysql_connect("localhost", "root", "") or die("Could not connect: " . mysql_error());
echo 'Connected successfully';
mysql_select_db("assesstool");
$qry = "select area from residential_plots where area <= ".$sumarea ." order by area desc";
$result = mysql_query($qry);
$numbers = [];
while($row = mysql_fetch_array($result))
{
    $numbers[] = $row['area'];
}
mysql_close($link);

echo "<pre>";
print_r($numbers);
/*
 static void sum_up_recursive(ArrayList<Integer> numbers, int target, ArrayList<Integer> partial) {
       int s = 0;
       for (int x: partial) s += x;
       if (s == target)
            System.out.println("sum("+Arrays.toString(partial.toArray())+")="+target);
       if (s >= target)
            return;
       for(int i=0;i<numbers.size();i++) {
             ArrayList<Integer> remaining = new ArrayList<Integer>();
             int n = numbers.get(i);
             for (int j=i+1; j<numbers.size();j++) remaining.add(numbers.get(j));
             ArrayList<Integer> partial_rec = new ArrayList<Integer>(partial);
             partial_rec.add(n);
             sum_up_recursive(remaining,target,partial_rec);
       }
    }
    static void sum_up(ArrayList<Integer> numbers, int target) {
        sum_up_recursive(numbers,target,new ArrayList<Integer>());
    }
    public static void main(String args[]) {
        Integer[] numbers = {3,9,8,4,5,7,10};
        int target = 15;
        sum_up(new ArrayList<Integer>(Arrays.asList(numbers)),target);
    }
*/
    #Outputs:
    #sum([3, 8, 4])=15
    #sum([3, 5, 7])=15
    #sum([8, 7])=15
    #sum([5, 10])=15
function sum_up_recursive($numbers, $target, $partial=array()) {
       $s = 0;
       for ($k=0; $k<count($partial);$k++) {
            $s += $partial[$k];
        }
//print_r($numbers);

       echo $s+"<br>";
       // exit;
           if ($s == $target)
                print(print_r($partial)+$target); echo "<br>";
           if ($s >= $target)
                return;
           for($i=0;$i<count($numbers);$i++) {
                 $remaining = array();
                 $n = $numbers[$i];
                 for ($j=$i+1; $j<count($numbers);$j++){
                    array_push($remaining,$numbers[$j]);
                }
                 $partial_rec = $partial;
                 array_push($partial_rec,$n);

//print_r($partial_rec); exit;
                 sum_up_recursive($remaining,$target,$partial_rec);
                 //echo $s;
//exit;
           }
     //} 
    }
    
    function sum_up($numbers, $target) {
        sum_up_recursive($numbers,$target,array());
    }
    //public static void main(String args[]) {
       // $numbers = array(3,9,8,4,5,7,10);
       // $sumarea = 15;
        sum_up($numbers,$sumarea);
   // }




/*$numbers = array(
    2,
    8,
    16,
    30,
    44,
    48
);

$solutions = array();

function generate($k, $solution)
{
    global $solutions, $numbers;
    if (count($solution) == 4) {
        $solutions[] = $solution;
    }
    if (count($solution) < 4)
        for ($i = $k; $i < count($numbers); $i++) {
            $solution[] = $numbers[$i];
            generate($i + 1, $solution);
            array_pop($solution);
        }

}

generate(0, array());

echo "<P>Total number of combinations:" . count($solutions) . "</p>";
echo "<p> solutions: </p>";
foreach ($solutions as $sol) {
  if(array_sum($sol) == 96)
    echo "<p> {$sol[0]} {$sol[1]} {$sol[2]} {$sol[3]}</p>";
}*/
?>