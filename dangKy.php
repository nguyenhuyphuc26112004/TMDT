<?php
session_start();
require('php/client/saveObject.php'); // Chứa hàm saveUser

$hoVaTen = $email = $username = $password = $confirm = $gender = "";
$errorFullname = $errorEmail = $errorUsername = $errorPassword = $errorConfirm = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Lấy và làm sạch dữ liệu
    $hoVaTen  = trim($_POST['hoVaTen'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $username = trim($_POST['tenDangNhap'] ?? '');
    $password = $_POST['matKhau'] ?? ''; 
    $confirm  = $_POST['nhapLaiMatKhau'] ?? '';
    $gender   = $_POST['gioiTinh'] ?? 'nam';
    
    $idVaiTro = 1; // Mặc định Role khách hàng

    // 2. Kiểm tra các trường rỗng
    if ($hoVaTen === "")  $errorFullname = "Họ và tên không được để trống";
    
    if ($email === "") {
        $errorEmail = "Email không được để trống";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorEmail = "Định dạng email không hợp lệ";
    }

    if ($username === "") $errorUsername = "Tên đăng nhập không được để trống";
    if ($password === "") $errorPassword = "Mật khẩu không được để trống";

    // 3. Kiểm tra mật khẩu khớp
    if ($password !== "" && $password !== $confirm) {
        $errorConfirm = "Mật khẩu nhập lại không khớp";
    }

    // 4. Kiểm tra trùng Tên đăng nhập hoặc Email trong Database
    if (empty($errorUsername) && empty($errorEmail)) {
        $sqlCheck = "SELECT id FROM nguoi_dung WHERE ten_dang_nhap = ? OR email = ?";
        $stmtCheck = $con->prepare($sqlCheck);
        $stmtCheck->bind_param("ss", $username, $email);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        if ($resCheck->num_rows > 0) {
            $errorUsername = "Tên đăng nhập hoặc Email đã tồn tại";
        }
        $stmtCheck->close();
    }

    // 5. Lưu dữ liệu nếu không có lỗi
    if (empty($errorFullname) && empty($errorEmail) && empty($errorUsername) && empty($errorPassword) && empty($errorConfirm)) {
        
        // Gọi hàm saveUser (lưu mật khẩu trực tiếp, không băm)
        $ketQua = saveUser($con, $idVaiTro, $hoVaTen, $gender, $email, $username, $password);

        if ($ketQua) {
            echo "<script>alert('Đăng ký tài khoản thành công!'); window.location='dangNhap.php';</script>";
            exit;
        } else {
            $errorUsername = "Lỗi hệ thống: Không thể lưu dữ liệu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="css/dangKy.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .register { height: auto; min-height: 550px; }
        .gender-box { display: flex; align-items: center; gap: 20px; margin: 5px 0 15px 10px; color: #555; font-size: 14px; }
        .gender-option { display: flex; align-items: center; cursor: pointer; }
        .gender-option input { width: auto !important; margin-right: 5px; cursor: pointer; }
        .input-box.has-error input { border: 1px solid red; }
        .input-box.has-error .error { display: block; color: red; font-size: 12px; margin-top: 10px; }
        .error span {color: red; }
    </style>
</head>
<body>

<div class="page">
    <div class="register">
        <div class="left-register">
            <h2 style="margin-bottom: 20px;">Đăng ký</h2>
            <form action="" method="POST" id="registerForm">
                
                <div class="input-box <?= $errorFullname ? 'has-error' : '' ?>">
                    <input type="text" name="hoVaTen" placeholder="Họ và tên" value="<?= htmlspecialchars($hoVaTen) ?>">
                    <i class="fa-solid fa-address-card"></i>
                    <div class="error"><span><?= $errorFullname ?></span></div>
                </div>

                <div class="input-box <?= $errorEmail ? 'has-error' : '' ?>">
                    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
                    <i class="fa-solid fa-envelope"></i>
                    <div class="error"><span><?= $errorEmail ?></span></div>
                </div>

                <div class="input-box <?= $errorUsername ? 'has-error' : '' ?>">
                    <input type="text" name="tenDangNhap" placeholder="Tên đăng nhập" value="<?= htmlspecialchars($username) ?>">
                    <i class="fa-solid fa-user"></i>
                    <div class="error"><span><?= $errorUsername ?></span></div>
                </div>

                <div class="input-box <?= $errorPassword ? 'has-error' : '' ?>">
                    <input type="password" name="matKhau" placeholder="Mật khẩu">
                    <i class="fa-solid fa-lock"></i>
                    <div class="error"><span><?= $errorPassword ?></span></div>
                </div>

                <div class="input-box <?= $errorConfirm ? 'has-error' : '' ?>">
                    <input type="password" name="nhapLaiMatKhau" placeholder="Nhập lại mật khẩu">
                    <i class="fa-solid fa-shield-halved"></i>
                    <div class="error"><span><?= $errorConfirm ?></span></div>
                </div>

                <div class="gender-box">
                    <span>Giới tính:</span>
                    <label class="gender-option">
                        <input type="radio" name="gioiTinh" value="nam" <?= ($gender == 'nam' || $gender == '') ? 'checked' : '' ?>> Nam
                    </label>
                    <label class="gender-option">
                        <input type="radio" name="gioiTinh" value="nu" <?= ($gender == 'nu') ? 'checked' : '' ?>> Nữ
                    </label>
                </div>
                
                <button type="submit" style="margin-top: 10px;">Đăng ký</button>
            </form>
        </div>
        <div class="right-register">
            <h2>Welcome!</h2>
            <p>Đã có tài khoản?</p>
            <button onclick="location.href='dangNhap.php'">Đăng nhập ngay</button>
        </div>
    </div>
</div>

<script>
const form = document.getElementById("registerForm");

form.addEventListener("submit", function (e) {
    let isValid = true;
    
    // 1. Kiểm tra các ô nhập liệu chung
    const inputs = form.querySelectorAll("input[type='text'], input[type='password'], input[type='email']");
    
    inputs.forEach(input => {
        const box = input.closest(".input-box");
        const span = box.querySelector(".error span");

        if (input.value.trim() === "") {
            box.classList.add("has-error");
            span.textContent = "Trường này không được để trống";
            isValid = false;
        } else {
            box.classList.remove("has-error");
            span.textContent = "";
        }
    });

    // 2. Kiểm tra định dạng Email bằng JS
    const emailInput = form.querySelector('input[name="email"]');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailInput.value.trim() !== "" && !emailPattern.test(emailInput.value)) {
        const emailBox = emailInput.closest(".input-box");
        emailBox.classList.add("has-error");
        emailBox.querySelector(".error span").textContent = "Email không đúng định dạng (ví dụ: abc@gmail.com)";
        isValid = false;
    }

    // 3. Kiểm tra khớp mật khẩu
    const pass = form.querySelector('input[name="matKhau"]').value;
    const confirm = form.querySelector('input[name="nhapLaiMatKhau"]').value;
    
    if (pass !== confirm && confirm !== "") {
        const confirmBox = form.querySelector('input[name="nhapLaiMatKhau"]').closest(".input-box");
        confirmBox.classList.add("has-error");
        confirmBox.querySelector(".error span").textContent = "Mật khẩu nhập lại không khớp";
        isValid = false;
    }

    // Ngăn chặn submit nếu có lỗi
    if (!isValid) {
        e.preventDefault();
    }
});
</script>

</body>
</html>