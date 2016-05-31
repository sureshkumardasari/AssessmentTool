<?php
$data = array(array(2,3,5,10,15),array(4,6,23,15,12),array(23,34,12,1,5));
$maxsum = 25;

print_r(bestsum($data,$maxsum));  //function call

function bestsum($data,$maxsum)
{
$res = array_fill(0, $maxsum + 1, '0');
$res[0] = array();              //base case
foreach($data as $group)
{
 $new_res = $res;               //copy res

  foreach($group as $ele)
  {
    for($i=0;$i<($maxsum-$ele+1);$i++)
    {   
        if($res[$i] != 0)
        {
            $ele_index = $i+$ele;
            $new_res[$ele_index] = $res[$i];
            $new_res[$ele_index][] = $ele;
        }
    }
  }

  $res = $new_res;
}

 for($i=$maxsum;$i>0;$i--)
  {
    if($res[$i]!=0)
    {
        return $res[$i];
        break;
    }
  }
return array();
}
?>
