<?php
session_start();

if (isset($_POST['save-session'])) {
    $_SESSION['name'] = $_POST['username'];
}

if (isset($_POST['delete-session'])) {
    unset($_SESSION['name']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Session Demo</title>
</head>
<body>

<h1>
<?php
if (isset($_SESSION['name'])) {
    echo 'Tên Đăng Nhập Là: ' . $_SESSION['name'];
} else {
    echo 'Chưa có session';
}
?>
</h1>

<form method="POST" action="">
    <input type="text" name="username" placeholder="Nhập username"><br><br>

    <input type="submit" name="save-session" value="Lưu Session">
    <input type="submit" name="delete-session" value="Xóa Session">
</form>

</body>
</html>
