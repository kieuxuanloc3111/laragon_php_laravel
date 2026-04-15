<?php
$mang = array();

for ($i = 1; $i <= 100; $i++) {
    $mang[] = $i;
}

echo "Vị trí các số chia hết cho 3:<br>";

for ($index = 0; $index < count($mang); $index++) {
    if ($mang[$index] % 3 == 0) {
        echo "Vị trí: $index (giá trị: $mang[$index])<br>";
    }
}
?>
