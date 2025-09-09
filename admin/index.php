<?php
// ktra người dùng đăng nhập hay chưa
require('../php/checkSession.php');
checkSession(2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - admin</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- header -->
    <?php
    require('layout/header1.php');
    ?>
    <!-- end header -->
    <h1>
        Giới thiệu về trang admin <br>
        Mô tả các chức năng của trang admin
    </h1>
    <h3 style="color:red"><?php echo $_SESSION['tenDangNhap'] ?> Đang đăng nhập</h3>
</body>

</html>