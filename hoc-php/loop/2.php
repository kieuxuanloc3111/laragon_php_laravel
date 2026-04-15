<?php 
$array= [1,23,32,5,43,321,312,352,2,13,153,21,32,1];

$max= $array[0];
$index= 0;
for($i=1;$i<count($array);$i++){
    if ($array[$i] > $max) {
        $max = $array[$i];
        $index = $i;
    }
}
echo $max .  "<br>";
echo  $index;
?>