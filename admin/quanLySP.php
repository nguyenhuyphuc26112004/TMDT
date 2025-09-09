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
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="css/header1.css">
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
    $products = getAll_object($con, 'san_pham');
    ?>
    
    <div class="container" >
        <div class="trangchu">
            <img src="./logo/logo.jpg" alt="">
            <div class="tieude">
                <p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p>
            </div>
            <div class="list-tieude">
                <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
                    <div class="danhmuc">
                        <a href="quanLySP.php">Quản lý sản phẩm</a><br>
                        <a href="quanLyKH.php">Quản lý người dùng</a><br>
                        <a href="quanLyDH.php">Quản lý đơn hàng</a>
                    </div>
                <p> <i class="fa-solid fa-layer-group"></i> Tài khoản</p>
                    <div class="danhmuc">
                        <a href="../php/admin/logoutAdmin.php"> <i class="fa-solid fa-circle-user"></i> Đăng xuất</a>
                    </div>
            </div>
        </div>
        <div class="quanLyDH">
            <div class="title">
                <h2>Bảng sản phẩm</h2>
                <a href="taoMoi_SP.php" class="create">Tạo mới SP</a>
            </div>
            <table>
                <tr>
                    <th>Id</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th>Loại</th>
                    <th>Hoạt động</th>
                </tr>
                <?php
                foreach ($products as $product) {

                ?>
                    <tr>
                        <td><?php echo $product['id'] ?></td>
                        <td><?php echo $product['ten'] ?></td>
                        <td><?php echo $product['so_luong'] ?></td>
                        <td><?php echo $product['trang_thai'] == 1 ? 'Còn hàng' : 'Hết hàng' ?></td>
                        <td><?php echo $product['loai'] ?></td>
                        <td>
                            <a class = "xem" href="xemCT_SP.php?id=<?php echo $product['id'] ?>">Xem chi tiết</a>
                            <a class = "capNhat" href="capNhat_SP.php?id=<?php echo $product['id'] ?>" >Cập nhật</a>
                            <?php 
                                if ($product['trang_thai'] == 1) 
                                {
                            ?>
                                <a class = "xoa" href="xoa_SP.php?id=<?php echo $product['id'] ?>">Xóa</a>
                            <?php
                                } 
                            ?>

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