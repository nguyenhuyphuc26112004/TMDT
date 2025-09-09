<?php
require('../connectMysql.php');
require('cart.php');

$idProduct = $_GET['idsp'];
$idCart = $_GET['idgh'];

$cart = getCartById($con, $idCart);
$soLuongCart = $cart['so_luong_sp'];
$soLuong  = $soLuongCart - 1;
updateCart($con, $idCart, $soLuong);

// Câu lệnh SQL
$sql = "DELETE FROM ct_gio_hang WHERE id_gio_hang = ? AND id_san_pham = ? ; ";



// Chuẩn bị câu lệnh
$stmt = $con->prepare($sql);

// Kiểm tra câu lệnh đã sẵn sàng chưa
if ($stmt) {

    // gán các tham số vào câu lệnh
    $stmt->bind_param("ii", $idCart, $idProduct);

    // Thực thi câu lệnh SQL
    $stmt->execute();

    header('Location: ../../gioHang.php');
    exit;
}

echo ">>>>>>>>>>>>> Lỗi xóa sp";
