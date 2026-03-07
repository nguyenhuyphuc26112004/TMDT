<?php
// File: /TMDT/php/admin/deleteObjectById.php

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

/**
 * 1. Hàm xóa đối tượng bất kỳ theo ID (Dùng cho bảng không có ràng buộc phức tạp)
 */
function deleteObjectById($con, $tableName, $id)
{
    $sql = "DELETE FROM $tableName WHERE id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return true;
        }
    }
    return false;
}

/**
 * 2. Hàm "Xóa" sản phẩm (Thực tế là ẩn sản phẩm bằng cách update trang_thai = 0)
 */
function deleteProductById($con, $id)
{
    $sql = "UPDATE san_pham SET trang_thai = 0 WHERE id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return "đã cập nhật product vào database";
        } else {
            return "Lỗi không cập nhật được product vào database";
        }
    }
    return "lỗi câu lệnh sql";
}

/**
 * 3. Hàm đếm TẤT CẢ đơn hàng của một người dùng
 * Dùng để chặn xóa khách hàng nếu họ vẫn còn dữ liệu đơn hàng
 */
function countAllOrders($con, $userId) {
    $sql = "SELECT COUNT(*) as total FROM don_hang WHERE id_nguoi_dung = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0;
}

/**
 * 4. Hàm đếm đơn hàng CHƯA HOÀN TẤT (Đang chờ duyệt, Đang giao...)
 */
function countPendingOrders($con, $userId) {
    $sql = "SELECT COUNT(*) as total FROM don_hang 
            WHERE id_nguoi_dung = ? 
            AND trang_thai NOT IN ('Đã nhận hàng', 'Đã hủy')";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0;
}

/**
 * 5. Hàm dọn dẹp Giỏ hàng của người dùng
 */
function deleteCartByUserId($con, $userId) {
    // Xóa chi tiết giỏ hàng trước để tránh lỗi khóa ngoại
    $sql1 = "DELETE FROM ct_gio_hang WHERE id_gio_hang IN (SELECT id FROM gio_hang WHERE id_nguoi_dung = ?)";
    $stmt1 = $con->prepare($sql1);
    $stmt1->bind_param("i", $userId);
    $stmt1->execute();

    // Xóa giỏ hàng chính
    $sql2 = "DELETE FROM gio_hang WHERE id_nguoi_dung = ?";
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
}

/**
 * 6. Hàm dọn dẹp Đơn hàng (Chỉ dùng khi bạn thực sự muốn xóa sạch đơn hàng của User đó)
 */
function deleteOrdersByUserId($con, $userId) {
    // Xóa chi tiết đơn hàng trước
    $sql1 = "DELETE FROM ct_don_hang WHERE id_don_hang IN (SELECT id FROM don_hang WHERE id_nguoi_dung = ?)";
    $stmt1 = $con->prepare($sql1);
    $stmt1->bind_param("i", $userId);
    $stmt1->execute();

    // Xóa đơn hàng chính
    $sql2 = "DELETE FROM don_hang WHERE id_nguoi_dung = ?";
    $stmt2 = $con->prepare($sql2);
    $stmt2->bind_param("i", $userId);
    $stmt2->execute();
}

/**
 * 7. Hàm xóa chi tiết của một đơn hàng cụ thể
 */
function deleteRelatedRecords2($con, $orderId) {
    $sql = "DELETE FROM ct_don_hang WHERE id_don_hang = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
    }
}
