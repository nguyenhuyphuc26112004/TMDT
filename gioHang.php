<?php
// Kiểm tra người dùng đăng nhập hay chưa
require('php/checkSession.php');
checkSessionClient();

// Khởi động session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <title>Giỏ hàng của bạn</title>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .quanLyDH {
            margin: 50px auto;
            max-width: 1100px;
            min-height: 80vh;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .quanLyDH table {
            font-family: 'Segoe UI', Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #eeeeee;
            text-align: left;
            padding: 15px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            text-transform: uppercase;
            font-size: 14px;
        }

        /* Hiệu ứng hover cho dòng trong bảng */
        .quanLyDH table tr:hover {
            background-color: #fcfcfc;
            transition: 0.2s;
        }

        .title-cart {
            margin-bottom: 20px;
            border-bottom: 2px solid #28c361;
            display: inline-block;
        }

        /* Nút Xóa */
        .btn-delete {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #DC3640;
            color: white;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-delete:hover {
            opacity: 0.8;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        /* Nút Thanh toán */
        .pay {
            text-decoration: none;
            padding: 12px 25px;
            background-color: #28c361;
            color: #fff;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .pay:hover {
            background-color: #21a350;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Thông báo trống */
        .empty-cart-container {
            text-align: center;
            padding: 100px 20px;
        }

        .gio-hang-thong-bao {
            color: #999;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .btn-back {
            display: inline-block;
            padding: 12px 25px;
            background-color: #151534;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #28c361;
        }

        .total-price {
            color: red;
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <?php
    require('layout/header.php');
    require('php/client/cart.php');
    require('php/client/getObjectById.php');

    $idNguoiDung = $_SESSION['idNguoiDung'];
    $gioHang = checkCart($con, $idNguoiDung);

    if ($gioHang == null) {
        echo '
        <div class="empty-cart-container">
            <i class="fa-solid fa-cart-shopping" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
            <h1 class="gio-hang-thong-bao">Giỏ hàng của bạn đang trống</h1>
            <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Quay lại cửa hàng</a>
        </div>';
    } else {
        $cTGioHang = getCartDetailByCart($con, $gioHang['id']);
    ?>
        <div class="quanLyDH">
            <div class="title-cart">
                <h1><i class="fa-solid fa-cart-shopping"></i> Giỏ hàng của bạn</h1>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Ảnh sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th style="text-align: center;">Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tongTien = 0;
                    if (!empty($cTGioHang)) {
                        foreach ($cTGioHang as $cTGH) {
                            $sp = getObjectById($con, 'san_pham', $cTGH['id_san_pham']);
                            $thanhTien = $cTGH['so_luong'] * $cTGH['gia'];
                            $tongTien += $thanhTien;
                    ?>
                            <tr>
                                <td>
                                    <img src="admin/img/<?php echo $sp['anh'] ?>" alt="<?php echo $sp['ten'] ?>" width="70px" height="70px" style="object-fit: cover; border-radius: 5px;">
                                </td>
                                <td>
                                    <a style="color: #151534; font-weight: 500; text-decoration: none;" href="xemChiTietSP.php?id=<?php echo $sp['id'] ?>">
                                        <?php echo $sp['ten'] ?>
                                    </a>
                                </td>
                                <td style="text-align: center; font-weight: bold;">
                                    <?php echo $cTGH['so_luong'] ?>
                                </td>
                                <td style="color: #333;">
                                    <?php echo number_format($thanhTien, 0, ',', '.') . ' VNĐ'; ?>
                                </td>
                                <td>
                                    <a href="php/client/deleteProduct.php?idsp=<?php echo $sp['id'] ?>&idgh=<?php echo $gioHang['id'] ?>" 
                                       class="btn-delete" >
                                        <i class="fa-solid fa-trash-can"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <?php if ($tongTien > 0) { ?>
                        <tr>
                            <td colspan="2" style="text-align: right;">
                                <h3>Tổng đơn hàng:</h3>
                            </td>
                            <td colspan="2" style="text-align: center;">
                                <span class="total-price"><?php echo number_format($tongTien, 0, ',', '.') . ' VNĐ'; ?></span>
                            </td>
                            <td>
                                <a href="trangDatHang.php?id=<?php echo $gioHang['id'] ?>" class="pay">
                                    Thanh toán <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px;">
                                <p style="color: red; font-size: 18px; font-weight: bold;">Giỏ hàng trống!</p>
                                <a href="trangChu.php" class="btn-back">Mua sắm ngay</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tfoot>
            </table>
        </div>
    <?php
    }
    require('layout/footer.php');
    ?>
</body>

</html>