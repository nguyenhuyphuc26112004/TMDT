<?php
// ktra người dùng đăng nhập hay chưa
require('../php/checkSession.php');
checkSession(2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật sản phẩm</title>
    <link rel="stylesheet" href="./css/header1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .error {
            color: red;
            display: none;
        }
        .container{
            display: flex;
        }
        .update {
            border: 2px solid #dbdbdb;
            border-radius: 5px;
            padding: 20px;
            margin: 70px 50px;
            width: 60%;
        }
        .title {
            border-bottom: 1px solid #979494;
            margin: 25px 0px 20px 0px;
        }
        .gr {
            display: flex;
        }
        .infor {
            margin-right: 30px;
            width: 50%;
            position: relative;
            font-size: 18px;

        }
        .infor input {
            margin: 15px 0;
            border-radius: 3px;
            border: 1px solid #acacac;
            height: 30px;
            width: 97%;
            margin-bottom: 10px;
            padding-left: 10px;
            font-size: 16px;
        }
        .infor #payment {
            margin: 5px 15px;
            padding: 5px 12px;
            font-size: 16px;
            color: #000;
            border-radius: 3px;
            border: 1px solid #acacac;
        }
        .infor #loai {
            margin-top: 15px;
            padding: 6.5px 12px;
            font-size: 16px;
            color: #000;
            border-radius: 3px;
            border: 1px solid #acacac;
        }
        .infor #don_vi {
            margin-top: 15px;
            padding: 6.5px 12px;
            font-size: 16px;
            color: #000;
            border-radius: 3px;
            border: 1px solid #acacac;
        }
        .infor #anhSanPham {
            padding: 0px;
            margin: 5px 0px;
            text-align: center;
            height: 100%;
            width: 100%;
        }
        .submit {
            display: flex;
            justify-self: center;
        }

        .submit button {
            border-radius: 5px;
            border: 1px solid #acacac;
            padding: 8px 10px;
            height: 38px;
            width: 150px;
            margin-top: 20px ;
            font-size: 20px;
        }
        .submit a {
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #acacac;
            padding: 8px 10px;
            width: 100px;
            height: 20px;
            font-size: 20px;
        }
    
    </style>
</head>

