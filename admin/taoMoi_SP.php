<?php
require('../php/checkSession.php');
checkSession(2);

require('../php/connectMysql.php');
require('../php/admin/saveObject.php');


$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ten = trim($_POST['tenSanPham']);
    $loai = $_POST['loai'];
    $don_vi = $_POST['don_vi'];
    $soLuong = $_POST['soLuong'];
    $gia = $_POST['gia'];
    $old = $_POST;

    // ===== VALIDATE =====

    if ($ten == "") {
        $errors['ten'] = "Tên sản phẩm không được để trống";
    } else {
        $sql = "SELECT id FROM san_pham WHERE ten = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $ten);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['ten'] = "Tên sản phẩm đã tồn tại";
        }
    }

    if ($soLuong === "" || !is_numeric($soLuong)) {
        $errors['soLuong'] = "Số lượng không hợp lệ";
    } elseif ($soLuong <= 0) {
        $errors['soLuong'] = "Số lượng phải lớn hơn 0";
    }

    if ($gia === "" || !is_numeric($gia)) {
        $errors['gia'] = "Giá không hợp lệ";
    } elseif ($gia <= 0) {
        $errors['gia'] = "Giá phải lớn hơn 0";
    }


    if (!isset($_FILES['anhSanPham']) || $_FILES['anhSanPham']['size'] == 0) {
        $errors['anh'] = "Ảnh sản phẩm không được để trống";
    } else {
        $fileName = $_FILES['anhSanPham']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
            $errors['anh'] = "File phải là ảnh (.jpg, .jpeg, .png)";
        }
    }

    if (empty($errors)) {

        $tenAnh = time() . '_' . $_FILES['anhSanPham']['name'];
        $anh = $_FILES['anhSanPham']['tmp_name'];
        move_uploaded_file($anh, './img/' . $tenAnh);

        saveProduct($con, $ten, $loai, $don_vi, $soLuong, $gia, $tenAnh);

        header('Location: quanLySP.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm Mới</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/create.css">
    <style>
        :root {
    --primary-color: #24ACF2;
    --secondary-color: #6c757d;
    --success-color: #1C8552;
    --error-color: #dc3545;
    --bg-color: #f4f7f6;
    --text-color: #333;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--bg-color);
    color: var(--text-color);
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: 40px auto;
}

.form-card {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
}

.form-header h2 {
    margin: 0;
    color: #222;
}

.form-header p {
    color: #777;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.row {
    display: flex;
    gap: 15px;
}

.row .form-group {
    flex: 1;
}

label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
    font-size: 14px;
}

.required {
    color: var(--error-color);
}

input[type="text"],
input[type="number"],
select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

input:focus, select:focus {
    outline: none;
    border-color: var(--primary-color);
}

.input-error {
    border-color: var(--error-color) !important;
}

.error-text {
    color: var(--error-color);
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

/* Upload & Preview */
.upload-container {
    border: 2px dashed #ddd;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}

#preview {
    max-width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 10px;
}

/* Buttons */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: opacity 0.3s;
}

.btn:hover {
    opacity: 0.9;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn-secondary {
    background-color: #e9ecef;
    color: #495057;
}
    </style>
</head>
<body>

<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h2>Tạo Sản Phẩm Mới</h2>
            <p>Vui lòng điền đầy đủ thông tin bên dưới</p>
        </div>

        <form action="taoMoi_SP.php" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Tên sản phẩm <span class="required">*</span></label>
                <input type="text" name="tenSanPham" 
                       value="<?= $old['tenSanPham'] ?? '' ?>" 
                       placeholder="Ví dụ: Táo Envy Mỹ"
                       class="<?= isset($errors['ten']) ? 'input-error' : '' ?>">
                <?php if(isset($errors['ten'])): ?>
                    <span class="error-text"><?= $errors['ten'] ?></span>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Phân loại</label>
                    <select name="loai">
                        <?php 
                        $categories = ["Trái cây nhập khẩu", "Trái cây Việt Nam", "Quả sấy khô", "Giỏ trái cây", "Đồ uống trái cây"];
                        foreach($categories as $cat): ?>
                            <option value="<?= $cat ?>" <?= (isset($old['loai']) && $old['loai'] == $cat) ? 'selected' : '' ?>>
                                <?= $cat ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Đơn vị tính</label>
                    <select name="don_vi">
                        <option value="Kg" <?= (isset($old['don_vi']) && $old['don_vi'] == 'Kg') ? 'selected' : '' ?>>Kg</option>
                        <option value="Giỏ" <?= (isset($old['don_vi']) && $old['don_vi'] == 'Giỏ') ? 'selected' : '' ?>>Giỏ</option>
                        <option value="Ly" <?= (isset($old['don_vi']) && $old['don_vi'] == 'Ly') ? 'selected' : '' ?>>Ly</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Số lượng <span class="required">*</span></label>
                    <input type="number" name="soLuong" value="<?= $old['soLuong'] ?? '' ?>" placeholder="0">
                    <?php if(isset($errors['soLuong'])): ?>
                        <span class="error-text"><?= $errors['soLuong'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Giá bán (VNĐ) <span class="required">*</span></label>
                    <input type="number" name="gia" value="<?= $old['gia'] ?? '' ?>" placeholder="0">
                    <?php if(isset($errors['gia'])): ?>
                        <span class="error-text"><?= $errors['gia'] ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Hình ảnh sản phẩm <span class="required">*</span></label>
                <div class="upload-container">
                    <input type="file" name="anhSanPham" id="imageInput" accept="image/*">
                    <div id="preview-container">
                        <img id="preview" src="#" alt="Xem trước ảnh" style="display:none;">
                    </div>
                </div>
                <?php if(isset($errors['anh'])): ?>
                    <span class="error-text"><?= $errors['anh'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <a href="quanLySP.php" class="btn btn-secondary">Hủy bỏ</a>
                <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
            </div>

        </form>
    </div>
</div>

<script>
    // Logic xem trước ảnh khi chọn file
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('preview');

    imageInput.onchange = evt => {
        const [file] = imageInput.files;
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }
</script>

</body>
</html>