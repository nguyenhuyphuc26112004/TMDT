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
    <title>Quản lý đơn hàng</title>
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
            margin-left: 50px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td,
        th{
            font-size: 16px;
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
            font-size:16px;
        }
        .capNhat{
            background-color:rgb(247, 243, 15);
            color: black;
            font-size:16px;

        }
        .xoa{
            background-color:rgba(227, 20, 5, 0.72);
            color: white;
            font-size:16px;

        }
        .xemSP{
            background-color: #1C8CD1;
            color : white;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php
    require('../php/admin/getAllObject.php');
    $donHang = getAll_object($con, 'don_hang');
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
                <h2>Bảng đơn hàng</h2>
            </div>
            <table>
                <tr>
                    <th>Id</th>
                    <th>Id người dùng</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức thanh toán </th>
                    <th>Trạng thái</th>
                    <th>Hoạt động</th>
                </tr>
                <?php
                foreach ($donHang as $dH) {
                ?>
                    <tr>
                        <td><?php echo $dH['id'] ?></td>
                        <td><?php echo $dH['id_nguoi_dung'] ?></td>
                        <td><?php echo number_format ($dH['tong_tien'], 0, ',', '.'), " VNĐ" ?></td>
                        <td>Khi nhận hàng</td>
                        <td><?php echo $dH['trang_thai'] ?></td>
                        <td>
                            <a class = "xem" href="xemCT_DH.php?id=<?php echo $dH['id'] ?>">Xem chi tiết</a>
                            <a class = "capNhat"href="capNhat_DH.php?id=<?php echo $dH['id'] ?>">Cập nhật</a>
                            <a class = "xemSP" href="xemSP_DH.php?id=<?php echo $dH['id'] ?>">Xem sản phẩm</a>
                            <?php
                            if ($dH['trang_thai'] == 'Đã hủy') {

                            ?>
                                <a class = "xoa" href="xoa_DH.php?id=<?php echo $dH['id'] ?>">Xóa</a>
                            <?php
                            } ?>
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