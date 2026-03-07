<?php
session_start();

require('php/checkLogin.php'); // Giả sử file này tạo biến kết nối $con
require('php/client/getObjectByCondition.php');

$tenDangNhap = $matKhau = "";
$errorUsername = $errorPassword = $errorLogin = "";

// Kiểm tra nếu có tham số 'loi' trên URL (từ lần redirect trước)
if (isset($_GET['loi'])) {
    $errorLogin = "Tên đăng nhập hoặc mật khẩu không chính xác!";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenDangNhap = trim($_POST['tenDangNhap']);
    $matKhau = trim($_POST['matKhau']);

    if ($tenDangNhap === "") $errorUsername = "Tên đăng nhập không được để trống";
    if ($matKhau === "") $errorPassword = "Mật khẩu không được để trống";

    if (empty($errorUsername) && empty($errorPassword)) {
        if (checkLogin($con, $tenDangNhap, $matKhau)) {
            $nguoiDung = getUserByUserName($con, $tenDangNhap);

            if ($nguoiDung != null) {
                $_SESSION["tenDangNhap"] = $tenDangNhap;
                $_SESSION["idNguoiDung"] = $nguoiDung['id'];
                $_SESSION["vaiTro"] = $nguoiDung['id_vai_tro'];
                
                header('Location: trangChu.php');
                exit;
            }
        } else {
            // Thay vì chuyển hướng, ta gán lỗi để hiển thị ngay
            $errorLogin = "Tên đăng nhập hoặc mật khẩu không chính xác!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/dangNhap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<div class="page">
    <div class="login">
        <div class="left-login">
            <h2>Hello, Welcome</h2>
            <p>Bạn chưa có tài khoản!</p>
            <button onclick="location.href='dangKy.php'">Đăng ký ngay</button>
        </div>

        <div class="right-login">
            <h2>Đăng nhập</h2>

            <?php if ($errorLogin): ?>
                <div style="color: #dc3545; text-align: center; margin-bottom: 10px; font-size: 14px;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= $errorLogin ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST"> <div class="input-box <?= ($errorUsername || $errorLogin) ? 'has-error' : '' ?>">
                    <input type="text" name="tenDangNhap"
                           value="<?= htmlspecialchars($tenDangNhap) ?>"
                           placeholder="Tên đăng nhập">
                    <i class="fa-solid fa-user"></i>
                    <div class="error">
                        <span><?= $errorUsername ?></span>
                    </div>
                </div>

                <div class="input-box <?= ($errorPassword || $errorLogin) ? 'has-error' : '' ?>">
                    <input type="password" name="matKhau" placeholder="Mật khẩu">
                    <i class="fa-solid fa-lock"></i>
                    <div class="error">
                        <span><?= $errorPassword ?></span>
                    </div>
                </div>

                <p style="cursor: pointer; font-size: 13px;">Forgot password?</p>
                <button type="submit">Đăng nhập</button>

                <div class="divider">
                    <span>Or Connect with</span>
                </div>
            </form>

            <div class="list-icon">
                <div class="icon">
                    <i class="fa-brands fa-facebook"></i>
                    <i class="fa-brands fa-google"></i>
                    <i class="fa-brands fa-instagram"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const form = document.querySelector("form");
const inputs = form.querySelectorAll("input");

form.addEventListener("submit", function (e) {
    let isValid = true;
    inputs.forEach(input => {
        const box = input.closest(".input-box");
        const span = box.querySelector(".error span");

        if (input.value.trim() === "") {
            box.classList.add("has-error");
            span.textContent = "Trường này không được để trống";
            isValid = false;
        } else {
            // Không xóa class has-error ngay lập tức để giữ thông báo lỗi từ PHP nếu có
        }
    });

    if (!isValid) {
        e.preventDefault();
    }
});
</script>
</body>
</html>