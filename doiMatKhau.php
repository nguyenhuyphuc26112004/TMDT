<?php
// ktra người dùng đăng nhập hay chưa
require('php/checkSession.php');
checkSessionClient();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="css/dangNhap.css">
</head>
<style>
    .error {
        color: red;
        display: none;
    }
</style>

<body>
    <!-- header -->
    <?php
    require('layout/header.php'); 
    require('php/client/getObjectByCondition.php');
    if (isset($_POST['doimatkhau'])) { // Kiểm tra nếu form được submit với nút "doimatkhau"
        $tenDangNhap = $_POST['tenDangNhap']; // Lấy tên đăng nhập từ form
        $matKhau = $_POST['mat_khau'];       // Lấy mật khẩu hiện tại từ form
        $matKhauMoi = $_POST['matKhaumoi']; // Lấy mật khẩu mới từ form

        $nguoiDung = getUserByUserName($con, $tenDangNhap); // lấy ra người dùng có tenDangNhap trong database

        if (!empty($nguoiDung)) { // tồn tại người dùng với $tenDangNhap
            if ($nguoiDung['mat_khau'] == $matKhau) {
                // mật khẩu người dung cung cấp là đúng 
                $sql = "UPDATE nguoi_dung SET mat_khau = ?  Where ten_dang_nhap = ?";
                // chuẩn bị câu lệnh
                $stmt = $con->prepare($sql);
                // gán các tham số vào câu lệnh
                $stmt->bind_param("ss", $matKhauMoi, $tenDangNhap);

                // thực thi câu lệnh 
                if ($stmt->execute()) {
                    // chuyển trang và đăng xuất khi đổi mật khẩu thành công
                    session_unset(); // Xóa tất cả biến trong session
                    session_destroy(); // Hủy phiên làm việc
                    header('Location: dangNhap.php?doi-mat-khau-ok');
                    exit;
                }
            }
        }
        // chuyển trang khi có lỗi
        header('Location: doiMatKhau.php?loi-doi-mat-khau');
        exit;
    }
    ?>
    <!-- end header -->

    <div class="box">
        <form id="formDoiMatKhau" action="doiMatKhau.php" method="post">
            <h2>Đổi mật khẩu</h2>
            <p style="color : red">
                <?php echo isset($_GET['loi-doi-mat-khau']) ? "Lỗi đổi mật khẩu" : " " ?>
            </p>
            <div class="dau_vao">
                <p>Tên đăng nhập</p>
                <input type="text" id="tenDangNhap" placeholder="Nhập tên đăng nhập của bạn" name="tenDangNhap">
            </div>
            <span class="error" id="tenDangNhapError">Tên đăng nhập không được để trống</span>


            <div class="dau_vao">
                <p>Mật khẩu hiện tại</p>
                <input type="text" id="matKhauHienTai" placeholder="Nhập mật khẩu hiện tại" name="mat_khau">
            </div>
            <span class="error" id="matKhauHienTaiError">Mật khẩu không được để trống</span>


            <div class="dau_vao">
                <p>Mật khẩu mới</p>
                <input type="text" id="matKhauMoi" placeholder="Nhập mật khẩu mới" name="matKhaumoi">
            </div>
            <span class="error" id="matKhauMoiError">Mật khẩu không được để trống</span>


            <button type="submit" name="doimatkhau">Đổi mật khẩu</button>
        </form>
    </div>

    <script src="js/validDoiMK.js"></script>
    <!-- footer -->
    <?php require('layout/footer.php'); ?>
    <!-- end footer -->
</body>

</html>