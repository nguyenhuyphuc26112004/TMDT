<?php
// echo "hello from cart.php <br>";

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');
// require('../connectMysql.php');


function insertOrder($con, $idNguoiDung, $tongTien, $address, $sdt, $email, $name, $trangThai)
{
    // Câu lệnh SQL
    $sql = "INSERT INTO don_hang (id_nguoi_dung, tong_tien, dia_chi, sdt, email, ten,trang_thai) VALUES (?, ?, ?, ?, ?, ?, ?);";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("iisssss", $idNguoiDung, $tongTien, $address, $sdt, $email, $name, $trangThai);

        if ($stmt->execute()) {
            return $con->insert_id; // tra ve id cua gio hang vua tao moi
        } else {
            return "Lỗi không them duoc don hang vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}

function insertOrderDetail($con, $idDonHang, $idSanPham, $soLuong, $gia)
{
    // Câu lệnh SQL
    $sql = "INSERT INTO ct_don_hang (id_don_hang, id_san_pham, so_luong, gia) VALUES (?, ?, ?, ?);";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("iiii", $idDonHang, $idSanPham, $soLuong, $gia);

        if ($stmt->execute()) {
            return "đã thêm ct don hang vào database";
        } else {
            return "Lỗi không them duoc ct don hang vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}
