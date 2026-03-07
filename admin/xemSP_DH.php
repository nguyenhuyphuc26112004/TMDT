<?php
// ktra người dùng đăng nhập hay chưa
require('../php/checkSession.php');
checkSession(2);

require('../php/admin/orderDetail.php');
require('../php/admin/getObjectById.php');

$idDonHang = $_GET['id'] ?? null;
if (!$idDonHang) {
    header('Location: quanLyDH.php');
    exit();
}
$ctDonHang = getOrderDetailByOrder($con, $idDonHang);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        /* CSS GỐC CHO SIDEBAR - ĐỒNG BỘ TOÀN HỆ THỐNG */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; position: sticky; top: 0; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .tieude i { margin-right: 5px; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; object-fit: cover; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 20px; margin: 15px 0; color: #333; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; text-align: center; margin-right: 10px; }

        /* TỐI ƯU BẢNG CHI TIẾT SẢN PHẨM */
        .quanLyDH {
            flex: 1;
            padding: 40px;
        }
        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e67e22;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .title h2 { margin: 0; color: #333; }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }
        td, th {
            border: 1px solid #ececec;
            text-align: center;
            padding: 15px;
        }
        tr:hover { background-color: #fcfcfc; }

        /* Style cho ảnh sản phẩm */
        .product-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
            transition: 0.3s;
        }
        .product-img:hover { transform: scale(1.1); }

        .price { color: #d35400; font-weight: bold; }
        .quantity { font-weight: bold; color: #2c3e50; }

        .submit {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
        .btn-back {
            text-decoration: none;
            padding: 10px 30px;
            border-radius: 5px;
            font-weight: bold;
            background-color: #1C8552;
            color: white;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover { background-color: #14633d; box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
    </style>
</head>

<body>
    <div class="container">
        <div class="trangchu">
            <img src="./logo/logo.jpg" alt="Logo">
            <div class="tieude">
                <p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p>
            </div>
            <div class="list-tieude">
                <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
                <div class="danhmuc">
                    <a href="quanLySP.php"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</a>
                    <a href="quanLyKH.php"><i class="fa-solid fa-users"></i> Quản lý người dùng</a>
                    <a href="quanLyDH.php" style="background: #ccc; color: #000;"><i class="fa-solid fa-cart-shopping"></i> Quản lý đơn hàng</a>
                </div>
                <p><i class="fa-solid fa-user-gear"></i> Tài khoản</p>
                <div class="danhmuc">
                    <a href="../php/admin/logoutAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="quanLyDH">
            <div class="title">
                <h2>Danh sách sản phẩm - Đơn hàng #<?= htmlspecialchars($idDonHang) ?></h2>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th width="10%">Mã ĐH</th>
                        <th width="10%">Mã SP</th>
                        <th>Tên sản phẩm</th>
                        <th width="10%">Số lượng</th>
                        <th width="15%">Đơn giá</th>
                        <th width="20%">Ảnh minh họa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($ctDonHang as $cTDH) {
                        $sp = getObjectById($con, 'san_pham', $cTDH['id_san_pham']);
                    ?>
                        <tr>
                            <td>#<?= $cTDH['id_don_hang'] ?></td>
                            <td><?= $cTDH['id_san_pham'] ?></td>
                            <td style="text-align: left;"><strong><?= htmlspecialchars($sp['ten']) ?></strong></td>
                            <td class="quantity">x<?= $cTDH['so_luong'] ?></td>
                            <td class="price"><?= number_format($cTDH['gia'], 0, ',', '.') ?> đ</td>
                            <td>
                                <img src="img/<?= htmlspecialchars($sp['anh']) ?>" alt="<?= htmlspecialchars($sp['ten']) ?>" class="product-img">
                            </td>
                        </tr>
                    <?php
                    } 
                    ?>
                </tbody>
            </table>

            <div class="submit">
                <a href="quanLyDH.php" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Trở lại danh sách đơn hàng
                </a>
            </div>
        </div>
    </div>
</body>

</html>