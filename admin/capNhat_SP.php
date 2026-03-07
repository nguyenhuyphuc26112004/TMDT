<?php
// 1. Kiểm tra session
require('../php/checkSession.php');
checkSession(2);

include('../php/admin/getObjectById.php');
include('../php/admin/updateObjectById.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: quanLySP.php');
    exit();
}

$sanPham = getObjectById($con, 'san_pham', $id);
$errors = [];

// Xử lý Form khi Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
    $idProduct = $_POST['idCheck'];
    $ten = trim($_POST['tenSanPham']);
    $loai = $_POST['loai'];
    $don_vi = $_POST['don_vi'];
    $soLuong = $_POST['soLuong'];
    $gia = $_POST['gia'];
    $anhCu = $_POST['anhCu'];

    $trangThai = ((int)$soLuong > 0) ? 1 : 0;

    // ===== VALIDATE =====
    if ($ten == "") $errors['ten'] = "Tên sản phẩm không được để trống";
    if ($soLuong === "" || !is_numeric($soLuong)) {
        $errors['soLuong'] = "Số lượng không hợp lệ";
    } elseif ($soLuong < 0) {
        $errors['soLuong'] = "Số lượng không được âm";
    }
    if ($gia === "" || !is_numeric($gia)) {
        $errors['gia'] = "Giá không hợp lệ";
    } elseif ($gia <= 0) {
        $errors['gia'] = "Giá phải lớn hơn 0";
    }

    // ===== Nếu không có lỗi thì update =====
    if (empty($errors)) {
        if (isset($_FILES['anhSanPham']) && $_FILES['anhSanPham']['size'] > 0) {
            $tenAnh = time() . '_' . $_FILES['anhSanPham']['name'];
            move_uploaded_file($_FILES['anhSanPham']['tmp_name'], './img/' . $tenAnh);
        } else {
            $tenAnh = $anhCu;
        }

        updateProductById($con, $idProduct, $ten, $loai, $don_vi, $soLuong, $gia, $tenAnh, $trangThai);
        header('Location: quanLySP.php');
        exit();
    }

   
}
?>
    
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        /* GIỮ NGUYÊN CSS GỐC CỦA BẠN CHO PHẦN SIDEBAR */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .tieude i { margin-right: 5px; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 20px; margin: 15px 0; color: #333; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; text-align: center; margin-right: 10px; }

        /* LÀM MỚI CSS CHO PHẦN UPDATE */
        .update {
            flex: 1;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            width: 100%;
            max-width: 800px;
            height: fit-content;
        }
        .title {
            border-bottom: 2px solid #24ACF2;
            margin-bottom: 25px;
            padding-bottom: 10px;
        }
        .title h2 { margin: 0; color: #333; font-size: 24px; }

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
            border-color: #24ACF2;
            box-shadow: 0 0 5px rgba(36,172,242,0.2);
        }

        .error { color: #e74c3c; font-size: 13px; margin-top: 5px; display: block; font-style: italic; }

        .image-section {
            display: flex;
            align-items: center;
            gap: 20px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            border: 1px dashed #ccc;
        }
        .image-section img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

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
        .btn-cancel { background-color: #7f8c8d; color: white; }
        .btn-cancel:hover { background-color: #636e72; }
        .btn-submit { background-color: #24ACF2; color: white; }
        .btn-submit:hover { background-color: #1b8ec9; }
    </style>
</head>

<body>
    <div class="container">
        <div class="trangchu">
            <img src="./logo/logo.jpg" alt="Logo">
            <div class="tieude"><p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p></div>
            <div class="list-tieude">
                <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
                <div class="danhmuc">
                    <a href="quanLyKH.php"><i class="fa-solid fa-users"></i> Quản lý người dùng</a>
                    <a href="quanLySP.php"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</a>
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
                    <h2>Cập nhật sản phẩm</h2>
                </div>

                <form id="formCapNhatSP" action="capNhat_SP.php?id=<?= $sanPham['id'] ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idCheck" value="<?= $sanPham['id'] ?>">
                    <input type="hidden" name="anhCu" value="<?= $sanPham['anh'] ?>">

                    <div class="form-grid">
                        <div class="field-group full-row">
                            <label for="name">Tên sản phẩm</label>
                            <input type="text" id="name" name="tenSanPham" value="<?= htmlspecialchars($sanPham['ten']) ?>">
                            <?php if(isset($errors['ten'])): ?>
                                <span class="error"><?= $errors['ten'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label for="loai">Phân loại</label>
                            <select name="loai" id="loai">
                                <?php 
                                $types = ["Trái cây Việt Nam", "Trái cây nhập khẩu", "Quả sấy khô", "Giỏ trái cây", "Đồ uống trái cây"];
                                foreach($types as $t) {
                                    $selected = ($sanPham['loai'] == $t) ? "selected" : "";
                                    echo "<option value='$t' $selected>$t</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="trang_thai">Trạng thái</label>
                            <select name="trang_thai" id="trang_thai">
                                <option value="1" <?= $sanPham['trang_thai'] == 1 ? 'selected' : '' ?>>Còn hàng</option>
                                <option value="0" <?= $sanPham['trang_thai'] == 0 ? 'selected' : '' ?>>Hết hàng</option>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="don_vi">Đơn vị</label>
                            <select name="don_vi" id="don_vi">
                                <option value="Kg" <?= $sanPham['don_vi'] == 'Kg' ? 'selected' : '' ?>>Kg</option>
                                <option value="Giỏ" <?= $sanPham['don_vi'] == 'Giỏ' ? 'selected' : '' ?>>Giỏ</option>
                                <option value="Ly" <?= $sanPham['don_vi'] == 'Ly' ? 'selected' : '' ?>>Ly</option>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="quantify">Số lượng</label>
                            <input type="number" id="quantify" name="soLuong" value="<?= $sanPham['so_luong'] ?>">
                            <?php if(isset($errors['soLuong'])): ?>
                                <span class="error"><?= $errors['soLuong'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="field-group">
                            <label for="price">Giá bán (VNĐ)</label>
                            <input type="number" id="price" name="gia" value="<?= $sanPham['gia'] ?>">
                            <?php if(isset($errors['gia'])): ?>
                                <span class="error"><?= $errors['gia'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="field-group full-row">
                            <label>Hình ảnh sản phẩm</label>
                            <div class="image-section">
                                <img src="img/<?= $sanPham['anh'] ?>" alt="Sản phẩm">
                                <div style="flex:1">
                                    <input type="file" name="anhSanPham" style="border:none; padding:0;">
                                    <p style="font-size:12px; color:#777; margin-top:10px;">
                                        <i class="fa-solid fa-info-circle"></i> Chọn file khác nếu muốn thay đổi ảnh.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="submit-zone">
                        <a href="quanLySP.php" class="btn btn-cancel">Hủy bỏ</a>
                        <button type="submit" class="btn btn-submit">Cập nhật ngay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./js/validCapNhat_SP.js"></script>
</body>
</html>