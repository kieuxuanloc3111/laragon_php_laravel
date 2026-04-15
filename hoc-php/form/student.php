<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<form action="" method="POST">

    <div>
        <label>điểm toán:</label><br>
        <input type="text" name="toan" value="<?php echo $_POST['toan'] ?? ''; ?>">
        <?php
        if (isset($_POST['submit'])) {
            if ($_POST['toan'] === '') {
                echo "<div style='color:red;'>nhập điểm Toán</div>";
            } elseif (!is_numeric($_POST['toan']) || $_POST['toan'] < 0 || $_POST['toan'] > 10) {
                echo "<div style='color:red;'>nhập điểm hợp lệ</div>";
            }
        }
        ?>
    </div>
    <br>

    <div>
        <label>điểm lý:</label><br>
        <input type="text" name="ly" value="<?php echo $_POST['ly'] ?? ''; ?>">
        <?php
        if (isset($_POST['submit'])) {
            if ($_POST['ly'] === '') {
                echo "<div style='color:red;'> nhập điểm Lý</div>";
            } elseif (!is_numeric($_POST['ly']) || $_POST['ly'] < 0 || $_POST['ly'] > 10) {
                echo "<div style='color:red;'>nhập điểm hợp lệ</div>";
            }
        }
        ?>
    </div>
    <br>

    <div>
        <label>điểm hóa:</label><br>
        <input type="text" name="hoa" value="<?php echo $_POST['hoa'] ?? ''; ?>">
        <?php
        if (isset($_POST['submit'])) {
            if ($_POST['hoa'] === '') {
                echo "<div style='color:red;'>nhập điểm Hóa</div>";
            } elseif (!is_numeric($_POST['hoa']) || $_POST['hoa'] < 0 || $_POST['hoa'] > 10) {
                echo "<div style='color:red;'>nhập điểm hợp lệ</div>";
            }
        }
        ?>
    </div>
    <br>

    <button type="submit" name="submit">Kiểm tra kết quả</button>
</form>

<br><br>

<?php
if (isset($_POST['submit'])) {

    $toan = $_POST['toan'];
    $ly   = $_POST['ly'];
    $hoa  = $_POST['hoa'];

    $valid = true;

    if ($toan === '' || !is_numeric($toan) || $toan < 0 || $toan > 10) $valid = false;
    if ($ly === ''   || !is_numeric($ly)   || $ly < 0   || $ly > 10) $valid = false;
    if ($hoa === ''  || !is_numeric($hoa)  || $hoa < 0  || $hoa > 10) $valid = false;

    if ($valid) {
        $toan = (float)$toan;
        $ly   = (float)$ly;
        $hoa  = (float)$hoa;

        $tong = $toan + $ly + $hoa;

        if ($toan == 1 || $ly == 1 || $hoa == 1) {
            echo "<div style='color:red;'>rớt ,có môn bị 1 điểm</div>";
        }

        elseif ($tong >= 15) {
            echo "<div style='color:green;'>đậu ,tổng điểm = $tong</div>";
        }
        else {
            echo "<div style='color:red;'>rớt ,tổng điểm  = $tong</div>";
        }
    }
}
?>

</body>
</html>
