<?php
// ktra người dùng đăng nhập hay chưa
require('php/checkSession.php');
checkSessionClient();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng </title>
    <style>
        .quanLyDH {
            margin: 50px 30px;
            height: 100vh;

        }
        .quanLyDH table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 13px;
        }
        td a {
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 5px;
            width: 6px;
            color: white;
        }

        .title-cart h2 {
            display: flex;
            justify-content: space-between;
            /* Đẩy các phần tử ra 2 bên */
            align-items: center;
            /* Căn giữa các phần tử theo chiều dọc */
            padding: 10px;
            /* Thêm khoảng cách bên trong */
        }

        td .pay {
            padding: 10px;
            background-color: green;
            color: #fff;
        }

        td .pay:hover {
            cursor: pointer;
            opacity: 0.6;
        }

        .gio-hang-thong-bao {
            color: red;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
            margin-top: 200px;
        }

        .thong-bao {
            color: red;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php
        require('layout/header.php');
        require('php/client/cart.php');
        require('php/client/getObjectById.php');

    /* 
            Logic trang giỏ hàng
        - hiển thi sản phẩm của người dung hiện tại
            -> lấy cart -> lấy cartDetails
        - xóa sản
    */
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Chỉ gọi session_start nếu chưa có session nào được khởi động
    }

    $idNguoiDung = $_SESSION['idNguoiDung'];
    $gioHang = checkCart($con, $idNguoiDung);
    if ($gioHang == null) {
        // echo ">>>>>>>>>>>> không lấy được giỏ hàng";
        echo '<h1 class="gio-hang-thong-bao">Bạn chưa thêm sản phẩm nào vào giỏ hàng</h1>';

        exit;
    } else {
        // mảng lưu tất cả sp của người dùng 
        $cTGioHang = getCartDetailByCart($con, $gioHang['id']);

    ?>
        <div class="quanLyDH">
            <div class="title-cart">
                <h1>Giỏ hàng</h1>
            </div>
            <table>
                <tr>
                    <th>Ảnh sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng </th>
                    <th>Giá </th>
                    <th>Hành động </th>
                </tr>
                <?php
                $tongTien = 0;
                foreach ($cTGioHang as $cTGH) {
                    $tongTien += ($cTGH['so_luong'] * $cTGH['gia']);
                    // lấy sp thông qua id sản phẩm
                    $sp = getObjectById($con, 'san_pham', $cTGH['id_san_pham']);
                ?>
                    <tr>
                        <td>
                            <img src="admin/img/<?php echo $sp['anh'] ?>" alt="" width="60px" height="60px">
                        </td>
                        <td>
                            <a style="color : black;" href="xemChiTietSP.php?id=<?php echo $sp['id'] ?>"><?php echo $sp['ten'] ?></a>
                        </td>
                        <td><?php echo $cTGH['so_luong'] ?> </td>
                        <td><?php echo number_format($cTGH['so_luong'] * $cTGH['gia'], 0, ',', '.') . ' VNĐ'; ?></td>
                        <!-- <td><?php echo number_format($cTGH['so_luong'] * $cTGH['gia']) ?> VNĐ</td> -->

                        <td>
                            <a href="php/client/deleteProduct.php?idsp=<?php echo $sp['id'] ?>&idgh=<?php echo $gioHang['id'] ?>"
                                style=" background-color: #DC3640; color : white;">Xóa</a>
                        </td>
                    </tr>
                <?php
                } ?>
                <?php if ($tongTien != 0) {
                ?>
                    <tr>
                        <td colspan="">
                            <h3>Tổng tiền :</h3>
                        </td>
                        <td colspan="3" style="text-align: center;"><?php echo number_format($tongTien, 0, ',', '.') . 'VNĐ'; ?></td>

                        <td><a href="trangDatHang.php?id=<?php echo $gioHang['id'] ?>" class="pay">Thanh toán</a></td>


                    </tr>
                <?php
                } else {
                ?>
                    <tr>
                        <td colspan="5">
                            <h3 class="thong-bao">Bạn chưa thêm sản phẩm nào vào giỏ hàng</h3>
                        </td>
                    </tr>
                <?php
                } ?>



            </table>
        </div>

    <?php
    } 
    ?>
    <!-- footer -->
    <?php
    require('layout/footer.php');
    ?>
    <!-- end footer -->
</body>

</html>