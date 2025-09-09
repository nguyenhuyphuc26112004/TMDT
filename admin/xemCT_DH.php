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
    <title>Chi tiết đơn hàng</title>
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
            border: 1px solid  #dbdbdb;
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
    $idDonHang = $_GET['id'];

    $donHang = getObjectById($con, 'don_hang', $idDonHang);

    ?>
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
                <h2 style="font-size:23px">Quản lý đơn hàng</h2>
            </div>
            <div class="main">
                <form action="">
                    <table>
                        <tr>
                            <th colspan="2" style="text-align: center;">Chi tiết đơn hàng</th>
                        </tr>
                        <tr>
                            <th>ID đơn hàng</th>
                            <td><?php echo $donHang['id'] ?></td>
                        </tr>
                        <tr>
                            <th>Tên người nhận </th>
                            <td> <?php echo $donHang['ten'] ?></td>
                        </tr>
                        <tr>
                            <th>Số điện thoại </th>
                            <td> <?php echo $donHang['sdt'] ?></td>
                        </tr>

                        <tr>
                            <th>Địa chỉ</th>
                            <td> <?php echo $donHang['dia_chi'] ?></td>
                        </tr>

                        <tr>
                            <th>Thành tiền</th>
                            <td><?php echo number_format ($donHang['tong_tien'], 0, ',', '.') ?> VMĐ</td>
                        </tr>
                        <tr>
                            <th>Trạng thái đơn hàng</th>
                            <td><?php echo $donHang['trang_thai'] ?></td>
                        </tr>
                        <tr>
                            <th>Phương thức thanh toán</th>
                            <td>Thanh toán khi nhận hàng</td>
                        </tr>
                    </table>
                    <div class="submit">
                        <a href="quanLyDH.php" style="background-color: #1C8552; color : white;">Trở lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>