<?php
// echo "hello from deleteObjectById.php <br>";

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

// $con thuộc file connectMysql.php
// hàm

function deleteObjectById($con, $tableName, $id)
{
    // Câu lệnh SQL
    $sql = "DELETE FROM $tableName WHERE id = ? ";

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // gán các tham số vào câu lệnh
        $stmt->bind_param("i", $id);

        // Thực thi câu lệnh SQL
        $stmt->execute();

        return true;
    }

    // Trả về mảng rỗng nếu có lỗi
    return false;
}


function deleteProductById($con, $id,)
{
    // update trangj tthai ve 0 chu ko xoa sp

    // sql
    $sql = "UPDATE san_pham SET trang_thai = 0  WHERE id = ? ";

    // chuan bị cau lenh sql
    $stmt = $con->prepare($sql); // true nếu sẵn sàng

    if ($stmt) {

        // gắn các tham số cho câu lệnh
        $stmt->bind_param("i", $id);

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
// function deleteRelatedRecords($con, $userId) {
//     $sql = "DELETE FROM gio_hang WHERE id_nguoi_dung = ?";
//     $stmt = $con->prepare($sql);
//     if ($stmt) {
//         $stmt->bind_param("i", $userId);
//         $stmt->execute();
//     }
// }
function deleteRelatedRecords($con, $userId) {
    // Delete from ct_gio_hang first
    $sql = "DELETE FROM ct_gio_hang WHERE id_gio_hang IN (SELECT id FROM gio_hang WHERE id_nguoi_dung = ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    // Now delete from gio_hang
    $sql = "DELETE FROM gio_hang WHERE id_nguoi_dung = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }
}
function deleteRelatedRecords2($con, $orderId) {
    $sql = "DELETE FROM ct_don_hang WHERE id_don_hang = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
    }
}
// In '../php/admin/deleteObjectById.php'

?>
