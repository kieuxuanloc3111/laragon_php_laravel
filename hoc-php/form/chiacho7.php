<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    </head>
    <body>
        <form action="" method="POST">
            <input type="text" name = "number" placeholder="nhập">
            <button type="submit" name="submit">Kiểm tra</button>
        </form>
        <?php 
        if (isset($_POST["submit"])){
            $number = $_POST["number"];
            if(!is_numeric($number)){
                echo "<p>nhập một số hợp lệ.</p>";
            }
            else{
                $number = (int)$number;
                if ($number % 7 == 0) {
                    echo "<p>Số $number chia hết cho 7.</p>";
                } else {
                    echo "<p>Số $number không chia hết cho 7.</p>";
                }
            }
        }
         ?>
    </body>
</html>