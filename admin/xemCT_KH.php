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
    <title>Chi tiết khách hàng</title>
    <link rel="stylesheet" href="./css/header1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .container{
            display: flex;
        }
        .view {
            margin: 80px auto;
            height: 100vh;
            width:68%;
            font-family: Arial, sans-serif;
        }
        .view .title {
            border-bottom: 1px solid #979494;
            margin: 25px 0px 25px 0px;
            font-size: 24px;
        }
        .main table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        td,
        th {
            border: 1px solid  #cecece;
            text-align: center;
            padding: 13px;
        }        
        .submit {
            margin: 25px 40%;
        }
        .submit a {
            text-decoration: none;
            border-radius: 5px;
            border:1px solid #acacac;
            padding:8px 20px;
            height:20px;
            width:100px;
            font-size: 17px;
        }
    </style>
</head>
<body>
    <!-- header -->
    <?php
    require('../php/admin/getObjectById.php');
    require('../php/admin/getRoleById.php');

    $idCurrent = $_GET['id']; // lấy id từ link url 

    // gọi hàm của getObjectById.php
    $User = getObjectById($con, 'nguoi_dung', $idCurrent);
    // gọi hàm của getRoleById.php
    $Role = getRoleById($con, $User['id_vai_tro']);
    ?>
    <!-- code -->
    <div class="container">
        <div class="trangchu">
            <img src="./logo/logo.jpg" alt="">
            <div class="tieude">
                <p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p>
            </div>
            <div class="list-tieude">
                <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
                    <div class="danhmuc">
                        <a href="quanLySP.php">Quản lý sản phẩm</a>
                        <a href="quanLyKH.php">Quản lý người dùng</a>
                        <a href="quanLyDH.php">Quản lý đơn hàng</a>
                    </div>
                <p> <i class="fa-solid fa-layer-group"></i> Đăng nhập hệ thống</p>
                    <div class="danhmuc">
                        <a href="../php/admin/logoutAdmin.php"> <i class="fa-solid fa-circle-user"></i> Đăng xuất</a>
                    </div>
            </div>
        </div>
        <div class="view">
            <div class="title">
                <h2 style="font-size:23px">Quản lý tài khoản</h2>
            </div>
            <div class="main">
                <form action="">
                    <table>
                        <tr>
                            <th colspan="2" style="text-align: center;">Chi tiết khách hàng id = <?php echo $_GET['id'] ?></th>
                        </tr>
                        <tr>
                            <th>ID khách hàng</th>
                            <td><?php echo $User['id'] ?></td>
                        </tr>
                        <tr>
                            <th>Họ và tên</th>
                            <td><?php echo $User['ho_ten'] ?></td>
                        </tr>
                        <tr>
                            <th>Giới tính</th>
                            <td><?php echo $User['gioi_tinh'] ?></td>
                        </tr>
                        <tr>
                            <th>Số điện thoại</th>
                            <td><?php echo $User['so_dien_thoai'] ?></td>
                        </tr>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <td><?php echo $User['ten_dang_nhap'] ?></td>
                        </tr>
                        <tr>
                            <th>Mật khẩu</th>
                            <td><?php echo $User['mat_khau'] ?></td>
                        </tr>
                        <tr>
                            <th>Vai trò</th>
                            <td><?php echo $Role['ten'] ?></td>
                        </tr>

                    </table>
                    <div class="submit">
                        <a href="quanLyKH.php" style="background-color: #1C8552; color : white;">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>   
</body>
</html>