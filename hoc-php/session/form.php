<?php 
session_start();

$err_email = $err_pass = $err_city = "";
$email = $pass = $city = "";

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $pass  = trim($_POST['pass']);
    $city  = trim($_POST['city']);

    $isValid = true;

    if ($email === "") {
        $err_email = "Vui lòng nhập";
        $isValid = false;
    }

    if ($pass === "") {
        $err_pass = "Vui lòng nhập ";
        $isValid = false;
    }

    if ($city === "") {
        $err_city = "Vui lòng nhập";
        $isValid = false;
    }
    if ($isValid) {
        $demo = [
            "email" => $email,
            "pass" => $pass,
            "city" => $city
        ];
        $_SESSION['form'][] = $demo;

        echo "<p style='color:green'>Lưu thành công!</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h2>Form</h2>

<form method="POST">

    <br>Email:
    <input type="text" name="email" value="<?php echo $email; ?>">
    <div style="color:red"><?php echo $err_email; ?></div>
    <br>

    <br>Password: 
    <input type="text" name="pass" value="<?php echo $pass; ?>">
    <div style="color:red"><?php echo $err_pass; ?></div>
    <br>

    <br>City: 
    <input type="text" name="city" value="<?php echo $city; ?>">
    <div style="color:red"><?php echo $err_city; ?></div>
    <br>

    <button type="submit" name="submit">Lưu </button>
</form>

</body>
</html>