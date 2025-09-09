<?php
// ktra người dùng đăng nhập hay chưa
require('php/checkSession.php');
require('php/client/order.php');
require('php/client/cart.php');
require('php/client/getObjectById.php');
checkSessionClient();
// echo "hello";

/*
            Logic mua hàng
    - tạo đơn hàng cho người dùng -> tạo mới chi tiết đơn hàng cho người dùng
    -
*/

// Lấy thông tin
$idNguoiDung = $_SESSION['idNguoiDung'];
$name = $_POST['name'];
$sdt = $_POST['sdt'];
$email = $_POST['email'];
$address = $_POST['address'];
$tongTien = $_POST['tongTien'];
$trangThai = 'Đang chờ duyệt';

// echo "hello" . " <br>" . $idNguoiDung . " <br>" . $tenNguoiNhan  . " <br>" . $soDienThoai . " <br>" . $diaChi . " <br>" . $tongTien;

// Tạo đơn hàng
$idDonHang = insertOrder($con, $idNguoiDung, $tongTien, $address, $sdt, $email,  $name, $trangThai);

// Tạo chi tiết đơn hàng
$idNguoiDung = $_SESSION['idNguoiDung'];
$gioHang = checkCart($con, $idNguoiDung);
// mảng lưu tất cả sp của người dùng 
$cTGioHang = getCartDetailByCart($con, $gioHang['id']);
// duyet qua tung sp va them sp vao ct don hang
foreach ($cTGioHang as $cTGH) {

    // lấy sp thông qua id sản phẩm
    $sp = getObjectById($con, 'san_pham', $cTGH['id_san_pham']);
    $idSanPham = $sp['id'];
    $soLuong = $cTGH['so_luong'];
    $gia = $sp['gia'];

    insertOrderDetail($con, $idDonHang, $idSanPham, $soLuong, $gia);
}


// Đăt hàng thành công -> xóa các sp đã mua trong gio hang và ct gio hang
// vì có ràng buộc khóa ngoại giữa ct_gio_hang và gio_hang -> xóa ct_gio_hang truoc
$idGioHang = $gioHang['id'];
deleteProductInCartDetail($con, $idGioHang);
updateProductInCart($con, $idNguoiDung);
// Hoan thanh -> chuyen trang
header('Location: trangChu.php');
