<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<h2>table</h2>

<table border="1" >
    <tr>
        <th>Email</th>
        <th>Password</th>
        <th>City</th>
    </tr>

    <?php 
    if (!empty($_SESSION['form'])) {
        foreach ($_SESSION['form'] as $item) {
            echo "<tr>";
            echo "<td>{$item['email']}</td>";
            echo "<td>{$item['pass']}</td>";
            echo "<td>{$item['city']}</td>";
            echo "</tr>";
        }
    }
    ?>
</table>

</body>
</html>
