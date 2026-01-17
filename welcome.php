<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
        // Kiểm tra xem biến có tồn tại không trước khi sử dụng
        if (isset($_GET["s"]) && isset($_GET["t"])) {
            $s = floatval($_GET["s"]); 
            $t = floatval($_GET["t"]); 
            $v = $s / $t;
            echo "Van toc na: " . $v;
        } else {
            echo "Vui lòng nhập đầy đủ giá trị!";
        }
    ?>
</body>
</html>