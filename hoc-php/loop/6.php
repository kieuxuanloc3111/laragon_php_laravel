<?php
$students = array(
    array('name' => 'Nguyễn Văn Cường 1', 'email' => 'TheHalfHeart1@gmail.com', 'age' => 29),
    array('name' => 'Nguyễn Văn Cường 2', 'email' => 'TheHalfHeart2@gmail.com', 'age' => 29),
    array('name' => 'Nguyễn Văn Cường 3', 'email' => 'TheHalfHeart3@gmail.com', 'age' => 29),
    array('name' => 'Nguyễn Văn Cường 4', 'email' => 'TheHalfHeart4@gmail.com', 'age' => 29),
    array('name' => 'Nguyễn Văn Cường 5', 'email' => 'TheHalfHeart5@gmail.com', 'age' => 29),
    array('name' => 'Nguyễn Văn Cường 6', 'email' => 'TheHalfHeart6@gmail.com', 'age' => 29)
);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<table>
    <tr>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Tuổi</th>
    </tr>

    <?php foreach ($students as $student): ?>
    <tr>
        <td><?php echo $student['name']; ?></td>
        <td><?php echo $student['email']; ?></td>
        <td><?php echo $student['age']; ?></td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
