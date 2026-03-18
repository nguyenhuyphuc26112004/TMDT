<?php
session_start();
require('php/connectMysql.php'); 

date_default_timezone_set('Asia/Ho_Chi_Minh');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$error = $success = "";

if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    unset($_SESSION['reset_email']);
    header("Location: dangNhap.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['btnGuiMa'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sql = "SELECT id, ho_ten FROM nguoi_dung WHERE email = '$email'";
    $res = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $tenNguoiDung = $row['ho_ten'];
        $otp = rand(100000, 999999);
        $het_han = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        
        $update_sql = "UPDATE nguoi_dung SET ma_otp = '$otp', het_han_otp = '$het_han' WHERE email = '$email'";
        
        if (mysqli_query($con, $update_sql)) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'phuc40965@gmail.com';
                $mail->Password   = 'snyoshuogsuhmhwv'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;
                $mail->CharSet    = 'UTF-8';

                $mail->setFrom('phuc40965@gmail.com', 'Hệ thống TMDT');
                $mail->addAddress($email, $tenNguoiDung);

                $mail->isHTML(true);
                $mail->Subject = 'Mã OTP xác nhận quên mật khẩu';
                $mail->Body    = "
                    <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px; max-width: 500px;'>
                        <h2 style='color: #007bff; text-align: center;'>Xác nhận đổi mật khẩu</h2>
                        <p>Chào <b>$tenNguoiDung</b>,</p>
                        <p>Bạn vừa yêu cầu lấy lại mật khẩu. Mã OTP của bạn là:</p>
                        <div style='font-size: 30px; font-weight: bold; color: #dc3545; padding: 15px; background: #f8f9fa; text-align: center; border-radius: 5px; letter-spacing: 5px;'>$otp</div>
                        <p>Mã này có hiệu lực trong <b>10 phút</b>. Vui lòng không chia sẻ mã này cho bất kỳ ai.</p>
                        <hr style='border: 0; border-top: 1px solid #eee;'>
                        <p style='font-size: 12px; color: #888; text-align: center;'>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
                    </div>";

                $mail->send();
                $_SESSION['reset_email'] = $email;
                header("Location: xac-nhan-otp.php");
                exit();
            } catch (Exception $e) {
                $error = "Không thể gửi mail. Lỗi hệ thống.";
            }
        } else {
            $error = "Lỗi kết nối máy chủ, vui lòng thử lại.";
        }
    } else {
        $error = "Email này không tồn tại trong hệ thống!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu | TMDT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: #e7f3ff;
            color: #007bff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            font-size: 35px;
        }
        h2 { color: #333; margin-bottom: 10px; font-size: 24px; }
        p.desc { color: #666; font-size: 14px; margin-bottom: 25px; line-height: 1.5; }
        
        .error-msg {
            color: #dc3545;
            background: #f8d7da;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #f5c6cb;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s;
            font-size: 15px;
        }
        input[type="email"]:focus { border-color: #007bff; box-shadow: 0 0 8px rgba(0,123,255,0.1); }

        button {
            width: 100%;
            padding: 13px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background: #0056b3; }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #888;
            font-size: 14px;
            transition: color 0.3s;
        }
        .back-link:hover { color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-circle">
            <i class="fa-solid fa-lock-open"></i>
        </div>
        <form method="POST">
            <h2>Quên mật khẩu?</h2>
            <p class="desc">Đừng lo lắng! Hãy nhập email đã đăng ký, chúng tôi sẽ gửi mã OTP để bạn đặt lại mật khẩu.</p>
            
            <?php if($error): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" placeholder="Nhập email của bạn" required 
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            
            <button type="submit" name="btnGuiMa">Gửi mã xác nhận</button>
            
            <a href="dangNhap.php" class="back-link">
                <i class="fa-solid fa-arrow-left-long"></i> Quay lại đăng nhập
            </a>
        </form>
    </div>
</body>
</html>