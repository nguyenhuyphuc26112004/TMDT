<?php
session_start();
// Chỉnh lại đường dẫn nếu file nằm trong thư mục khác
require('php/connectMysql.php'); 
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!isset($_SESSION['reset_email'])) {
    header("Location: quen-mat-khau.php");
    exit();
}

$error = "";
$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp_input = trim($_POST['otp']); 
    $new_password = $_POST['new_password']; 
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Mật khẩu nhập lại không khớp!";
    } else {
        // Sử dụng biến $con thay vì $conn
        $sql = "SELECT ma_otp, het_han_otp FROM nguoi_dung WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        $user = mysqli_fetch_assoc($result);
        $bay_gio = date("Y-m-d H:i:s");

        if ($user) {
            if ($user['ma_otp'] !== $otp_input) {
                $error = "Mã xác nhận không chính xác!";
            } elseif ($user['het_han_otp'] < $bay_gio) {
                $error = "Mã xác nhận đã hết hạn!";
            } else {
                // THÀNH CÔNG -> Cập nhật mật khẩu trực tiếp (KHÔNG DÙNG HASH)
                // Đã chuyển thành $con
                $update = "UPDATE nguoi_dung SET mat_khau = '$new_password', ma_otp = NULL, het_han_otp = NULL WHERE email = '$email'";
                
                if (mysqli_query($con, $update)) {
                    unset($_SESSION['reset_email']);
                    echo "<script>alert('Đổi mật khẩu thành công!'); window.location='dangNhap.php';</script>";
                    exit();
                } else {
                    $error = "Lỗi hệ thống khi cập nhật mật khẩu.";
                }
            }
        } else {
            $error = "Không tìm thấy dữ liệu yêu cầu.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP | TMDT</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .login-box { max-width: 400px; margin: 80px auto; padding: 25px; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .login-box h2 { text-align: center; margin-bottom: 20px; }
        .login-box p { text-align: center; font-size: 14px; color: #666; }
        .login-box input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .login-box button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .login-box button:hover { background: #218838; }
        .error-p { color: red; text-align: center; font-size: 14px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <form method="POST">
            <h2>Đặt lại mật khẩu</h2>
            <p>Mã được gửi tới: <b><?= htmlspecialchars($email) ?></b></p>
            
            <input type="text" name="otp" placeholder="Nhập OTP 6 số" required maxlength="6">
            <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
            <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
            
            <button type="submit">Xác nhận đổi mật khẩu</button>
            
            <?php if($error): ?>
                <p class="error-p"><?= $error ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>