<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
<?php
$toan = $ly = $hoa = $anh = $van = $su = "";
$err_toan = $err_ly = $err_hoa = $err_anh = $err_van = $err_su = "";
$ketqua = "";
if (isset($_POST["submit"])) {
    $toan = $_POST["toan"];
    $ly   = $_POST["ly"];
    $hoa  = $_POST["hoa"];
    $anh  = $_POST["anh"];
    $van  = $_POST["van"];
    $su   = $_POST["su"];
    $valid = true;
    if ($toan === "") { $err_toan = "Nhập điểm Toán"; $valid = false; }
    elseif (!is_numeric($toan) || $toan < 0 || $toan > 10) { $err_toan = "Điểm không hợp lệ"; $valid = false; }

    if ($ly === "") { $err_ly = "Nhập điểm Lý"; $valid = false; }
    elseif (!is_numeric($ly) || $ly < 0 || $ly > 10) { $err_ly = "Điểm không hợp lệ"; $valid = false; }

    if ($hoa === "") { $err_hoa = "Nhập điểm Hóa"; $valid = false; }
    elseif (!is_numeric($hoa) || $hoa < 0 || $hoa > 10) { $err_hoa = "Điểm không hợp lệ"; $valid = false; }

    if ($anh === "") { $err_anh = "Nhập điểm Anh"; $valid = false; }
    elseif (!is_numeric($anh) || $anh < 0 || $anh > 10) { $err_anh = "Điểm không hợp lệ"; $valid = false; }

    if ($van === "") { $err_van = "Nhập điểm Văn"; $valid = false; }
    elseif (!is_numeric($van) || $van < 0 || $van > 10) { $err_van = "Điểm không hợp lệ"; $valid = false; }

    if ($su === "") { $err_su = "Nhập điểm Lịch Sử"; $valid = false; }
    elseif (!is_numeric($su) || $su < 0 || $su > 10) { $err_su = "Điểm không hợp lệ"; $valid = false; }

    if ($valid) {
        $toan = (float)$toan;
        $ly   = (float)$ly;
        $hoa  = (float)$hoa;
        $anh  = (float)$anh;
        $van  = (float)$van;
        $su   = (float)$su;
        if ($toan < 4 || $ly < 4 || $hoa < 4 || $anh < 4 || $van < 4 || $su < 4) {
            $ketqua = "<div style='color:red;'>Học lực: YẾU (có môn dưới 4)</div>";
        } else {
            $avg = ($toan + $ly + $hoa + $anh + $van + $su) / 6;
            $avg = number_format($avg, 2);

            if ($avg < 5) {
                $ketqua = "<div style='color:red;'>Học lực: YẾU : $avg</div>";
            } elseif ($avg <= 6.4) {
                $ketqua = "<div style='color:blue;'>Học lực: TRUNG BÌNH  :$avg</div>";
            } elseif ($avg <= 7.9) {
                $ketqua = "<div style='color:green;'>Học lực: KHÁ : $avg</div>";
            } else {
                $ketqua = "<div style='color:purple;'>Học lực: GIỎI : $avg</div>";
            }
        }
    }
}
?>
<form action="" method="POST">

    <label>Toán:</label><br>
    <input type="text" name="toan" value="<?php echo $toan; ?>">
    <div style="color:red;"><?php echo $err_toan; ?></div><br>

    <label>Lý:</label><br>
    <input type="text" name="ly" value="<?php echo $ly; ?>">
    <div style="color:red;"><?php echo $err_ly; ?></div><br>

    <label>Hóa:</label><br>
    <input type="text" name="hoa" value="<?php echo $hoa; ?>">
    <div style="color:red;"><?php echo $err_hoa; ?></div><br>

    <label>Tiếng Anh:</label><br>
    <input type="text" name="anh" value="<?php echo $anh; ?>">
    <div style="color:red;"><?php echo $err_anh; ?></div><br>

    <label>Văn:</label><br>
    <input type="text" name="van" value="<?php echo $van; ?>">
    <div style="color:red;"><?php echo $err_van; ?></div><br>

    <label>Lịch Sử:</label><br>
    <input type="text" name="su" value="<?php echo $su; ?>">
    <div style="color:red;"><?php echo $err_su; ?></div><br>

    <button type="submit" name="submit">Xét học lực</button>
</form>

<br>
<?php echo $ketqua; ?>

</body>
</html>