<body>
    <!-- header -->
    <?php
    include('../php/admin/getObjectById.php');
    include('../php/admin/updateObjectById.php');
    $id = $_GET['id'];
    $sanPham = getObjectById($con, 'san_pham', $id);

    // check dataform

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {

        // lấy data cua form về đây
        $idProduct = $_POST['idCheck'];
        $ten = $_POST['tenSanPham'];  // thực hiện hứng dữ liệu bằng cách gán chúng vào 1 biến khác
        $loai = $_POST['loai'];
        $don_vi = $_POST['don_vi'];
        $soLuong = $_POST['soLuong'];
        $gia = $_POST['gia'];
        $trangThai = $_POST['trang_thai'];

        $anhCu = $_POST['anhCu'];
        $tenAnh = "";
        // KT người dùng có cập nhật ảnh mới hay không
        if (isset($_FILES['anhSanPham']) && $_FILES['anhSanPham']['size'] > 0) {
            // Có ảnh mới
            $tenAnh = time() . '_' . $_FILES['anhSanPham']['name']; //thêm thời gian thêm vào tên sp ( tránh trùng tên ảnh)
            $anh = $_FILES['anhSanPham']['tmp_name'];
            // thực hiện lưu ảnh vào folder img
            move_uploaded_file($anh, './img/' . $tenAnh);
        } else {
            // Không có ảnh mới
            $tenAnh = $anhCu; // cập nhật ảnh cũ vào datasbase
        }

        // gọi hàm cập nhật sp
        updateProductById($con, $idProduct, $ten, $loai, $don_vi, $soLuong, $gia, $tenAnh, $trangThai);
        header('Location: quanLySP.php');
    }

    ?>
    <!-- code -->
    <div class="container">
        <div class="trangchu">
            <img src="./logo/logo.jpg" alt="">
            <div class="tieude">
                <p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p>
            </div>
            <div class="list-tieude">
                <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
                    <div class="danhmuc">
                        <a href="quanLySP.php">Quản lý sản phẩm</a>
                        <a href="quanLyKH.php">Quản lý người dùng</a>
                        <a href="quanLyDH.php">Quản lý đơn hàng</a>
                    </div>
                <p> <i class="fa-solid fa-layer-group"></i> Đăng nhập hệ thống</p>
                    <div class="danhmuc">
                        <a href="../php/admin/logoutAdmin.php"> <i class="fa-solid fa-circle-user"></i> Đăng xuất</a>
                    </div>
            </div>
        </div>
        <div class="update">
            <div class="title">
                <h2>Cập nhật sản phẩm</h2>
            </div>
            <div class="main">
                <form id="formCapNhatSP" action="capNhat_SP.php" method="post" enctype="multipart/form-data">
                    <div class="gr">
                        <div class="infor">
                            <div class="">
                                <input type="hidden" name="idCheck" value="<?php echo $sanPham['id'] ?>" placeholder="" style="opacity: 0.6;" required>
                            </div>
                            <label for="name">Tên sản phẩm</label>
                            <input type="text" id="name" name="tenSanPham" value="<?php echo $sanPham['ten'] ?>" style="opacity: 0.6;">
                            <span class="error" id="tenError">Tên sản phẩm không được để trống</span>
                        </div>
                        <div class="infor">
                            <label for="loai">Phân loại</label><br>
                            <select name="loai" id="loai">
                                <option value="Trái cây Việt Nam" <?php echo $sanPham['loai'] == 'Trái cây Việt Nam' ? 'selected' : '  ' ?>>Trái cây Việt Nam</option>
                                <option value="Trái cây nhập khẩu" <?php echo $sanPham['loai'] == 'Trái cây nhập khẩu' ? 'selected' : ' ' ?>>Trái cây Nhập khẩu</option>
                                <option value="Quả sấy khô" <?php echo $sanPham['loai'] == 'Quả sấy khô' ? 'selected' : ' ' ?>>Quả sấy khô</option>
                                <option value="Giỏ trái cây" <?php echo $sanPham['loai'] == 'Giỏ trái cây' ? 'selected' : ' ' ?>>Giỏ trái cây</option>
                                <option value="Đồ uống trái cây" <?php echo $sanPham['loai'] == 'Đồ uống trái cây' ? 'selected' : ' ' ?>>Đồ uống trái cây</option>
                            </select>
                        </div>
                        <div class="infor">
                            <label for="trang_thai">Trạng thái</label><br>
                            <select name="trang_thai" id="loai">
                                <option value="1" <?php echo $sanPham['trang_thai'] == 1 ? 'selected' : '  ' ?>>Còn hàng
                                </option>
                                <option value="0" <?php echo $sanPham['trang_thai'] == 0 ? 'selected' : ' ' ?>>Hết hàng
                                </option>

                            </select>
                        </div>
                        <div class="infor">
                            <label for="don_vi">Đơn vị</label><br>
                            <select name="don_vi" id="don_vi">
                                <option value="Kg" <?php echo $sanPham['don_vi'] == 'Kg' ? 'selected' : '  ' ?>>Kg</option>
                                <option value="Giỏ" <?php echo $sanPham['don_vi'] == 'Giỏ' ? 'selected' : ' ' ?>>Giỏ</option>
                                <option value="Ly" <?php echo $sanPham['don_vi'] == 'Ly' ? 'selected' : ' ' ?>>Ly</option>
                            </select>
                        </div>
                    </div>
                    <div class="gr">
                        
                        <div class="infor">
                            <label for="quantify">Số lượng</label>
                            <input type="number" id="quantify" name="soLuong" value="<?php echo $sanPham['so_luong'] ?>" style="opacity: 0.6;">
                            <span class="error" id="soLuongError"> Số lượng không được để trống</span>
                            <span class="error" id="soLuongAmError"> Số lượng phải lớn hơn 0</span>

                        </div>
                        <div class="infor">
                            <label for="price">Giá</label>
                            <input type="number" id="price" name="gia" value="<?php echo $sanPham['gia'] ?>" style="opacity: 0.6;">
                            <span class="error" id="giaError"> Giá sản phẩm không được để trống</span>
                            <span class="error" id="giaAmError"> Giá sản phẩm phải lớn hơn 0</span>
                        </div>
                    </div>
                    <div class="infor">
                        <label for="anhSanPham">Ảnh SP</label>
                        <input id="anhSanPham" type="file" name="anhSanPham" style="opacity: 0.6;">
                        <!-- nhiệm vụ lưu ảnh cũ của sp -->
                        <input type="hidden" value="<?php echo $sanPham['anh'] ?>" name="anhCu">
                    </div><br>
                    <div class="infor">
                        <img src="img/<?php echo $sanPham['anh'] ?>"  alt="">

                    </div>

                    <div class="submit">
                        <a href="quanLySP.php" style="background-color: #1C8552; color : white;">Trở lại</a>
                        <button style="background-color: #24ACF2; color: white">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="./js/validCapNhat_SP.js"></script>
</body>

</html>