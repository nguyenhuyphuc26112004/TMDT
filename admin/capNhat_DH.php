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
    <title>Cập nhật đơn hàng</title>
    <link rel="stylesheet" href="css/update.css">
    <link rel="stylesheet" href="./css/header1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <style>
        .container{
            display: flex;
        }
        .error {
            color: red;
            display: none;
        }   
        .update {
            border: 2px solid #dbdbdb;
            border-radius: 5px;
            padding: 20px;
            margin: 130px 50px;
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
        .infor #sex {
            margin: 5px 15px;
            padding: 5px 12px;
            font-size: 16px;
            color: #000;
            border-radius: 3px;
            border: 1px solid #acacac;
        }
        .submit {
            margin: 20px 35% 0 35%;
            display: flex;
        }

        .submit button {
            border-radius: 5px;
            border: 1px solid #acacac;
            padding: 8px 10px;
            height: 38px;
            margin-top: 20px ;
            font-size: 20px;
        }

        .submit a {
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #acacac;
            padding: 8px 10px;
            height: 20px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php
    // require('layout/header.php');
    require('../php/admin/getObjectById.php');
    require('../php/admin/updateObjectById.php');
    $idDonHang = $_GET['id'];

    // gọi hàm của getObjectById.php
    $donHang = getObjectById($con, 'don_hang', $idDonHang);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {

        // lấy thông tin từ form
        $idDonHang = $_POST['idCheck'];
        $diaChi = $_POST['diaChi'];
        $trangThai = $_POST['trangThai'];
        
        // gọi hàm của updateObjectById.php
        updateOrderById($con, $idDonHang, $diaChi, $trangThai);

        // Sau khi xóa xong, chuyển hướng trở lại trang quản lý khách hàng
        header('Location: quanLyDH.php');
        exit; // không thực hiện các câu lệnh phía sau
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
                <h2>Cập nhật đơn hàng</h2>
            </div>
            <div class="main">
                <form action="capNhat_DH.php" method="post" id="formCapNhat">
                    <div class="gr">
                        <div class="infor">
                            <label for="address">Địa chỉ</label>
                            <input type="text" id="address" name="diaChi" style="opacity: 0.6;" value=" <?php echo $donHang['dia_chi'] ?>">
                            <span class="error" id="diaChiError">Địa chỉ không được để trống.</span>
                        </div>
                        <div class="infor">
                            <label for="mode"> Trạng thái</label>
                            <select id="mode" name="trangThai" style="opacity: 0.6;" required>
                                <option value=""> </option>
                                <option value="Đã hủy" <?php echo $donHang['trang_thai'] == 'Đã hủy' ? 'selected' : ' ' ?>>Đã hủy </option>
                                <option value="Đang chờ duyệt" <?php echo $donHang['trang_thai'] == 'Đang chờ duyệt' ? 'selected' : ' ' ?>>Đang chờ duyệt</option>
                                <option value="Đang vận chuyển" <?php echo $donHang['trang_thai'] == 'Đang vận chuyển' ? 'selected' : ' ' ?>>Đang vận chuyển</option>
                                <option value="Đã nhận hàng" <?php echo $donHang['trang_thai'] == 'Đã nhận hàng' ? 'selected' : ' ' ?>>Đã nhận hàng</option>
                            </select>
                        </div>
                    </div>
                    <div class="gr">
                        <div class="infor">
                            <input type="hidden" name="idCheck" value="<?php echo $idDonHang ?>">
                        </div>
                    </div>

                    <div class="submit">
                        <a href="quanLyDH.php" style="background-color: #1C8552; color : white;">Trở lại</a>
                        <button style="background-color: #FBBE00; color : black;">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Xử lý kiểm tra đầu vào bằng JavaScript
        const form = document.getElementById('formCapNhat');

        
        const address = document.getElementById('address');
        const diaChiError = document.getElementById('diaChiError');

        form.addEventListener('submit', function(e) {
            let check = true;

            if (address.value.trim() === '') {
                check = false;
                diaChiError.style.display = 'block';
                diaChiError.textContent = 'Địa chỉ không được để trống.';
            } else {
                diaChiError.style.display = 'none';
            }

            if (!check) {
                e.preventDefault(); // Ngăn chặn submit nếu có lỗi
            }
        });
    </script>
</body>

</html>