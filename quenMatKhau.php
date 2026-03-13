<?php
session_start();
// Đã đổi đường dẫn để khớp với thư mục dự án của Phúc
require('php/connectMysql.php'); 

date_default_timezone_set('Asia/Ho_Chi_Minh');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Đảm bảo các file PHPMailer nằm đúng thư mục gốc hoặc theo cấu trúc của bạn
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$error = $success = "";

// Hủy phiên làm việc nếu người dùng muốn quay lại
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    unset($_SESSION['reset_email']);
    header("Location: dangNhap.php"); // Quay về trang đăng nhập
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnGuiMa'])) {
    // Đã đổi sang $con
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Kiểm tra email có tồn tại trong hệ thống không
    $sql = "SELECT id, ho_ten FROM nguoi_dung WHERE email = '$email'";
    $res = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $tenNguoiDung = $row['ho_ten'];
        
        $otp = rand(100000, 999999);
        $het_han = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        
        // Cập nhật OTP vào DB - Đã đổi sang $con
        $update_sql = "UPDATE nguoi_dung SET ma_otp = '$otp', het_han_otp = '$het_han' WHERE email = '$email'";
        
        if (mysqli_query($con, $update_sql)) {
            $mail = new PHPMailer(true);
            try {
                // Cấu hình Server
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'phuc40965@gmail.com';
                $mail->Password   = 'snyoshuogsuhmhwv'; // Mật khẩu ứng dụng của Phúc
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                // Người gửi & Người nhận
                $mail->setFrom('phuc40965@gmail.com', 'Hệ thống TMDT');
                $mail->addAddress($email, $tenNguoiDung);

                // Nội dung Email
                $mail->isHTML(true);
                $mail->Subject = 'Mã OTP xác nhận quên mật khẩu';
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd;'>
                        <h2 style='color: #007bff;'>Xác nhận đổi mật khẩu</h2>
                        <p>Chào <b>$tenNguoiDung</b>,</p>
                        <p>Bạn vừa yêu cầu lấy lại mật khẩu. Mã OTP của bạn là:</p>
                        <div style='font-size: 24px; font-weight: bold; color: #dc3545; padding: 10px; background: #f8f9fa; display: inline-block;'>$otp</div>
                        <p>Mã này có hiệu lực trong <b>10 phút</b>. Vui lòng không chia sẻ mã này cho bất kỳ ai.</p>
                        <hr>
                        <p style='font-size: 12px; color: #888;'>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
                    </div>";

                $mail->send();
                $_SESSION['reset_email'] = $email;
                header("Location: xac-nhan-otp.php");
                exit();
            } catch (Exception $e) {
                $error = "Không thể gửi mail. Lỗi: {$mail->ErrorInfo}";
            }
        } else {
            $error = "Lỗi kết nối máy chủ, vui lòng thử lại.";
        }
    } else {
        $error = "Email này chưa được đăng ký trong hệ thống!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu | TMDT</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error-msg { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; border: 1px solid #f5c6cb; }
        .back-link { display: block; margin-top: 15px; text-decoration: none; color: #6c757d; font-size: 14px; text-align: center; }
        .back-link:hover { color: #007bff; }
    </style>
</head>
<body>
    <div class="login-box">
        <form method="POST">
            <h2 style="text-align: center;">Quên mật khẩu</h2>
            <p style="font-size: 14px; color: #666; margin-bottom: 20px; text-align: center;">Nhập email của bạn để nhận mã xác thực OTP.</p>
            
            <?php if($error): ?>
                <div class="error-msg"><?= $error ?></div>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Địa chỉ Email" required 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;"
                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            
            <button type="submit" name="btnGuiMa" style="width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Gửi mã xác nhận</button>
            
            <a href="dangNhap.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Quay lại đăng nhập</a>
        </form>
    </div>
</body>
</html>