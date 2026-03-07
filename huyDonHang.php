<?php
// 1. Kiểm tra người dùng đăng nhập hay chưa
require('php/checkSession.php');
checkSessionClient();

// 2. Kết nối CSDL (Đảm bảo file này có biến $con)
require('php/client/getObjectByCondition.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
    $idDonHang = $_POST['idCheck'];

    // --- BƯỚC 1: HOÀN TRẢ SỐ LƯỢNG SẢN PHẨM VÀO KHO ---
    // Truy vấn lấy các sản phẩm trong đơn hàng này
    $sql_details = "SELECT id_san_pham, so_luong FROM ct_don_hang WHERE id_don_hang = ?";
    $stmt_details = $con->prepare($sql_details);
    $stmt_details->bind_param("i", $idDonHang);
    $stmt_details->execute();
    $result_details = $stmt_details->get_result();

    // Duyệt từng sản phẩm để cộng lại vào kho
    while ($item = $result_details->fetch_assoc()) {
        $idSP = $item['id_san_pham'];
        $soLuongHoan = $item['so_luong'];

        // Cập nhật lại bảng san_pham (cộng thêm số lượng)
        $sql_update_stock = "UPDATE san_pham SET so_luong = so_luong + ? WHERE id = ?";
        $stmt_stock = $con->prepare($sql_update_stock);
        $stmt_stock->bind_param("ii", $soLuongHoan, $idSP);
        $stmt_stock->execute();
    }

    // --- BƯỚC 2: CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG THÀNH 'ĐÃ HỦY' ---
    // (Chỉ dùng cột trang_thai có sẵn trong DB của bạn)
    $sql_order = "UPDATE don_hang SET trang_thai = 'Đã hủy' WHERE id = ?";
    $stmt_order = $con->prepare($sql_order);
    $stmt_order->bind_param("i", $idDonHang);
    $stmt_order->execute();

    // Xong thì quay về trang danh sách đơn hàng
    header('Location: donHang.php');
    exit;
}

// Lấy ID từ URL để hiển thị trên giao diện
$idHienThi = isset($_GET['id']) ? $_GET['id'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hủy đơn hàng</title>
    <style>
        .delete { margin: 130px 30px; font-family: Arial, sans-serif; }
        .delete .title { border-bottom: 1px solid #979494; margin-bottom: 15px; }
        .warning { background-color: #f925164e; color: rgb(76, 12, 12); padding: 10px; border-radius: 2px; }
        .del { margin-top: 25px; display: flex; gap: 10px; }
        .del a { text-decoration: none; padding: 10px; border: 1px solid #acacac; border-radius: 5px; background: #1C8552; color: white; }
        button { border-radius: 5px; border: 1px solid #acacac; padding: 8px 25px; background: #c5303a; color: white; cursor: pointer; }
    </style>
</head>
<body>
    <div class="delete">
        <div class="title">
            <h2>Hủy đơn hàng id = <?php echo htmlspecialchars($idHienThi) ?></h2>
        </div>
        <div class="main">
            <div class="warning">
                <p>Bạn chắc chắn muốn hủy đơn hàng này chứ? (Số lượng sản phẩm sẽ được hoàn trả lại vào kho)</p>
            </div>
            <div class="del">
                <form action="" method="POST">
                    <input type="hidden" name="idCheck" value="<?php echo htmlspecialchars($idHienThi) ?>">
                    <a href="donHang.php">Trở lại</a>
                    <button type="submit">Hủy</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>