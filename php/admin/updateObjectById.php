<?php
// echo "hello from updateObjectById.php <br>";
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

function hello()
{
    return "hello from updateObjectById.php";
}

function updateUserById($con, $id, $hoVaTen, $idVaiTro, $soDienThoai, $gioiTinh)
{
    $sql = "UPDATE nguoi_dung SET ho_ten = ? , id_vai_tro = ? , so_dien_thoai = ? , gioi_tinh = ? Where id = ?";
    // chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // ktra câu lệnh đã săn sàng chưa
    if ($stmt) {

        // đã sẵn sàng
        // gán các tham số vào câu lệnh
        $stmt->bind_param("sissi", $hoVaTen, $idVaiTro, $soDienThoai, $gioiTinh,  $id);

        // thực thi câu lệnh 
        if ($stmt->execute()) {
            return "đã cập nhật user vào database";
        } else {
            return "Lỗi không cập nhật được user vào database";
        }
    } else {
        return "Lỗi câu lệnh sql ";
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

function updateOrderById($con, $id, $diaChi, $trangThai)
{

    // sql
    $sql = "UPDATE don_hang SET dia_chi = ? , trang_thai = ? WHERE id = ? ";

    // chuan bị cau lenh sql
    $stmt = $con->prepare($sql); // true nếu sẵn sàng

    if ($stmt) {

        // gắn các tham số cho câu lệnh
        $stmt->bind_param("ssi", $diaChi, $trangThai, $id);

        // thuc thi câu lệnh
        if ($stmt->execute()) {
            return "đã cập nhật Order vào database";
        } else {
            return "Lỗi không cập nhật được Order vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}
