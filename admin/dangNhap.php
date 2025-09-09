<?php
session_start();
// Nếu đã đăng nhập và là admin, chuyển về trang admin
if (isset($_SESSION['tenDangNhap']) && $_SESSION["vaiTro"] == 2) {
    header('Location: index.php');
    exit;
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
    <?php
    require('../php/checkLogin.php');
    // ktra biến có giá trị hay không
    if (
        $_SERVER['REQUEST_METHOD'] == 'POST'
        && !empty($_POST['tenDangNhap'])
        && !empty($_POST['matKhau'])
    ) {
        $tenDangNhap = $_POST['tenDangNhap'];
        $matKhau = $_POST['matKhau'];

        // gọi hàm checkLoginAdmin của checkLogin.php
        if (checkLoginAdmin($con, $tenDangNhap, $matKhau)) {
            // người dùng đăng nhập thành công -> bắt đầu 1 phiên làm việc


            /*
                    Logic đăng nhập
                - lúc người dùng đăng nhâp thành công -> đưa tenDangNhap và id lên session
                - đưa id lên để thuận tiện lấy id của người dùng khi thao tác với giỏ hàng
            */
            session_start();
            $_SESSION["tenDangNhap"] = "$tenDangNhap";

            $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ? ";

            // chuẩn bị câu lệnhlệnh
            $stmt = $con->prepare($sql);


            // gán các tham số vào câu lệnh
            $stmt->bind_param("s", $tenDangNhap);

            // Thực thi câu lệnh SQL
            $stmt->execute();

            // Lấy kết quả
            $result = $stmt->get_result();

            // lấy 1 dòng đầu tiên trong mảng kq ( có thể null )
            $nguoiDung =  $result->fetch_assoc();

            $idNguoiDung = $nguoiDung['id'];
            $_SESSION["idNguoiDung"] = "$idNguoiDung";

            $_SESSION["vaiTro"] = 2;
            header('Location: quanLySP.php');
            exit; // không thực hiện các câu lệnh phía sau
        } else {
            header('Location: dangNhap.php?loi');
            exit;
        }
    }

    ?>
    <!-- end header -->
    <div class="box">
        <form id="formDangNhap" action="dangNhap.php" method="post">
            <h2>Đăng Nhập</h2>
            <p style="color: red"><?php echo isset($_GET['loi']) ? "Đăng nhập thất bại" : " "; ?></p>
            <p style="color: green"><?php echo isset($_GET['dang-xuat']) ? "Đăng xuất thành công " : " "; ?></p>

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

    <script src="js/validDangNhap.js">

    </script>
    <!-- footer -->

    <!-- end footer -->
</body>

</html>