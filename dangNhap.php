<?php
session_start();

// Đảm bảo các file require đúng đường dẫn và biến kết nối là $con
require('php/connectMysql.php'); 
require('php/checkLogin.php'); 
require('php/client/getObjectByCondition.php');

date_default_timezone_set('Asia/Ho_Chi_Minh');

$tenDangNhap = $matKhau = "";
$errorUsername = $errorPassword = $errorLogin = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Bảo mật dữ liệu nhập vào
    $tenDangNhap = mysqli_real_escape_string($con, trim($_POST['tenDangNhap']));
    $matKhau = trim($_POST['matKhau']);

    // Kiểm tra rỗng
    if ($tenDangNhap === "") $errorUsername = "Tên đăng nhập không được để trống";
    if ($matKhau === "") $errorPassword = "Mật khẩu không được để trống";

    if (empty($errorUsername) && empty($errorPassword)) {
        
        // Bước 1: Tìm User trong Database để kiểm tra trạng thái khóa
        $sql_check = "SELECT id, so_lan_sai, thoi_gian_khoa FROM nguoi_dung WHERE ten_dang_nhap = '$tenDangNhap'";
        $res_check = mysqli_query($con, $sql_check);
        $user_data = mysqli_fetch_assoc($res_check);

        $bay_gio = date("Y-m-d H:i:s");

        // Bước 2: Kiểm tra xem tài khoản có đang bị khóa không
        if ($user_data && !empty($user_data['thoi_gian_khoa']) && $user_data['thoi_gian_khoa'] > $bay_gio) {
            $giay_con_lai = strtotime($user_data['thoi_gian_khoa']) - strtotime($bay_gio);
            $errorLogin = "Tài khoản bị khóa. Thử lại sau " . ceil($giay_con_lai) . " giây.";
        } else {
            // Bước 3: Gọi hàm checkLogin (Hàm này phải trả về mảng dữ liệu)
            $nguoiDung = checkLogin($con, $tenDangNhap, $matKhau);

            if ($nguoiDung) {
                // ĐĂNG NHẬP ĐÚNG: Reset số lần sai và thời gian khóa
                $userId = $nguoiDung['id'];
                mysqli_query($con, "UPDATE nguoi_dung SET so_lan_sai = 0, thoi_gian_khoa = NULL WHERE id = $userId");

                // Lưu thông tin vào Session
                $_SESSION["tenDangNhap"] = $nguoiDung['ten_dang_nhap'];
                $_SESSION["idNguoiDung"] = $nguoiDung['id'];
                $_SESSION["vaiTro"] = $nguoiDung['id_vai_tro'];
                
                // Chuyển hướng theo vai trò (Admin vào admin, User vào trang chủ)
                
                    header('Location: trangChu.php');
                
                exit;
            } else {
                // ĐĂNG NHẬP SAI: Xử lý tăng số lần sai
                if ($user_data) {
                    $userId = $user_data['id']; 
                    $moi_so_lan_sai = (int)$user_data['so_lan_sai'] + 1;
                    
                    if ($moi_so_lan_sai >= 3) {
                        // Khóa 2 phút nếu sai quá 3 lần
                        $khoa_den = date("Y-m-d H:i:s", strtotime("+2 minutes"));
                        mysqli_query($con, "UPDATE nguoi_dung SET so_lan_sai = $moi_so_lan_sai, thoi_gian_khoa = '$khoa_den' WHERE id = $userId");
                        $errorLogin = "Bạn đã nhập sai 3 lần. Tài khoản bị khóa trong 2 phút!";
                    } else {
                        // Cập nhật số lần sai vào DB
                        mysqli_query($con, "UPDATE nguoi_dung SET so_lan_sai = $moi_so_lan_sai WHERE id = $userId");
                        $errorLogin = "Sai tài khoản hoặc mật khẩu! (Lần sai: $moi_so_lan_sai/3)";
                    }
                } else {
                    $errorLogin = "Tên đăng nhập không tồn tại!";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - TMDT</title>
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
                <div style="color: #dc3545; text-align: center; margin-bottom: 15px; font-size: 14px; background: #f8d7da; padding: 10px; border-radius: 5px;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= $errorLogin ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST"> 
                <div class="input-box <?= ($errorUsername) ? 'has-error' : '' ?>">
                    <input type="text" name="tenDangNhap"
                           value="<?= htmlspecialchars($tenDangNhap) ?>"
                           placeholder="Tên đăng nhập">
                    <i class="fa-solid fa-user"></i>
                    <div class="error">
                        <span><?= $errorUsername ?></span>
                    </div>
                </div>

                <div class="input-box <?= ($errorPassword) ? 'has-error' : '' ?>">
                    <input type="password" name="matKhau" placeholder="Mật khẩu">
                    <i class="fa-solid fa-lock"></i>
                    <div class="error">
                        <span><?= $errorPassword ?></span>
                    </div>
                </div>

                <a href="quenMatKhau.php" style="font-size: 13px; text-decoration: none; margin-bottom: 10px; display: block; color: #007bff;">Quên mật khẩu?</a>
                <button type="submit">Đăng nhập</button>

                <div class="divider">
                    <span>Hoặc kết nối với</span>
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
        }
    });

    if (!isValid) e.preventDefault();
});
</script>
</body>
</html>