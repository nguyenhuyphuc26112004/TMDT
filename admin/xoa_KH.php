<?php
require('../php/checkSession.php');
// Kiểm tra quyền Admin (vai trò = 2)
checkSession(2); 

require('../php/admin/deleteObjectById.php');

// Lấy ID khách hàng từ URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: quanLyKH.php');
    exit;
}

$idCurrent = $_GET['id'];
$error_message = "";
$canDelete = true;

$totalOrders = countAllOrders($con, $idCurrent);

if ($totalOrders > 0) {
    $error_message = "Người này hiện đang có <b>$totalOrders đơn hàng</b> trong hệ thống. Để bảo đảm tính chính xác của báo cáo doanh thu, bạn không thể xóa khách hàng này.";
    $canDelete = false;
}

// 2. Xử lý khi người dùng nhấn nút xác nhận xóa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck']) && $canDelete) {
    $idCheck = $_POST['idCheck'];

    // Dọn dẹp giỏ hàng trước (vì giỏ hàng không ảnh hưởng báo cáo doanh thu)
    deleteCartByUserId($con, $idCheck); 
    
    // Thực hiện xóa tài khoản khách hàng
    if (deleteObjectById($con, 'nguoi_dung', $idCheck)) {
        header('Location: quanLyKH.php?success=1');
        exit;
    } else {
        $error_message = "Đã xảy ra lỗi trong quá trình xóa dữ liệu.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận xóa khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 500px; text-align: center; }
        h2 { color: #333; margin-top: 0; }
        .alert { background-color: #f8d7da; color: #721c24; padding: 20px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px; text-align: left; }
        .alert i { margin-right: 10px; font-size: 1.2em; }
        .info-box { background-color: #e2e3e5; padding: 15px; border-radius: 5px; margin-bottom: 25px; color: #383d41; }
        .btn-group { display: flex; justify-content: center; gap: 15px; }
        .btn { padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; cursor: pointer; border: none; transition: 0.3s; }
        .btn-back { background-color: #6c757d; color: white; }
        .btn-back:hover { background-color: #5a6268; }
        .btn-delete { background-color: #c5303a; color: white; }
        .btn-delete:hover { background-color: #a72832; }
        .btn-disabled { background-color: #ced4da; color: #6c757d; cursor: not-allowed; }
    </style>
</head>
<body>

<div class="container">
    <i class="fa-solid fa-user-slash" style="font-size: 3em; color: #dc3545; margin-bottom: 15px;"></i>
    <h2>Xóa khách hàng</h2>

    <?php if (!$canDelete): ?>
        <div class="alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            <strong>Cảnh báo:</strong> <?php echo $error_message; ?>
        </div>
    <?php else: ?>
        <div class="info-box">
            Bạn có chắc chắn muốn xóa khách hàng ID: <strong><?php echo htmlspecialchars($idCurrent); ?></strong>?
            <br><small>(Hành động này sẽ xóa vĩnh viễn tài khoản người dùng)</small>
        </div>
    <?php endif; ?>

    <div class="btn-group">
        <a href="quanLyKH.php" class="btn btn-back">Quay lại</a>

        <form action="" method="POST">
            <input type="hidden" name="idCheck" value="<?php echo htmlspecialchars($idCurrent); ?>">
            
            <?php if ($canDelete): ?>
                <button type="submit" class="btn btn-delete" onclick="return confirm('Bạn thực sự muốn xóa vĩnh viễn khách hàng này?')">
                    Xác nhận xóa
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-disabled" title="Không thể xóa vì còn đơn hàng">
                    Không thể xóa
                </button>
            <?php endif; ?>
        </form>
    </div>
</div>

</body>
</html>