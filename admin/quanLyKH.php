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
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="./css/header1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .container{
            display: flex;
        }
        .quanLyDH{
            margin-top: 50px;
            margin-left: 100px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td,
        th{
            font-size: 18px;
            border: 1px solid #a19898;
            text-align: center;
            padding: 15px;
        }
        td a,
        .create {
            text-decoration: none;
            padding: 8px 10px;
            border: 1px solid white;
            border-radius: 5px;
            margin: 0 5px;
        }
        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }
        .create {
            background-color: #24ACF2;
            color: white;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        td a,
        .title .create:hover {
            background-color: #1c8cd1;
            /* Màu nút khi hover */
        }
        .xem{
            background-color:rgb(8, 209, 95);
            color: white;
        }
        .capNhat{
            background-color:rgb(247, 243, 15);
            color: black;
        }
        .xoa{
            background-color:rgba(227, 20, 5, 0.72);
            color: white;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php
    require('../php/admin/getAllObject.php');
    require('../php/admin/cart.php');
    // hàm của admin/getAll_object.php
    $users = getAll_object($con, 'nguoi_dung');
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
        <div class="quanLyDH">
            <div class="title">
                <h2>Bảng người dùng</h2>
                <a href="dangKy.php" class="create">Tạo mới KH</a>
            </div>
            <table>
                <tr>
                    <th>Id</th>
                    <th>Họ và tên</th>
                    <th>Tên đăng nhập</th>
                    <th>Số điện thoại</th>
                    <th>Hoạt động</th>
                </tr>
                <?php
                foreach ($users as $user) {
                ?>
                    <tr>
                        <td><?php echo $user['id'] ?></td>
                        <td><?php echo $user['ho_ten'] ?></td>
                        <td><?php echo $user['ten_dang_nhap'] ?></td>
                        <td><?php echo $user['so_dien_thoai'] ?></td>
                        <td>
                            <a class = "xem" href="xemCT_KH.php?id=<?php echo $user['id'] ?>">Xem chi tiết</a>
                            <a class = "capNhat" href="capNhat_KH.php?id=<?php echo $user['id'] ?>">Cập nhật</a>
                            <a class = "xoa" href="xoa_KH.php?id=<?php echo $user['id'] ?>">Xóa</a>
                        </td>
                    </tr>
                <?php

                }
                ?>
            </table>
        </div>
    </div>
    
</body>

</html>