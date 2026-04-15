<?php
$a = 10;
$b = 55;
$c = 32;

$max = $a;

if ($b > $max) {
    $max = $b;
}

if ($c > $max) {
    $max = $c;
}

echo "Giá trị lớn nhất: $max";
?>
