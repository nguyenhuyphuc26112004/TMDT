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
    <title>Xóa đơn hàng</title>
    <link rel="stylesheet" href="css/delete.css">
    
</head>

<body>
    <!-- header -->
    <?php
        
        require('../php/admin/deleteObjectById.php');
        
        $idCurrent = isset($_GET['id']) ? $_GET['id'] : null;
        
        if ($idCurrent === null) {
            echo "Error: ID not provided.";
            exit;
        }
        
        // Check if the user has clicked the "Delete" button
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
            deleteRelatedRecords2($con, $_POST['idCheck']); // Delete related records first
            deleteObjectById($con, 'don_hang', $_POST['idCheck']); // Assuming the table is 'don_hang'
            header('Location: quanLyDH.php');
            exit;
        }
    ?>
    <!-- code -->
    <div class="delete">
        <div class="title">
            <h2>Xóa đơn hàng id = <?php echo $_GET['id'] ?></h2>
        </div>
        <div class="main">
            <div class="warning">
                <p>Bạn chắc chắn muốn xóa đơn hàng này chứ ?</p>
            </div>
            <div class="del">
            <form action="xoa_DH.php?id=<?php echo htmlspecialchars($idCurrent); ?>" method="POST">
                    <input type="hidden" name="idCheck" , value="<?php echo $_GET['id'] ?>">
                    <a href="quanLyDH.php" class="back" style="background-color: #1C8552; color : white; padding: 8px 19px;">Trở lại</a>
                    <button type="submit" style="background-color: #c5303a; color : white;">
                        Xóa
                    </button>

                </form>
            </div>
        </div>
    </div>
</body>

</html>