<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="./css/dangNhap.css">
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
    require('layout/header.php');
    require('php/checkLogin.php');
    require('php/client/getObjectByCondition.php');
    // ktra thong tin nguoi dung submit len
    if (
        $_SERVER['REQUEST_METHOD'] == 'POST' &&
        !empty($_POST['tenDangNhap']) &&
        !empty($_POST['matKhau'])
    ) {
        $tenDangNhap = $_POST['tenDangNhap'];
        $matKhau = $_POST['matKhau'];
        // ktra
        if (checkLogin($con, $tenDangNhap, $matKhau)) {
            /*
                    Logic đăng nhập
                - lúc người dùng đăng nhâp thành công -> đưa tenDangNhap và id lên session
                - đưa id lên để thuận tiện lấy id của người dùng khi thao tác với giỏ hàng
                    -> lấy id thông qua tenDangNhap của người dùng
            */
            // id người dùng 
            $nguoiDung = getUserByUserName($con, $tenDangNhap);
            if ($nguoiDung != null) {
                $idNguoiDung = $nguoiDung['id'];
            }
            // người dùng đăng nhập thành công -> bắt đầu 1 phiên làm việc
            $_SESSION["tenDangNhap"] = "$tenDangNhap";
            $_SESSION["idNguoiDung"] = "$idNguoiDung";
            header('Location:trangChu.php');
            exit; // không thực hiện các câu lệnh phía sau
        } else {
            header('Location: dangNhap.php?loi');
        }
    }
    ?>
    <div class="box">
        <form id="formDangNhap" action="dangNhap.php" method="post">
            <h2>Đăng Nhập</h2>
            <div class="dau_vao">
                <label for="tenDangNhap">Tên đăng nhập</label>
                <input type="text" id="tenDangNhap" placeholder="Nhập tên đăng nhập của bạn" name="tenDangNhap">
            </div>
            <span class="error" id="tenDangNhapError">Tên đăng nhập không được để trống</span>

            <div class="dau_vao">
                <label for="matKhau">Mật khẩu</label>
                <input type="password" id="matKhau" placeholder="Nhập mật khẩu của bạn" name="matKhau">
            </div>
            <span class="error" id="matKhauError">Mật khẩu không được để trống</span>
            <div class="btn">
                <button type = "submid">Đăng nhập</button>
            </div>
            <!-- <button type="submit"><a href="">Đăng Nhập</a></button> -->
            <div class="register">
                <p>
                    Bạn chưa có tài khoản?
                    <a href="dangKy.php">Đăng Kí</a>
                </p>
            </div>
        </form>
    </div>
    <script src="./js/dangNhap.js"></script>
</body>

</html>