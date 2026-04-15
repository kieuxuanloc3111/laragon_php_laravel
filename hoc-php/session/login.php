<?php 
session_start();

$errEmail = $errPass = "";
$email = $pass = "";
$result = "";

if (isset($_POST['login'])) {

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

        if (!isset($_SESSION['reg_email']) || !isset($_SESSION['reg_pass'])) {
            $result = "<p style='color:red;'>Chưa có tài khoản. Hãy đăng ký trước!</p>";
        } else {

            if ($email === $_SESSION['reg_email'] && $pass === $_SESSION['reg_pass']) {
                $result = "<p style='color:green;'>Login thành công!</p>";
            } else {
                $result = "<p style='color:red;'>Sai email hoặc mật khẩu</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form method="POST">
    <br> Email: 
    <input type="text" name="email" value="<?php echo $email; ?>">
    <div style="color:red;"><?php echo $errEmail; ?></div>
    <br>

    <br> Mật khẩu: 
    <input type="password" name="pass" value="<?php echo $pass; ?>">
    <div style="color:red;"><?php echo $errPass; ?></div>
    <br>

    <button type="submit" name="login">Login</button>
</form>

<br>
<?php echo $result; ?>

</body>
</html>
