<?php
// 1. Kiểm tra session và quyền đăng nhập
require('../php/checkSession.php');
checkSession(2);

// 2. Import các file xử lý dữ liệu
require('../php/admin/getObjectById.php');
require('../php/admin/updateObjectById.php');

// 3. Lấy ID đơn hàng từ URL
$idDonHang = $_GET['id'] ?? null;
if (!$idDonHang) {
    header('Location: quanLyDH.php');
    exit();
}

// 4. Lấy dữ liệu đơn hàng hiện tại từ database
$donHang = getObjectById($con, 'don_hang', $idDonHang);

// 5. Xử lý khi Admin nhấn nút Submit Form (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
    $idCheck = $_POST['idCheck'];
    $diaChi = $_POST['diaChi'];
    $trangThai = $_POST['trangThai'];
    $trangThaiThanhToan = $_POST['trangThaiThanhToan'];
    
    // LOGIC TỰ ĐỘNG: Nếu đơn hàng là 'Đã nhận hàng', ép trạng thái thanh toán thành 'Đã thanh toán'
    // (Logic này cũng đã được mình thêm vào hàm updateOrderById ở file xử lý SQL)
    if ($trangThai === 'Đã nhận hàng') {
        $trangThaiThanhToan = 'Đã thanh toán';
    }

    // Thực hiện cập nhật vào database
    if (updateOrderById($con, $idCheck, $diaChi, $trangThai, $trangThaiThanhToan)) {
        header('Location: quanLyDH.php?success=1');
        exit();
    } else {
        echo "<script>alert('Lỗi khi cập nhật dữ liệu!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật đơn hàng #<?php echo $idDonHang ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        /* GIỮ NGUYÊN CSS GỐC CỦA BẠN */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; position: sticky; top: 0; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; object-fit: cover; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 20px; margin: 15px 0; color: #333; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; text-align: center; margin-right: 10px; }

        .update-wrapper { flex: 1; padding: 40px; display: flex; justify-content: center; }
        .update-container { background: #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 30px; width: 100%; max-width: 800px; height: fit-content; }
        .title { border-bottom: 2px solid #e67e22; margin-bottom: 25px; padding-bottom: 10px; }
        .title h2 { margin: 0; color: #333; font-size: 22px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .full-row { grid-column: span 2; }
        .infor label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        .infor input, .infor select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; box-sizing: border-box; outline: none; transition: 0.3s; }
        .infor input:focus, .infor select:focus { border-color: #e67e22; box-shadow: 0 0 5px rgba(230,126,34,0.2); }
        .info-box { background: #f0f7ff; border-left: 4px solid #007bff; padding: 15px; border-radius: 4px; margin: 20px 0; display: flex; align-items: center; gap: 10px; }
        .submit-zone { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .btn { padding: 12px 25px; border-radius: 5px; border: none; font-size: 16px; font-weight: bold; text-decoration: none; cursor: pointer; transition: 0.3s; }
        .btn-cancel { background-color: #6c757d; color: white; }
        .btn-save { background-color: #FBBE00; color: black; }
        .btn-save:hover { background-color: #e5ad00; }
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

        <div class="update-wrapper">
            <div class="update-container">
                <div class="title">
                    <h2><i class="fa-solid fa-pen-to-square"></i> Cập nhật đơn hàng #<?php echo $idDonHang ?></h2>
                </div>
                
                <form action="capNhat_DH.php?id=<?php echo $idDonHang ?>" method="post">
                    <input type="hidden" name="idCheck" value="<?php echo $idDonHang ?>">
                    
                    <div class="form-grid">
                        <div class="infor full-row">
                            <label for="address">Địa chỉ giao hàng</label>
                            <input type="text" id="address" name="diaChi" value="<?php echo htmlspecialchars($donHang['dia_chi']) ?>" required>
                        </div>

                        <div class="infor">
                            <label for="mode">Trạng thái vận chuyển</label>
                            <select id="mode" name="trangThai">
                                <option value="Đang chờ duyệt" <?php echo ($donHang['trang_thai'] == 'Đang chờ duyệt') ? 'selected' : '' ?>>Đang chờ duyệt</option>
                                <option value="Đang vận chuyển" <?php echo ($donHang['trang_thai'] == 'Đang vận chuyển') ? 'selected' : '' ?>>Đang vận chuyển</option>
                                <option value="Đã nhận hàng" <?php echo ($donHang['trang_thai'] == 'Đã nhận hàng') ? 'selected' : '' ?>>Đã nhận hàng</option>
                                <option value="Đã hủy" <?php echo ($donHang['trang_thai'] == 'Đã hủy') ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>

                        <div class="infor">
                            <label for="payment_status">Trạng thái thanh toán</label>
                            <select id="payment_status" name="trangThaiThanhToan">
                                <option value="Chưa thanh toán" <?php echo ($donHang['trang_thai_thanh_toan'] == 'Chưa thanh toán') ? 'selected' : '' ?>>Chưa thanh toán</option>
                                <option value="Chờ xác nhận tiền" <?php echo ($donHang['trang_thai_thanh_toan'] == 'Chờ xác nhận tiền') ? 'selected' : '' ?>>Chờ xác nhận tiền</option>
                                <option value="Đã thanh toán" <?php echo ($donHang['trang_thai_thanh_toan'] == 'Đã thanh toán') ? 'selected' : '' ?>>Đã thanh toán ✅</option>
                            </select>
                        </div>
                    </div>

                    <?php if(!empty($donHang['ma_chuyen_khoan'])): ?>
                    <div class="info-box">
                        <i class="fa-solid fa-receipt" style="color: #007bff;"></i>
                        <span><strong>Mã giao dịch khách gửi:</strong> <?php echo htmlspecialchars($donHang['ma_chuyen_khoan']) ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="submit-zone">
                        <a href="quanLyDH.php" class="btn btn-cancel">Hủy bỏ</a>
                        <button type="submit" class="btn btn-save">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const mode = document.getElementById('mode');
        const payment = document.getElementById('payment_status');

        mode.addEventListener('change', function() {
            if (this.value === 'Đã nhận hàng') {
                payment.value = 'Đã thanh toán';
            }
        });
    </script>
</body>
</html>