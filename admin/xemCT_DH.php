<?php
// ktra người dùng đăng nhập hay chưa
require('../php/checkSession.php');
checkSession(2);

require('../php/admin/getObjectById.php');

$idDonHang = $_GET['id'] ?? null;
if (!$idDonHang) {
    header('Location: quanLyDH.php');
    exit();
}

$donHang = getObjectById($con, 'don_hang', $idDonHang);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        /* CSS GỐC CHO SIDEBAR - ĐỒNG BỘ TOÀN HỆ THỐNG */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; }
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

        /* TỐI ƯU PHẦN HIỂN THỊ CHI TIẾT (VIEW) */
        .view {
            flex: 1;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .view-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            width: 100%;
            max-width: 800px;
            height: fit-content;
        }
        .title {
            border-bottom: 2px solid #e67e22; /* Màu nhấn riêng cho đơn hàng */
            margin-bottom: 25px;
            padding-bottom: 10px;
        }
        .title h2 { margin: 0; color: #333; font-size: 23px; }

        .main table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }
        .main th {
            background-color: #f8f9fa;
            color: #555;
            text-align: left;
            width: 35%;
        }
        .main td, .main th {
            border: 1px solid #ececec;
            padding: 15px;
        }
        .main tr:hover {
            background-color: #fcfcfc;
        }
        
        .header-table {
            background-color: #e67e22 !important;
            color: white !important;
            text-align: center !important;
            font-size: 18px;
        }

        .submit-zone {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
        .btn-back {
            text-decoration: none;
            border-radius: 5px;
            padding: 10px 30px;
            font-size: 17px;
            font-weight: bold;
            background-color: #1C8552;
            color: white;
            transition: 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back:hover {
            background-color: #14633d;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Badge trạng thái */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            background-color: #eee;
            color: #333;
        }
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

        <div class="view">
            <div class="view-container">
                <div class="title">
                    <h2>Chi tiết đơn hàng</h2>
                </div>
                <div class="main">
                    <table>
                        <tr>
                            <th colspan="2" class="header-table">
                                Thông tin vận chuyển - Đơn hàng #<?php echo $donHang['id']; ?>
                            </th>
                        </tr>
                        <tr>
                            <th><i class="fa-solid fa-hashtag"></i> ID đơn hàng</th>
                            <td><strong><?php echo $donHang['id']; ?></strong></td>
                        </tr>
                        <tr>
                            <th><i class="fa-solid fa-user"></i> Tên người nhận</th>
                            <td><?php echo htmlspecialchars($donHang['ten']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fa-solid fa-phone"></i> Số điện thoại</th>
                            <td><?php echo htmlspecialchars($donHang['sdt']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fa-solid fa-location-dot"></i> Địa chỉ giao hàng</th>
                            <td><?php echo htmlspecialchars($donHang['dia_chi']); ?></td>
                        </tr>
                        <tr>
                            <th><i class="fa-solid fa-money-bill-wave"></i> Thành tiền</th>
                            <td><strong style="color: #d35400; font-size: 18px;"><?php echo number_format($donHang['tong_tien'], 0, ',', '.'); ?> VNĐ</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fa-solid fa-truck-ramp-box"></i> Trạng thái đơn hàng</th>
                            <td>
                                <span class="status-badge">
                                    <?php echo htmlspecialchars($donHang['trang_thai']); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="fa-solid fa-credit-card"></i> Phương thức thanh toán</th>
                            <td>Thanh toán khi nhận hàng (COD)</td>
                        </tr>
                    </table>

                    <div class="submit-zone">
                        <a href="quanLyDH.php" class="btn-back">
                            <i class="fa-solid fa-arrow-left"></i> Trở lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>