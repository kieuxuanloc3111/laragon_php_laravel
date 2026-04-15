<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="form.css">
</head>
<body>

<?php

$email = '';
$password = '';
$errors = [];
$submitted = false;

if (isset($_POST['submit'])) {
    $submitted = true;

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email === '') {
        $errors['email'] = "Vui lòng nhập email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ.";
    }

    if ($password === '') {
        $errors['password'] = "Vui lòng nhập password.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password phải >= 6 ký tự.";
    }

    if (empty($errors)) {
        echo '<div class="success">Gửi form thành công! Email: <strong>' . htmlspecialchars($email) . '</strong></div>';
    } else {
        echo '<div class="info">Form có lỗi — xem bên dưới.</div>';
    }
}
?>

<form action="" method="POST" novalidate>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" type="text" name="email"
               value="<?php echo htmlspecialchars($email); ?>"
               placeholder="vd: you@example.com">

        <?php if (isset($errors['email'])): ?>
            <div class="error"><?php echo $errors['email']; ?></div>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input id="password" type="text" name="password"
               value="<?php echo htmlspecialchars($password); ?>"
               placeholder="ít nhất 6 ký tự">

        <?php if (isset($errors['password'])): ?>
            <div class="error"><?php echo $errors['password']; ?></div>
        <?php endif; ?>
    </div>

    <button class="btn" type="submit" name="submit">Gửi</button>
</form>

</body>
</html>
