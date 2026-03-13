<?php
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

function saveUserAtAdmin($con, $idVaiTro, $hoVaTen, $gioiTinh, $email, $tenDangNhap, $matKhau)
{
    // Thêm các cột mới vào câu lệnh INSERT, đặt giá trị mặc định cho OTP và khóa tài khoản
    $sql = "INSERT INTO nguoi_dung (id_vai_tro, ho_ten, gioi_tinh, email, ten_dang_nhap, mat_khau, so_lan_sai, thoi_gian_khoa) 
            VALUES (?, ?, ?, ?, ?, ?, 0, NULL)";
            
    $stmt = $con->prepare($sql);
    if ($stmt) {
        // "isssss" ứng với: int, string, string, string, string, string
        $stmt->bind_param("isssss", $idVaiTro, $hoVaTen, $gioiTinh, $email, $tenDangNhap, $matKhau);
        if ($stmt->execute()) {
            return true;
        } else {
            return "Lỗi: " . $stmt->error;
        }
    } else {
        return "Lỗi câu lệnh SQL";
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
