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
    <title>Cập nhật khách hàng</title>
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
            margin: 20px 38% 0 38%;
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
    require('../php/admin/getObjectById.php');
    require('../php/admin/updateObjectById.php');

    $idCurrent = $_GET['id']; // lấy id từ link url 

    // gọi hàm của getObjectById.php
    $User = getObjectById($con, 'nguoi_dung', $idCurrent);

    // Kiểm tra nếu người dùng nhấn nút "Cập nhật"
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
        // lấy thông tin từ form
        $id = $_POST['idCheck'];
        $hoVaTen = $_POST['ho_ten'];
        $soDienThoai = $_POST['so_dien_thoai'];
        $gioiTinh = $_POST['gioi_tinh'];
        $idVaiTro = $_POST['vai_tro'];


        // gọi hàm của updateObjectById.php
        updateUserById($con, $id, $hoVaTen, $idVaiTro, $soDienThoai, $gioiTinh);

        // Sau khi xóa xong, chuyển hướng trở lại trang quản lý khách hàng
        header('Location: quanLyKH.php');
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
                <h2>Cập nhật khách hàng id = <?php echo $_GET['id'] ?></h2>
            </div>
            <div class="main">
                <form id = "formCapNhatKH" action="capNhat_KH.php" method="post">
                    <div class="gr">
                        <div class="">
                            <input type="hidden" name="idCheck" value="<?php echo $idCurrent ?>" placeholder="" style="opacity: 0.6;" required>
                        </div>
                        <div class="infor">
                            <label for="name"><b>Họ và tên</b></label>
                            <input type="text" id = "name" name="ho_ten" value="<?php echo $User['ho_ten'] ?>" placeholder="" style="opacity: 0.6;" >
                            <span class="error" id="hoVaTenError">Họ và tên không được để trống</span> 
                        </div>
                        <div class="infor">
                            <label for="tel"><b>Số điện thoại</b></label>
                            <input type="text" id="tel" name="so_dien_thoai" value="<?php echo $User['so_dien_thoai'] ?>" placeholder="" style="opacity: 0.6;" >
                            <span class="error" id="soDienThoaiError">Số điện thoại không được để trống</span> 
                        </div>
                    </div>
                    
                    <div class="infor">
                        <label for="sex"><b> Giới tính</b>:</label>
                        <select id="sex" name="gioi_tinh" style="opacity: 0.6;" >
                            <option value="Nam" <?php echo $User['gioi_tinh'] == "Nam" ? "selected" : " " ?>>Nam</option>
                            <option value="Nữ" <?php echo $User['gioi_tinh'] == "Nữ" ? "selected" : " " ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="infor">
                        <label for="sex"> <b>Vai trò: </b> </label>
                        <select id="sex" name="vai_tro" style="opacity: 0.6;" >
                            <option value="1" <?php echo $User['id_vai_tro'] == "1" ? "selected" : " " ?>>USER</option>
                            <option value="2" <?php echo $User['id_vai_tro'] == "2" ? "selected" : " " ?>>ADMIN</option>
                        </select>
                    </div>
                    <div class="submit">
                        <a href="quanLyKH.php" style="background-color: #1C8552; color : white;">Trở lại</a>
                        <button type="submit" style="background-color: #FBBE00; color : black;">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="./js/validCapNhat_KH.js"></script>


</body>

</html>