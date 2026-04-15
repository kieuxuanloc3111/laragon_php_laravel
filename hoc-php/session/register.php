<?php 
session_start();

$errEmail = $errPass = "";
$email = $pass = "";

if (isset($_POST['register'])) {

    $email = trim($_POST['email']);
    $pass  = trim($_POST['pass']);

    $valid = true;

    if ($email === "") {
        $errEmail = "Vui lòng nhập email";
        $valid = false;
    }

    if ($pass === "") {
        $errPass = "Vui lòng nhập mật khẩu";
        $valid = false;
    }

    if ($valid) {
        $_SESSION['reg_email'] = $email;
        $_SESSION['reg_pass']  = $pass;

        echo "<h3 style='color:green;'>Đăng ký thành công! Dữ liệu đã lưu vào SESSION</h3>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>

<h2>Register</h2>

<form method="POST">
    <br> Email: 
    <input type="text" name="email" value="<?php echo $email; ?>">
    <div style="color:red;"><?php echo $errEmail; ?></div>
    <br>

    <br> Mật khẩu: 
    <input type="password" name="pass" value="<?php echo $pass; ?>">
    <div style="color:red;"><?php echo $errPass; ?></div>
    <br>

    <button type="submit" name="register">Đăng ký</button>
</form>


</body>
</html>
