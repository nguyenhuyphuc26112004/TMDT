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
    <title>Xóa khách hàng</title>
    <link rel="stylesheet" href="css/delete.css">
</head>

<body>
    <!-- header -->
    <?php
    // require('layout/header.php');
    require('../php/admin/deleteObjectById.php');

    $idCurrent = $_GET['id']; // lấy id từ link url 

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
        deleteRelatedRecords($con, $_POST['idCheck']); // Delete related records first
        deleteObjectById($con, 'nguoi_dung', $_POST['idCheck']);
        header('Location: quanLyKH.php');
        exit;
    }
    ?>
    <!-- code -->
    <div class="delete">
        <div class="title">
            <h2>Xóa khách hàng id = <?php echo $_GET['id'] ?></h2>
        </div>
        <div class="main">
            <div class="warning">
                <p>Bạn chắc chắn muốn xóa khách hàng này chứ ?</p>
            </div>
            <div class="del">
                <form action="xoa_KH.php" method="POST">
                    <input type="hidden" name="idCheck" , value="<?php echo $_GET['id'] ?>">
                    <a href="quanLyKH.php" class="back" style="background-color: #1C8552; color : white; padding: 8px 19px;">Trở lại</a>
                    <button type="submit" style="background-color: #c5303a; color : white;">
                        Xóa
                    </button>

                </form>

            </div>
        </div>
    </div>
</body>

</html>