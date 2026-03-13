<?php
// echo "hello from updateObjectById.php <br>";
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

function hello()
{
    return "hello from updateObjectById.php";
}

function updateUserById($con, $id, $hoVaTen, $idVaiTro, $email, $gioiTinh)
{
    // 1. Sửa câu lệnh SQL: đổi so_dien_thoai thành email
    $sql = "UPDATE nguoi_dung SET ho_ten = ?, id_vai_tro = ?, email = ?, gioi_tinh = ? WHERE id = ?";
    
    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {
        
        $stmt->bind_param("sissi", $hoVaTen, $idVaiTro, $email, $gioiTinh, $id);

        // Thực thi câu lệnh 
        if ($stmt->execute()) {
            return "đã cập nhật user vào database";
        } else {
            return "Lỗi không cập nhật được user vào database: " . $stmt->error;
        }
    } else {
        return "Lỗi câu lệnh sql";
    }
}

function updateProductById($con, $id, $ten, $loai, $don_vi, $so_luong, $gia, $anh, $trangThai)
{

    // sql
    $sql = "UPDATE san_pham SET ten = ? , loai = ? , don_vi = ?,  so_luong = ? , gia = ?  , anh = ? , trang_thai = ?  WHERE id = ? ";

    // chuan bị cau lenh sql
    $stmt = $con->prepare($sql); // true nếu sẵn sàng

    if ($stmt) {

        // gắn các tham số cho câu lệnh
        $stmt->bind_param("sssiisii", $ten, $loai, $don_vi, $so_luong, $gia,  $anh, $trangThai, $id);

        // thuc thi câu lệnh
        if ($stmt->execute()) {
            return "đã cập nhật product vào database";
        } else {
            return "Lỗi không cập nhật được product vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}
// File: ../php/admin/updateObjectById.php

function updateOrderById($con, $id, $diaChi, $trangThai, $trangThaiThanhToan) {
    // Logic tự động: Nếu vận chuyển là 'Đã nhận hàng', ép trạng thái thanh toán thành 'Đã thanh toán'
    if ($trangThai === 'Đã nhận hàng') {
        $trangThaiThanhToan = 'Đã thanh toán';
    }

    $sql = "UPDATE don_hang SET 
            dia_chi = ?, 
            trang_thai = ?, 
            trang_thai_thanh_toan = ? 
            WHERE id = ?";
            
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssi", $diaChi, $trangThai, $trangThaiThanhToan, $id);
        return $stmt->execute(); // Trả về true/false để bên gọi (capNhat_DH.php) biết mà redirect
    }
    return false;
}