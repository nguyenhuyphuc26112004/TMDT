<?php
header('Content-Type: application/json');
require('../connectMysql.php');
require('cart.php');

// Lấy dữ liệu từ Fetch API gửi lên
$input = json_decode(file_get_contents('php://input'), true);
$idProduct = $input['idsp'] ?? null;
$idCart = $input['idgh'] ?? null;

if (!$idProduct || !$idCart) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
    exit;
}

// 1. Lấy thông tin giỏ để giảm số lượng loại SP
$cart = getCartById($con, $idCart);
if ($cart) {
    $soLuongMoi = max(0, $cart['so_luong_sp'] - 1); // Đảm bảo không âm
    updateCart($con, $idCart, $soLuongMoi);
}

// 2. Xóa sản phẩm khỏi chi tiết giỏ hàng
$sql = "DELETE FROM ct_gio_hang WHERE id_gio_hang = ? AND id_san_pham = ?";
$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ii", $idCart, $idProduct);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Đã xóa sản phẩm']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi thực thi SQL']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi chuẩn bị câu lệnh']);
}