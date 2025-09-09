<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/dangKy.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Đăng ký</title>

    <link rel="stylesheet" href="">
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
    <?php
    require('php/client/saveObject.php');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy tên đăng nhập từ form
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
                $idVaiTro = '1'; // 1 : User 
                $nhapLaiMatKhau = $_POST['nhapLaiMatKhau'];

                //  gọi hàm của file saveObject.php
                // hàm saveUser() thực hiện chức năng lưu 1 user xuống database
                $ketQua = saveUser($con, $idVaiTro,  $hoVaTen, $gioiTinh, $soDienThoai, $tenDangNhap, $matKhau);
                // Sau khi xóa xong, chuyển hướng trở lại trang quản lý khách hàng
                header('Location: dangNhap.php');
                exit; // không thực hiện các câu lệnh phía sau
            }
        }
    ?>
    <div class="box">
        <h2>Đăng ký</h2>
        <form id="formDangKy" action="dangKy.php" method="post">
            <div class="dau_vao">
                <label for="hoVaTen">Họ và tên</label>
                <input type="text" id="hoVaTen" name="hoVaTen" placeholder="Nhập họ và tên" style="opacity: 0.6;">
            </div>
            <span class="error " id="hoVaTenError"> Họ và tên không được để trống</span>
            
            <div class="dau_vao">
                <label for="soDienThoai">Số điện thoại</label>
                <input type="text" id="soDienThoai" name="soDienThoai" placeholder="Nhập số điện thoại">
            </div>
            <span class="error" id="soDienThoaiError"> Số điện thoại không được để trống</span>
            
            <div class="gioi_tinh">
                <p>Giới tính</p>
                <input type="radio" name="gioiTinh" value="nam">Nam
                <input type="radio" name="gioiTinh" value="nu">Nữ
            </div>
            <div class="dau_vao">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input type="text" id="tenDangNhap" name="tenDangNhap" placeholder="Nhập tên đăng nhập">
            </div>

            <span class="error" id="tenDangNhapError"> Tên đăng nhập không được để trống</span>
            <div class="dau_vao">
                <label for="matKhau">Mật khẩu</label>
                <input type="password" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu">
                <div class="eye" onclick="togglePassword('matKhau', this)">
                    <i class="fa-solid fa-eye-slash"></i>
                </div>
            </div>
            <span class="error" id="matKhauError"> Mật Khẩu không được để trống</span>
            
            <div class="dau_vao">
                <label for="nhapLaiMatKhau">Nhập lại mật khẩu</label>
                <input type="password" id="nhapLaiMatKhau" name="nhapLaiMatKhau" placeholder="Nhập lại mật khẩu">
                <div class="eye" onclick="togglePassword('nhapLaiMatKhau', this)">
                    <i class="fa-solid fa-eye-slash"></i>
                </div>
            </div>
            <span class="error" id="nhapLaiMatKhauError">Nhập lại mật Khẩu sai </span>

            <div class="btn-dangKy">
                <button type="submit">Đăng ký</button>
            </div>
        </form>
    </div>
    <script src="./js/dangKy.js"></script>
</body>

</html>