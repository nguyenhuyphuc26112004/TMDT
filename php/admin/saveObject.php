<?php
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

function saveUserAtAdmin($con, $idVaiTro,  $hoVaTen, $gioiTinh, $soDienThoai,  $tenDangNhap, $matKhau)
{
    $sql = "INSERT INTO nguoi_dung (id_vai_tro, ho_ten, gioi_tinh, so_dien_thoai, ten_dang_nhap, mat_khau ) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isssss", $idVaiTro, $hoVaTen, $gioiTinh, $soDienThoai,  $tenDangNhap, $matKhau);
        if ($stmt->execute()) {
            return "đã lưu user vào database";
        } else {
            return "Lỗi không lưu được user vào database";
        }
    } else {
        return "Lỗi câu lệnh sql ";
    }
}

function saveProduct($con, $ten, $loai, $don_vi, $soLuong, $gia, $anh)
{
    $sql = "INSERT INTO san_pham (ten, loai, don_vi, so_luong, gia, anh) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssiis", $ten, $loai, $don_vi, $soLuong, $gia,  $anh);
        if ($stmt->execute()) {
            return "đã lưu product vào database";
        } else {
            return "Lỗi không lưu được product vào database";
        }
    } else {
        return "Lỗi câu lệnh sql ";
    }
}
