<?php
// ktra người dùng đăng nhập hay chưa
require('../php/checkSession.php');
checkSession(2);

require('../php/admin/getObjectById.php');
require('../php/admin/updateObjectById.php');

$idCurrent = $_GET['id'] ?? null; 
if (!$idCurrent) {
    header('Location: quanLyKH.php');
    exit();
}

// gọi hàm của getObjectById.php
$User = getObjectById($con, 'nguoi_dung', $idCurrent);

// Kiểm tra nếu người dùng nhấn nút "Cập nhật"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
    $id = $_POST['idCheck'];
    $hoVaTen = $_POST['ho_ten'];
    $soDienThoai = $_POST['so_dien_thoai'];
    $gioiTinh = $_POST['gioi_tinh'];
    $idVaiTro = $_POST['vai_tro'];

    // gọi hàm của updateObjectById.php
    updateUserById($con, $id, $hoVaTen, $idVaiTro, $soDienThoai, $gioiTinh);

    header('Location: quanLyKH.php');
    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        /* CSS GỐC CHO SIDEBAR - ĐỒNG BỘ VỚI HỆ THỐNG */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .tieude i { margin-right: 5px; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; object-fit: cover; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 20px; margin: 15px 0; color: #333; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; text-align: center; margin-right: 10px; }

        /* TỐI ƯU GIAO DIỆN CẬP NHẬT */
        .update {
            flex: 1;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            width: 100%;
            max-width: 750px;
        }
        .title {
            border-bottom: 2px solid #FBBE00; /* Màu nhấn riêng cho khách hàng */
            margin-bottom: 25px;
            padding-bottom: 10px;
        }
        .title h2 { margin: 0; color: #333; font-size: 22px; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .full-row { grid-column: span 2; }

        .field-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 5px;
        }
        .field-group label {
            font-weight: bold;
            margin-bottom: 8px;
            color: #555;
            font-size: 15px;
        }
        .field-group input, .field-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }
        .field-group input:focus, .field-group select:focus {
            border-color: #FBBE00;
            box-shadow: 0 0 5px rgba(251,190,0,0.2);
        }

        .error { color: #e74c3c; font-size: 13px; margin-top: 5px; display: none; font-style: italic; }

        .submit-zone {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
        .btn {
            padding: 12px 25px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: 0.3s;
        }
        .btn-back { background-color: #1C8552; color: white; }
        .btn-back:hover { background-color: #14633d; }
        .btn-submit { background-color: #FBBE00; color: black; }
        .btn-submit:hover { background-color: #e5ad00; }
    </style>
</head>

<body>
    <div class="container">
        <div class="trangchu">
            <img src="./logo/logo.jpg" alt="Logo">
            <div class="tieude">
                <p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p>
            </div>
            <div class="list-tieude">
                <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
                <div class="danhmuc">
                    <a href="quanLySP.php"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</a>
                    <a href="quanLyKH.php" style="background: #ccc; color: #000;"><i class="fa-solid fa-users"></i> Quản lý người dùng</a>
                    <a href="quanLyDH.php"><i class="fa-solid fa-cart-shopping"></i> Quản lý đơn hàng</a>
                </div>
                <p><i class="fa-solid fa-user-gear"></i> Tài khoản</p>
                <div class="danhmuc">
                    <a href="../php/admin/logoutAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="update">
            <div class="form-container">
                <div class="title">
                    <h2>Cập nhật thông tin khách hàng (ID: #<?= $idCurrent ?>)</h2>
                </div>

                <form id="formCapNhatKH" action="capNhat_KH.php?id=<?= $idCurrent ?>" method="post">
                    <input type="hidden" name="idCheck" value="<?= $idCurrent ?>">

                    <div class="form-grid">
                        <div class="field-group full-row">
                            <label for="name">Họ và tên</label>
                            <input type="text" id="name" name="ho_ten" value="<?= htmlspecialchars($User['ho_ten']) ?>" placeholder="Nhập họ tên...">
                            <span class="error" id="hoVaTenError">Họ và tên không được để trống</span>
                        </div>

                        <div class="field-group full-row">
                            <label for="tel">Số điện thoại</label>
                            <input type="text" id="tel" name="so_dien_thoai" value="<?= htmlspecialchars($User['so_dien_thoai']) ?>" placeholder="Nhập số điện thoại...">
                            <span class="error" id="soDienThoaiError">Số điện thoại không đúng định dạng</span>
                        </div>

                        <div class="field-group">
                            <label for="sex">Giới tính</label>
                            <select id="sex" name="gioi_tinh">
                                <option value="Nam" <?= $User['gioi_tinh'] == "Nam" ? "selected" : "" ?>>Nam</option>
                                <option value="Nữ" <?= $User['gioi_tinh'] == "Nữ" ? "selected" : "" ?>>Nữ</option>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="role">Vai trò hệ thống</label>
                            <select id="role" name="vai_tro">
                                <option value="1" <?= $User['id_vai_tro'] == "1" ? "selected" : "" ?>>Khách hàng (USER)</option>
                                <option value="2" <?= $User['id_vai_tro'] == "2" ? "selected" : "" ?>>Quản trị viên (ADMIN)</option>
                            </select>
                        </div>
                    </div>

                    <div class="submit-zone">
                        <a href="quanLyKH.php" class="btn btn-back">Trở lại</a>
                        <button type="submit" class="btn btn-submit">Cập nhật dữ liệu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./js/validCapNhat_KH.js"></script>
</body>

</html>