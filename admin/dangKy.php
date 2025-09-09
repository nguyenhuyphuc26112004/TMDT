<?php
// ktra người dùng đăng nhập hay chưa
require('../php/checkSession.php');
checkSession(2);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - admin</title>

    <link rel="stylesheet" href="css/dangKy.css">
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

    <!-- header -->
    <?php
    require('layout/header.php');
    require('../php/admin/saveObject.php');
    // Kiểm tra nếu người dùng nhấn nút "Dăng ký"
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tenDangNhap = $_POST['tenDangNhap'];

        // Kiểm tra tên đăng nhập đã tồn tại trong cơ sở dữ liệu chưa
        $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $tenDangNhap);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Tên đăng nhập đã tồn tại
            $error = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
        } else {
            $hoVaTen = $_POST['hoVaTen'];  // thực hiện hứng dữ liệu bằng cách gán chúng vào 1 biến khác
            $soDienThoai = $_POST['soDienThoai'];
            $gioiTinh = $_POST['gioiTinh'];
            $tenDangNhap = $_POST['tenDangNhap'];
            $matKhau = $_POST['matKhau'];
            $vaiTro = $_POST['vai_tro'];

            // $idVaiTro = '1'; // 1 : User 
            $nhapLaiMatKhau = $_POST['nhapLaiMatKhau'];


            //  gọi hàm của file saveObject.php
            // hàm saveUserAtAdmin() thực hiện chức năng lưu 1 user xuống database
            $ketQua = saveUserAtAdmin($con, $vaiTro, $hoVaTen, $gioiTinh, $soDienThoai, $tenDangNhap, $matKhau);
            // Sau khi xóa xong, chuyển hướng trở lại trang quản lý khách hàng
            header('Location: quanLyKH.php');
            exit; // không thực hiện các câu lệnh phía sau 
        }
    }
    ?>
    <!-- end header -->
    <div class="box">
        <h2>Đăng ký</h2>
        <form id="formDangKyAdmin" action="dangKy.php" method="post">
            <div class="dau_vao">
                <label for="hoVaTen">Họ và tên</label>
                <input type="text" id="hoVaTen" name="hoVaTen" placeholder="Nhập họ và tên" style="opacity: 0.6;">
            </div>
            <span class="error " id="hoVaTenError">Họ và tên không được để trống</span>

            <div class="dau_vao">
                <label for="soDienThoai">Số điện thoại</label>
                <input type="text" id="soDienThoai" name="soDienThoai" placeholder="Nhập số điện thoại">
            </div>
            <span class="error" id="soDienThoaiError"> Số điện thoại không được để trống</span>

            <div class="gioi_tinh">
                <p>Giới tính</p>
                <input type="radio" name="gioiTinh" value="Nam">Nam
                <input type="radio" name="gioiTinh" value="Nữ">Nữ
            </div>

            <div class="dau_vao">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input type="text" id="tenDangNhap" name="tenDangNhap" placeholder="Nhập tên đăng nhập">
            </div>
            <!-- Hiển thị thông báo lỗi nếu tên đăng nhập đã tồn tại -->

            <span class="red"><?php echo empty($error) ? ' ' : $error ?></span>

            <span class="error" id="tenDangNhapError"> Tên đăng nhập không được để trống</span>

            <div class="dau_vao">
                <label for="vai-tro"> Vai trò</label><br>
                <select id="vai-tro" name="vai_tro" style="opacity: 0.6;" required>
                    <option value="1">USER</option>
                    <option value="2">ADMIN</option>
                </select>
            </div>

            <div class="dau_vao">
                <label for="matKhau">Mật Khẩu</label>
                <input type="password" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu">
            </div>
            <span class="error" id="matKhauError"> Mật Khẩu không được để trống</span>

            <div class="dau_vao">
                <label for="nhapLaiMatKhau">Nhập lại Mật Khẩu</label>
                <input type="password" id="nhapLaiMatKhau" name="nhapLaiMatKhau" placeholder="Nhập lại mật khẩu">
            </div>
            <span class="error" id="nhapLaiMatKhauError">Nhập lại mật Khẩu sai </span>



            <div class="btn-dangKy">
                <button type="submit">Đăng ký</button>
            </div>
        </form>
    </div>
    <script src="js/validDangKy.js"></script>
</body>

</html>