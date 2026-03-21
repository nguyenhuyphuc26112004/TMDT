<?php
// 1. Kiểm tra đăng nhập
require('php/checkSession.php');
checkSessionClient(); 
require('php/connectMysql.php'); // Đảm bảo file này có biến $con

$idNguoiDung = $_SESSION['idNguoiDung'];
$successMsg = "";
$errorMsg = "";

// 2. Xử lý cập nhật thông tin khi nhấn nút
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnUpdate'])) {
    $hoTen = trim($_POST['hoVaTen']);
    $email = trim($_POST['email']);
    $gioiTinh = $_POST['gioiTinh'];

    // Kiểm tra rỗng cơ bản
    if (empty($hoTen) || empty($email)) {
        $errorMsg = "Họ tên và Email không được để trống!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Định dạng Email không hợp lệ!";
    } else {
        // Cập nhật vào DB (Sử dụng Prepared Statement để bảo mật)
        $sqlUp = "UPDATE nguoi_dung SET ho_ten = ?, email = ?, gioi_tinh = ? WHERE id = ?";
        $stmt = $con->prepare($sqlUp);
        $stmt->bind_param("sssi", $hoTen, $email, $gioiTinh, $idNguoiDung);
        
        if ($stmt->execute()) {
            $successMsg = "Cập nhật thông tin thành công!";
            // Cập nhật lại session nếu cần hiển thị tên mới trên Header
            $_SESSION['tenNguoiDung'] = $hoTen; 
        } else {
            $errorMsg = "Lỗi hệ thống, không thể cập nhật.";
        }
        $stmt->close();
    }
}

// 3. Lấy dữ liệu mới nhất từ Database để hiển thị lên Form
$sqlGet = "SELECT * FROM nguoi_dung WHERE id = ?";
$stmtGet = $con->prepare($sqlGet);
$stmtGet->bind_param("i", $idNguoiDung);
$stmtGet->execute();
$user = $stmtGet->get_result()->fetch_assoc();
$stmtGet->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ của tôi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f4; margin: 0; }
        .profile-wrapper { max-width: 500px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .profile-wrapper h2 { text-align: center; color: #28a745; margin-bottom: 25px; }
        
        .form-group { margin-bottom: 18px; position: relative; }
        .form-group label { display: block; font-size: 14px; color: #666; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px 10px 10px 35px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 15px; }
        .form-group i { position: absolute; left: 10px; top: 33px; color: #28a745; }
        
        .form-group input:disabled { background-color: #f9f9f9; color: #999; cursor: not-allowed; }

        .gender-group { display: flex; gap: 20px; margin: 10px 0; }
        .gender-option { display: flex; align-items: center; cursor: pointer; }
        .gender-option input { width: auto; margin-right: 8px; }

        .btn-update { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-update:hover { background: #218838; }
        
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <?php require('layout/header.php'); ?>

    <div class="profile-wrapper">
        <h2><i class="fa-solid fa-user-gear"></i> Hồ sơ cá nhân</h2>

        <?php if($successMsg) echo "<div class='alert alert-success'>$successMsg</div>"; ?>
        <?php if($errorMsg) echo "<div class='alert alert-error'>$errorMsg</div>"; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <i class="fa-solid fa-user"></i>
                <input type="text" value="<?= htmlspecialchars($user['ten_dang_nhap']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Họ và tên</label>
                <i class="fa-solid fa-address-card"></i>
                <input type="text" name="hoVaTen" value="<?= htmlspecialchars($user['ho_ten']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <label style="font-size: 14px; color: #666;">Giới tính:</label>
            <div class="gender-group">
                <label class="gender-option">
                    <input type="radio" name="gioiTinh" value="nam" <?= ($user['gioi_tinh'] == 'nam') ? 'checked' : '' ?>> Nam
                </label>
                <label class="gender-option">
                    <input type="radio" name="gioiTinh" value="nu" <?= ($user['gioi_tinh'] == 'nu') ? 'checked' : '' ?>> Nữ
                </label>
            </div>

            <button type="submit" name="btnUpdate" class="btn-update">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="doiMatKhau.php" style="color: #666; font-size: 13px; text-decoration: none;">
                <i class="fa-solid fa-key"></i> Bạn muốn đổi mật khẩu?
            </a>
        </p>
    </div>

    <?php require('layout/footer.php'); ?>
</body>
</html>