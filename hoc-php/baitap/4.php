<?php
echo "Các số chia hết cho 40 từ 1 đến 1000:<br>";

for ($i = 1; $i <= 1000; $i++) {
    if ($i % 40 == 0) {
        echo $i . "<br>";
    }
}
?>
