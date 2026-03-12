<?php
session_start();
require('../php/checkLogin.php'); 

// 1. Nếu đã đăng nhập và là admin, chuyển thẳng về trang quản lý
if (isset($_SESSION['tenDangNhap']) && 
    isset($_SESSION["vaiTro"]) && 
    $_SESSION["vaiTro"] == 2) {
    header('Location: quanLySP.php');
    exit;
}

// 2. Xử lý khi người dùng nhấn nút Đăng Nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenDangNhap = $_POST['tenDangNhap'] ?? '';
    $matKhau = $_POST['matKhau'] ?? '';

    // Kiểm tra không để trống dữ liệu trước khi xử lý
    if (!empty($tenDangNhap) && !empty($matKhau)) {
        
        // Gọi hàm checkLoginAdmin từ file checkLogin.php
        $nguoiDung = checkLoginAdmin($con, $tenDangNhap, $matKhau);
            
            if ($nguoiDung) {
                $_SESSION["tenDangNhap"] = $tenDangNhap;
                $_SESSION["idNguoiDung"] = $nguoiDung['id'];
                $_SESSION["vaiTro"] = 2; // Gán vai trò Admin
                
                header('Location: quanLySP.php');
                exit;
            }
            
        } else {
            // Sai mật khẩu hoặc không phải admin
            header('Location: dangNhap.php?loi');
            exit;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - admin</title>
    <link rel="stylesheet" href="css/dangNhap.css">
    <style>
        .error {
            color: red;
            display: none;
        }

        .red {
            color: red;
        }
    </style>
</head>

<body>
    <div class="box">
        <form id="formDangNhap" action="dangNhap.php" method="post">
            <h2>Đăng Nhập</h2>
            <p style="color: red"><?php echo isset($_GET['loi']) ? "Đăng nhập thất bại" : ""; ?></p>
            <p style="color: green"><?php echo isset($_GET['dang-xuat']) ? "Đăng xuất thành công " : ""; ?></p>

            <div class="dau_vao">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input type="text" id="tenDangNhap" placeholder="Nhập tên đăng nhập của bạn" name="tenDangNhap">
            </div>
            <span class="error" id="tenDangNhapError">Tên đăng nhập không được để trống</span>

            <div class="dau_vao">
                <label for="matKhau">Mật Khẩu</label>
                <input type="password" id="matKhau" placeholder="Nhập mật khẩu của bạn" name="matKhau">
            </div>
            <span class="error" id="matKhauError">Mật khẩu không được để trống</span>

            <button type="submit">Đăng Nhập</button>
        </form>
    </div>

    <script src="js/validDangNhap.js"></script>
</body>

</html>