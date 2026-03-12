<?php
session_start();
require('php/checkSession.php');
checkSessionClient();

if (isset($_POST['doimatkhau'])) {
    $tenDangNhap = $_POST['tenDangNhap'];
    $matKhauHT = $_POST['mat_khau'];
    $matKhauMoi = $_POST['matKhaumoi'];
    $nhapLaiMK = $_POST['nhapLaiMK'];

    // 1. Kiểm tra mật khẩu mới và nhập lại có khớp nhau không
    if ($matKhauMoi !== $nhapLaiMK) {
        header('Location: doiMatKhau.php?loi=khong-khop');
        exit;
    }

    require('php/client/getObjectByCondition.php');
    $nguoiDung = getUserByUserName($con, $tenDangNhap);

    if (!empty($nguoiDung)) {
        // 2. So sánh trực tiếp (Không dùng hàm băm)
        if ($nguoiDung['mat_khau'] === $matKhauHT) {
            
            $sql = "UPDATE nguoi_dung SET mat_khau = ? WHERE ten_dang_nhap = ?";
            $stmt = $con->prepare($sql);
            
            // Lưu trực tiếp mật khẩu mới vào DB
            $stmt->bind_param("ss", $matKhauMoi, $tenDangNhap);

            if ($stmt->execute()) {
                session_unset();
                session_destroy();
                header('Location: dangNhap.php?doi-mat-khau-ok');
                exit;
            }
        }
    }
    // Chuyển trang nếu sai mật khẩu cũ hoặc sai tên đăng nhập
    header('Location: doiMatKhau.php?loi=sai-thong-tin');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
    <style>
        /* CSS đồng bộ với form cũ của bạn */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .box { display: flex; justify-content: center; align-items: center; min-height: 80vh; }
        #formDoiMatKhau { background: #fff; padding: 30px; border-radius: 8px; width: 400px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        .dau_vao { margin-bottom: 15px; }
        .dau_vao p { margin: 0 0 5px 0; font-size: 14px; color: #555; font-weight: bold; }
        .dau_vao input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .error { color: red; font-size: 12px; display: none; margin-top: 5px; }
        button { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        button:hover { background-color: #218838; }
        .msg-error { color: red; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php
        require('layout/header.php');
    ?>

<div class="box">
    <form id="formDoiMatKhau" action="doiMatKhau.php" method="post">
        <h2>Đổi mật khẩu</h2>

        <div class="msg-error">
            <?php 
                if(isset($_GET['loi'])) {
                    if($_GET['loi'] == 'khong-khop') echo "Mật khẩu nhập lại không khớp!";
                    else echo "Tên đăng nhập hoặc mật khẩu cũ không đúng!";
                }
            ?>
        </div>

        <div class="dau_vao">
            <p>Tên đăng nhập</p>
            <input type="text" id="tenDangNhap" name="tenDangNhap" placeholder="Nhập tên đăng nhập">
            <span class="error" id="tenDangNhapError">Tên đăng nhập không được để trống</span>
        </div>

        <div class="dau_vao">
            <p>Mật khẩu hiện tại</p>
            <input type="password" id="matKhauHienTai" name="mat_khau" placeholder="Mật khẩu cũ">
            <span class="error" id="matKhauHienTaiError">Mật khẩu hiện tại không được để trống</span>
        </div>

        <div class="dau_vao">
            <p>Mật khẩu mới</p>
            <input type="password" id="matKhauMoi" name="matKhaumoi" placeholder="Mật khẩu mới">
            <span class="error" id="matKhauMoiError">Mật khẩu mới không được để trống</span>
        </div>

        <div class="dau_vao">
            <p>Nhập lại mật khẩu mới</p>
            <input type="password" id="nhapLaiMK" name="nhapLaiMK" placeholder="Xác nhận lại mật khẩu mới">
            <span class="error" id="nhapLaiMKError">Vui lòng xác nhận lại mật khẩu mới</span>
        </div>

        <button type="submit" name="doimatkhau">Đổi mật khẩu</button>
    </form>
</div>
<?php
        require('layout/footer.php');
    ?>
<script>
// Logic validation cơ bản
document.getElementById("formDoiMatKhau").addEventListener("submit", function(e) {
    let isValid = true;
    const inputs = this.querySelectorAll("input");
    
    inputs.forEach(input => {
        const errorSpan = document.getElementById(input.id + "Error");
        if (input.value.trim() === "") {
            errorSpan.style.display = "block";
            isValid = false;
        } else {
            errorSpan.style.display = "none";
        }
    });

    const mkMoi = document.getElementById("matKhauMoi").value;
    const nhapLai = document.getElementById("nhapLaiMK").value;
    if (mkMoi !== nhapLai && nhapLai !== "") {
        const errorConfirm = document.getElementById("nhapLaiMKError");
        errorConfirm.textContent = "Mật khẩu xác nhận không khớp!";
        errorConfirm.style.display = "block";
        isValid = false;
    }

    if (!isValid) e.preventDefault();
});
</script>

</body>
</html>