<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
</head>
<body>
    <?php
    echo '<pre>';
    var_dump($_FILES);
    // Nếu người dùng click Upload
    if (isset($_POST['uploadClick']))
    {
        // var_dump($_FILES['avatar']) ;

        // Nếu người dùng có chọn file để upload
        if (!empty($_FILES['avatar']['name']))
        {
            if ($_FILES['avatar']['error'] > 0)
            {
                echo 'File Upload Bị Lỗi';
            }
            else
            {
                // move uploaded file: hàm upload file
                move_uploaded_file($_FILES['avatar']['tmp_name'], './loc/'.$_FILES['avatar']['name']);
                echo 'File Uploaded';
            }
        }
        else{
            echo 'Bạn chưa chọn file upload';
        }
    }
    ?>

    <form method="post" action="" enctype="multipart/form-data">
        <input type="file" name="avatar"/>
        <input type="submit" value="uploadclick" value= "Upload"/>

    </form>
</body>

</html>