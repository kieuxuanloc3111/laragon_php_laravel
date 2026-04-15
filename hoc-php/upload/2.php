<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php
echo '<pre>';
var_dump($_FILES);
echo '</pre>';

if (isset($_POST['uploadClick'])) {

    if (!empty($_FILES['avatar']['name'])) {

        if ($_FILES['avatar']['error'] !== 0) {
            echo 'File upload bị lỗi';
        } else {

            $uploadDir = './loc2/';

            if (!is_dir($uploadDir)) {
                echo 'Thư mục không tồn tại';
            } else {
                $maxSize = 1024 * 1024; 

                if ($_FILES['avatar']['size'] > $maxSize) {
                    echo 'File quá to';
                } else {

                    $fileName = $_FILES['avatar']['name'];
                    $parts = explode('.', $fileName);
                    $fileExt = strtolower(end($parts));

                    $allowExt = ['jpg', 'jpeg', 'png'];

                    if (!in_array($fileExt, $allowExt)) {
                        echo 'Chỉ cho phép upload ảnh';
                    } else {

                        if (move_uploaded_file($_FILES['avatar']['tmp_name'], './loc2/' . $_FILES['avatar']['name'])) {
                            echo 'Upload thành công<br>';
                        } else {
                            echo ' Upload thất bại';
                        }
                    }
                }
            }
        }
    } else {
        echo 'Bạn chưa chọn file upload';
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="avatar"><br><br>
    <input type="submit" name="uploadClick" value="Upload">
</form>

</body>
</html>
