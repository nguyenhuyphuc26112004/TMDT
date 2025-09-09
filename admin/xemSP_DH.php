<!-- hiển thị tên và ảnh sp 
    hiển thị số lượng sp mà người dùng mua /1 sp
 -->


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
    <link rel="stylesheet" href="css/header1.css">
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
            font-size: 18px;
            border: 1px solid #cecece;
            text-align: center;
            padding: 15px;
        }
        /* chọn các phần tử thứ tự chẵn (2, 4, 6, ...) trong số các hàng <tr> */
        /* tr:nth-child(even) {
            Áp dụng màu nền (một tông màu xám nhạt) cho các hàng chẵn được chọn
            background-color: #dddddd;
        } */
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
        .submit{
            margin-top: 10px;
            display:flex;
            justify-content:center;
        }
        .submit a{
            padding: 10px;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php
    require('../php/admin/orderDetail.php');
    require('../php/admin/getObjectById.php');

    $idDonHang = $_GET['id'];
    $ctDonHang = getOrderDetailByOrder($con, $idDonHang);


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
                    <th>Id đơn hàng </th>
                    <th>Id sản phẩm </th>
                    <th>Tên sản phẩm </th>
                    <th>Số lượng </th>
                    <th>Giá sản phẩm </th>
                    <th>Ảnh sản phẩm </th>

                </tr>
                <?php
                foreach ($ctDonHang as $cTDH) {
                    $sp = getObjectById($con, 'san_pham', $cTDH['id_san_pham']);
                ?>
                    <tr>
                        <td><?php echo $cTDH['id_don_hang'] ?></td>
                        <td><?php echo $cTDH['id_san_pham'] ?></td>
                        <td><?php echo $sp['ten'] ?></td>
                        <td><?php echo $cTDH['so_luong'] ?></td>
                        <td><?php echo $cTDH['gia'] ?> đ</td>
                        <td>
                            <img src="img/<?php echo $sp['anh'] ?>" alt="" width="200px" height="200px">
                        </td>
                    </tr>
                <?php
                } 
                ?>
            </table>
            <div class="submit">
                <a href="quanLyDH.php" style="background-color: #1C8552; color : white;">Trở lại</a>
            </div>
        </div>
    </div>
</body>

</html>