<?php
session_start();
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
                // THÀNH CÔNG -> Cập nhật mật khẩu
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
            max-width: 420px;
            text-align: center;
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            background: #e8f5e9;
            color: #28a745;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            font-size: 30px;
        }
        h2 { color: #333; margin-bottom: 10px; font-size: 24px; }
        p.desc { color: #666; font-size: 14px; margin-bottom: 25px; line-height: 1.5; }
        b.email-display { color: #007bff; word-break: break-all; }

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

        .input-group { position: relative; margin-bottom: 15px; text-align: left; }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 38px;
            color: #aaa;
        }
        .input-group label {
            display: block;
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
            margin-left: 5px;
            font-weight: 600;
        }
        input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s;
            font-size: 15px;
        }
        input:focus { border-color: #28a745; box-shadow: 0 0 8px rgba(40,167,69,0.1); }

        /* Style đặc biệt cho OTP để trông nổi bật */
        input[name="otp"] {
            letter-spacing: 4px;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            padding-left: 15px; /* Bỏ padding-left của icon vì OTP căn giữa */
        }

        button {
            width: 100%;
            padding: 13px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        button:hover { background: #218838; }

        .footer-links {
            margin-top: 20px;
            font-size: 13px;
        }
        .footer-links a { text-decoration: none; color: #888; transition: color 0.3s; }
        .footer-links a:hover { color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-circle">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <form method="POST">
            <h2>Xác thực OTP</h2>
            <p class="desc">Chúng tôi đã gửi mã xác nhận đến địa chỉ email: <br><b class="email-display"><?= htmlspecialchars($email) ?></b></p>
            
            <?php if($error): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <label>Mã xác thực</label>
                <input type="text" name="otp" placeholder="••••••" required maxlength="6" autocomplete="off">
            </div>

            <div class="input-group">
                <label>Mật khẩu mới</label>
                <i class="fa-solid fa-key"></i>
                <input type="password" name="new_password" placeholder="Nhập mật khẩu mới" required>
            </div>

            <div class="input-group">
                <label>Xác nhận mật khẩu</label>
                <i class="fa-solid fa-check-double"></i>
                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
            </div>
            
            <button type="submit">Cập nhật mật khẩu</button>
            
            <div class="footer-links">
                <a href="quen-mat-khau.php?action=cancel">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Gửi lại mã khác
                </a>
            </div>
        </form>
    </div>
</body>
</html>