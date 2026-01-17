<?php
$connect = mysqli_connect('localhost', 'root', '', 'ycmqmguchosting_dybearidvn',3306);

if (!$connect) {
    echo "Kết nối thất bại";
} else {
    echo "";
}
?>