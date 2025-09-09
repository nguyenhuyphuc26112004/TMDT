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
    <title>Xóa sản phẩm</title>
    <link rel="stylesheet" href="css/delete.css">
</head>

<body>
    <!-- header -->
    <?php
    require('../php/admin/deleteObjectById.php');
    $idProduct = $_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
        // gọi hàm của deleteObjectById.php
        deleteProductById($con, $_POST['idCheck']);

        // Sau khi xóa xong, chuyển hướng trở lại trang quản lý 
        header('Location: quanLySP.php');
        exit; // không thực hiện các câu lệnh phía sau 
    }

    ?>
    <!-- code -->
    <div class="delete">
        <div class="title">
            <h2>Xóa sản phẩm id = <?php echo $_GET['id'] ?></h2>
        </div>
        <div class="main">
            <div class="warning">
                <p>Bạn chắc chắn muốn xóa sản phẩm này chứ ? </p>
            </div>
            <div class="del">
                <form action="xoa_SP.php" method="POST">
                    <input type="hidden" name="idCheck" , value="<?php echo $_GET['id'] ?>">
                    <a href="quanLySP.php" class="back" style="background-color: #1C8552; color : white;">Trở lại</a>
                    <button type="submit" style="background-color: #c5303a; color : white;">
                        Xóa
                    </button>

                </form>
            </div>
        </div>
    </div>
</body>

</html>