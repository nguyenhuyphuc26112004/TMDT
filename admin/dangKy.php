<?php
// ktra người dùng đăng nhập hay chưa
require('../php/checkSession.php');
checkSession(2); // Chỉ Admin (Role 2) mới được vào
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Người Dùng - Admin</title>
    <link rel="stylesheet" href="css/dangKy.css">
    <style>
        .error { color: red; display: none; font-size: 12px; }
        .red { color: red; }
        .dau_vao select { width: 100%; padding: 8px; border-radius: 4px; }
    </style>
</head>

<body>

    <?php
    require('../php/admin/saveObject.php');

    $error = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tenDangNhap = trim($_POST['tenDangNhap']);
        $email = trim($_POST['email']);

        // Kiểm tra tên đăng nhập hoặc email đã tồn tại chưa
        $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ? OR email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $tenDangNhap, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Tên đăng nhập hoặc Email đã tồn tại trong hệ thống.";
        } else {
            $hoVaTen = $_POST['hoVaTen'];
            $gioiTinh = $_POST['gioiTinh'];
            $matKhau = $_POST['matKhau'];
            $vaiTro = $_POST['vai_tro'];

            // Gọi hàm lưu dữ liệu
            $ketQua = saveUserAtAdmin($con, $vaiTro, $hoVaTen, $gioiTinh, $email, $tenDangNhap, $matKhau);
            
            if ($ketQua === true) {
                header('Location: quanLyKH.php');
                exit;
            } else {
                $error = "Lỗi lưu dữ liệu: " . $ketQua;
            }
        }
    }
    ?>

    <div class="box">
        <h2>Thêm Người Dùng Mới</h2>
        <form id="formDangKyAdmin" action="dangKy.php" method="post">
            
            <div class="dau_vao">
                <label for="hoVaTen">Họ và tên</label>
                <input type="text" id="hoVaTen" name="hoVaTen" placeholder="Nhập họ và tên" required>
            </div>
            <span class="error" id="hoVaTenError">Họ và tên không được để trống</span>

            <div class="dau_vao">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" required>
            </div>
            <span class="error" id="emailError">Email không được để trống</span>

            <div class="gioi_tinh">
                <p>Giới tính</p>
                <input type="radio" name="gioiTinh" value="Nam" checked> Nam
                <input type="radio" name="gioiTinh" value="Nữ"> Nữ
            </div>

            <div class="dau_vao">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input type="text" id="tenDangNhap" name="tenDangNhap" placeholder="Nhập tên đăng nhập" required>
            </div>
            <span class="red"><?php echo $error; ?></span>
            <span class="error" id="tenDangNhapError">Tên đăng nhập không được để trống</span>

            <div class="dau_vao">
                <label for="vai-tro">Vai trò hệ thống</label><br>
                <select id="vai-tro" name="vai_tro" required>
                    <option value="1">USER (Khách hàng)</option>
                    <option value="2">ADMIN (Quản trị viên)</option>
                </select>
            </div>

            <div class="dau_vao">
                <label for="matKhau">Mật Khẩu</label>
                <input type="password" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu" required>
            </div>
            <span class="error" id="matKhauError">Mật khẩu không được để trống</span>

            <div class="dau_vao">
                <label for="nhapLaiMatKhau">Nhập lại Mật Khẩu</label>
                <input type="password" id="nhapLaiMatKhau" name="nhapLaiMatKhau" placeholder="Xác nhận lại mật khẩu" required>
            </div>
            <span class="error" id="nhapLaiMatKhauError">Mật khẩu xác nhận không khớp</span>

            <div class="btn-dangKy">
                <button type="submit">Thêm Thành Viên</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('formDangKyAdmin').addEventListener('submit', function(e) {
            let isValid = true;
            const pass = document.getElementById('matKhau').value;
            const confirmPass = document.getElementById('nhapLaiMatKhau').value;
            const errorConfirm = document.getElementById('nhapLaiMatKhauError');

            if (pass !== confirmPass) {
                errorConfirm.style.display = 'block';
                isValid = false;
            } else {
                errorConfirm.style.display = 'none';
            }

            if (!isValid) e.preventDefault();
        });
    </script>
</body>
</html>