<?php
require('../php/checkSession.php');
checkSession(2);
require('../php/admin/getObjectById.php');

$idProduct = $_GET['id'] ?? 0;
$product = getObjectById($con, 'san_pham', $idProduct);

if (!$product) {
    header("Location: quanLySP.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* GIỮ NGUYÊN CSS SIDEBAR CỦA BẠN */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .tieude i { margin-right: 5px; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 20px; margin: 15px 0; color: #333; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; text-align: center; margin-right: 10px; }

        .view-content {
            flex: 1;
            padding: 40px;
        }

        .title-page {
            border-bottom: 2px solid #1C8552;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            overflow: hidden;
            max-width: 900px;
        }

        .product-img-box {
            flex: 1;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-right: 1px solid #eee;
        }

        .product-img-box img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            /* Reset lại margin từ file mặc định nếu bị ảnh hưởng */
            margin: 0 !important; 
            width: 350px !important;
            height: 350px !important;
            object-fit: cover;
        }

        .product-details {
            flex: 1.2;
            padding: 30px;
        }

        .product-details h2 {
            margin: 0 0 10px 0;
            color: #222;
            font-size: 28px;
        }

        .price {
            font-size: 24px;
            color: #e44d26;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info-group {
            margin-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 8px;
            display: flex;
        }

        .info-group label {
            width: 120px;
            font-weight: bold;
            color: #555;
        }

        .info-group span {
            color: #333;
        }

        .badge {
            background: #1C8552;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 14px;
        }

        .actions {
            margin-top: 30px;
        }

        .btn-back {
            text-decoration: none;
            background-color: #1C8552;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            font-size: 16px;
            transition: 0.3s;
            display: inline-block;
        }

        .btn-back:hover {
            background-color: #14633d;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="trangchu">
            <img src="./logo/logo.jpg" alt="Logo">
            <div class="tieude"><p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p></div>
            <div class="list-tieude">
                <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
                <div class="danhmuc">
                    <a href="quanLyKH.php"><i class="fa-solid fa-users"></i> Quản lý người dùng</a>
                    <a href="quanLySP.php"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</a>
                    <a href="quanLyDH.php"><i class="fa-solid fa-cart-shopping"></i> Quản lý đơn hàng</a>
                </div>
                <p><i class="fa-solid fa-user-gear"></i> Tài khoản</p>
                <div class="danhmuc">
                    <a href="../php/admin/logoutAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

    <div class="view-content">
        <div class="title-page">
            <h2 style="margin:0; font-size: 24px;">Thông tin chi tiết sản phẩm</h2>
        </div>

        <div class="product-card">
            <div class="product-img-box">
                <img src="img/<?= htmlspecialchars($product['anh']) ?>" alt="Sản phẩm">
            </div>

            <div class="product-details">
                <span class="badge"><?= htmlspecialchars($product['loai']) ?></span>
                <h2><?= htmlspecialchars($product['ten']) ?></h2>
                
                <p class="price"><?= number_format($product['gia'], 0, ',', '.') ?> VNĐ</p>

                <div class="info-group">
                    <label>ID sản phẩm:</label>
                    <span>#<?= $product['id'] ?></span>
                </div>

                <div class="info-group">
                    <label>Số lượng:</label>
                    <span><?= $product['so_luong'] ?></span>
                </div>

                <div class="info-group">
                    <label>Đơn vị tính:</label>
                    <span><?= htmlspecialchars($product['don_vi'] ?? 'Kg') ?></span>
                </div>

                <div class="info-group">
                    <label>Trạng thái:</label>
                    <span style="color: <?= $product['so_luong'] > 0 ? '#1C8552' : 'red' ?>; font-weight: bold;">
                        <?= $product['so_luong'] > 0 ? 'Còn hàng' : 'Hết hàng' ?>
                    </span>
                </div>

                <div class="actions">
                    <a href="quanLySP.php" class="btn-back">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>