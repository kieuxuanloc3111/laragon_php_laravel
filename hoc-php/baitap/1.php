<?php
$mang = array(321,312,3,4,5,45,56,5,7,6,787,8,7,2);

function kiemTraSo($array, $soCanTim) {
    for ($i = 0; $i < count($array); $i++) {
        if ($array[$i] == $soCanTim) {
            return true; 
        }
    }
    return false;
}

$so = 67;

if (kiemTraSo($mang, $so)) {
    echo "Số $so có nằm trong mảng.";
} else {
    echo "Số $so KHÔNG nằm trong mảng.";
}
?>
