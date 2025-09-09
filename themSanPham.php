<?php

session_start();
if (!isset($_SESSION['idNguoiDung'])) {
    header('Location: dangNhap.php');
} else {
    require('php/client/cart.php');
    /*
        Logic thêm sản phẩm vào giỏ hàng

    ( có giỏ hàng <-> có ct giỏ hànghàng)

    1 . ktra người dùng đã có giỏ hang hay chua

    -> chưa -> thực hiện  tạo mới giỏ hàng và tạo mới ct giỏ hàng
    -> có rồi :
        -> Ktra người dùng đã thêm sp này vào hay chưa
                -> thêm rồi : <-> có bản ghi trùng ( id cart và id product này ) trong  data table ct_gio_hang 
                    -> thực hiện tăng so_luong của id ct_gio_hang này lên 1
                -> chưa thêm : 
                    -> thực hiện thêm <-> tạo mới 1 bản ghi ct_gio_hang có ( id cart và id product này ) và sp_luong = 1
*/
    $idNguoiDung = $_SESSION['idNguoiDung'];
    $idSanPham =  $_POST['idSanPham'];
    $giaSanPham = $_POST['giaSanPham'];
    $soLuong = $_POST['soLuong']; // Capture quantity from the form


    $gioHang = checkCart($con, $idNguoiDung);
    if (is_array($gioHang) && !empty($gioHang)) {
        echo ">>>>>>>>>>>> nguoi dung nay da co gio hang ";
        $idGioHang = $gioHang['id'];
        $soLuongInCart = $gioHang['so_luong_sp'];
        $cTGioHang = checkCartAndProduct($con, $idGioHang, $idSanPham);
    
        // Check if the product is already in the cart
        if (is_array($cTGioHang) && !empty($cTGioHang)) {
            echo "<br>" . ">>>>>>>>>>>> Người dùng đã thêm sp này vào ít nhất 1 lần rồi";
            $currentQuantity = $cTGioHang['so_luong'];
            $idCTGioHang = $cTGioHang['id'];
    
            // Update the quantity in the cart
            $currentQuantity += $soLuong;  // Use the submitted quantity
            $kq = updateCartDetail($con, $idCTGioHang, $currentQuantity);
        } else {
            echo "<br>" . ">>>>>>>>>>>> Người dùng chưa thêm sp này vào giỏ hàng";
            insertCartDetail($con, $idGioHang, $idSanPham, $soLuong, $giaSanPham); // Use the submitted quantity
            $soLuongInCart += $soLuong; // Update total quantity in cart
        }
        $kqud = updateCart($con, $idGioHang, $soLuongInCart);
    } else {
        echo ">>>>>>>>>>>> nguoi dung nay chưa co gio hang ";
        $idGioHang = insertCart($con, $idNguoiDung, $soLuong); // Start with the submitted quantity
        if (is_numeric($idGioHang)) {
            insertCartDetail($con, $idGioHang, $idSanPham, $soLuong, $giaSanPham); // Use the submitted quantity
        } else {
            echo "Loi khong tao moi dc gio hang";
        }
    } 
    header('Location: gioHang.php');
}
