<?php
session_start();

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['idNguoiDung'])) {
    header('Location: dangNhap.php');
    exit(); // Luôn dùng exit sau header redirect
}

require('php/client/cart.php');

// 2. Tiếp nhận dữ liệu từ Form
$idNguoiDung = $_SESSION['idNguoiDung'];
$idSanPham   = $_POST['idSanPham'];
$giaSanPham  = $_POST['giaSanPham'];
$soLuong     = (int)$_POST['soLuong'];
$action      = $_POST['action'] ?? 'add'; // Mặc định là 'add' nếu không có (đề phòng)

// 3. Xử lý logic Giỏ hàng
$gioHang = checkCart($con, $idNguoiDung); // kiểm tra người dùng có giỏ hàng hay ch

if (is_array($gioHang) && !empty($gioHang)) {
    // TRƯỜNG HỢP: Đã có giỏ hàng
    $idGioHang = $gioHang['id'];
    $soLuongInCart = $gioHang['so_luong_sp'];
    
    $cTGioHang = checkCartAndProduct($con, $idGioHang, $idSanPham);
    // kiểm tra sản phẩm đã có trong giỏ hàng hay ch
    if (is_array($cTGioHang) && !empty($cTGioHang)) {
        // Sản phẩm đã tồn tại -> Cập nhật số lượng (+ thêm số lượng mới chọn)
        $currentQuantity = $cTGioHang['so_luong'] + $soLuong;
        $idCTGioHang = $cTGioHang['id'];
        updateCartDetail($con, $idCTGioHang, $currentQuantity);
    } else {
        // Sản phẩm mới -> Thêm dòng mới vào chi tiết
        insertCartDetail($con, $idGioHang, $idSanPham, $soLuong, $giaSanPham);
        $soLuongInCart += 1; // Tăng số lượng loại sản phẩm trong giỏ
    }
    // Cập nhật lại tổng số lượng mặt hàng trong giỏ chính
    updateCart($con, $idGioHang, $soLuongInCart);

} else {
    // TRƯỜNG HỢP: Chưa có giỏ hàng (Tạo mới hoàn toàn)
    $idGioHang = insertCart($con, $idNguoiDung, 1); // Tạo giỏ với 1 loại SP đầu tiên
    if (is_numeric($idGioHang)) {
        insertCartDetail($con, $idGioHang, $idSanPham, $soLuong, $giaSanPham); // thêm 1 bàn ghi ct giỏ hàng
    } else {
        die("Lỗi: Không thể tạo mới giỏ hàng.");
    }
}

// 4. Điều hướng dựa trên nút bấm (UX)
if ($action === 'buy') {
    // Nếu bấm "Đặt hàng" -> Nhảy thẳng đến trang thanh toán
    header("Location: trangDatHang.php?id=$idGioHang");
} else {
    // Nếu bấm "Thêm vào giỏ hàng" -> Quay về xem giỏ hàng
    header('Location: gioHang.php');
}
exit();