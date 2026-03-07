<?php
require('php/client/saveObject.php'); 

$hoVaTen = $phone = $username = $password = $confirm = $gender = "";
$errorFullname = $errorPhone = $errorUsername = $errorPassword = $errorConfirm = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Lấy dữ liệu từ POST
    $hoVaTen  = trim($_POST['hoVaTen'] ?? '');
    $phone    = trim($_POST['soDienThoai'] ?? '');
    $username = trim($_POST['tenDangNhap'] ?? '');
    $password = trim($_POST['matKhau'] ?? '');
    $confirm  = trim($_POST['nhapLaiMatKhau'] ?? '');
    $gender   = $_POST['gioiTinh'] ?? 'nam';
    
    $idVaiTro = '1'; // Mặc định Role User

    // 1. Kiểm tra rỗng
    if ($hoVaTen === "")  $errorFullname = "Họ và tên không được để trống";
    if ($phone === "")    $errorPhone = "Số điện thoại không được để trống";
    if ($username === "") $errorUsername = "Tên đăng nhập không được để trống";
    if ($password === "") $errorPassword = "Mật khẩu không được để trống";

    // 2. Kiểm tra mật khẩu khớp
    if ($password !== "" && $confirm !== "" && $password !== $confirm) {
        $errorConfirm = "Mật khẩu nhập lại không khớp";
    }

    // 3. Kiểm tra trùng tên đăng nhập
    if ($errorUsername === "") {
        $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errorUsername = "Tên đăng nhập đã tồn tại";
        }
        $stmt->close();
    }

    // 4. Nếu không có lỗi thì gọi hàm saveUser
    if (empty($errorUsername) && empty($errorPassword) && empty($errorConfirm) && empty($errorPhone) && empty($errorFullname)) {
        
        // Gọi hàm saveUser với đầy đủ tham số
        $ketQua = saveUser($con, $idVaiTro, $hoVaTen, $gender, $phone, $username, $password);

        if ($ketQua) {
            header('Location: dangNhap.php');
            exit;
        } else {
            $errorUsername = "Có lỗi xảy ra khi lưu dữ liệu.";
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
        .register { height: auto; min-height: 580px;}
        
        .gender-box {
            display: flex;
            align-items: center;
            gap: 20px;
            margin: 5px 0 15px 10px;
            color: #555;
            font-size: 14px;
        }
        .gender-option { display: flex; align-items: center; cursor: pointer; }
        .gender-option input { width: auto !important; margin-right: 5px; cursor: pointer; }
        
        /* Hiển thị lỗi đỏ nếu có class has-error */
        .input-box.has-error input { border: 1px solid red; }
        .input-box.has-error .error { display: block; color: red; font-size: 12px; }
    </style>
</head>
<body>

<div class="page">
    <div class="register">
        <div class="left-register">
            <h2 style="margin-bottom: 20px;">Đăng ký</h2>
            <form action="" method="POST">
                
                <div class="input-box <?= $errorFullname ? 'has-error' : '' ?>">
                    <input type="text" name="hoVaTen" placeholder="Họ và tên" value="<?= htmlspecialchars($hoVaTen) ?>">
                    <i class="fa-solid fa-address-card"></i>
                    <div class="error"><span><?= $errorFullname ?></span></div>
                </div>

                <div class="input-box <?= $errorPhone ? 'has-error' : '' ?>">
                    <input type="tel" name="soDienThoai" placeholder="Số điện thoại" value="<?= htmlspecialchars($phone) ?>">
                    <i class="fa-solid fa-phone"></i>
                    <div class="error"><span><?= $errorPhone ?></span></div>
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
const form = document.querySelector("form");
form.addEventListener("submit", function (e) {
    let isValid = true;
    const inputs = form.querySelectorAll("input[type='text'], input[type='password'], input[type='tel']");
    
    inputs.forEach(input => {
        const box = input.closest(".input-box");
        const span = box.querySelector(".error span");

        if (input.value.trim() === "") {
            box.classList.add("has-error");
            span.textContent = "Trường này không được để trống";
            isValid = false;
        } else {
            box.classList.remove("has-error");
        }
    });

    // Kiểm tra khớp mật khẩu ở phía client
    const pass = form.querySelector('input[name="matKhau"]').value;
    const confirm = form.querySelector('input[name="nhapLaiMatKhau"]').value;
    if (pass !== confirm && confirm !== "") {
        const confirmBox = form.querySelector('input[name="nhapLaiMatKhau"]').closest(".input-box");
        confirmBox.classList.add("has-error");
        confirmBox.querySelector(".error span").textContent = "Mật khẩu nhập lại không khớp";
        isValid = false;
    }

    if (!isValid) e.preventDefault();
});
</script>

</body>
</html>