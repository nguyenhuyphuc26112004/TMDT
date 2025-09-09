<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'tmdt';
$con = new mysqli($server, $user, $pass, $database);

if ($con) {
    mysqli_query($con, "SET NAMES 'utf8' ");
    // echo 'đã kết nối thành công';
} else {
    echo 'kết nối không thành công';
}
// echo 'hello from connectMysql.php <br>';
