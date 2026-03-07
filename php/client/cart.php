<?php

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

/**
 * NHÓM 1: KIỂM TRA (CHECK)
 */

// 1. Kiểm tra giỏ hàng của người dùng đã tồn tại chưa
function checkCart($con, $idNguoiDung)
{
    $sql = "SELECT * FROM gio_hang WHERE id_nguoi_dung = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idNguoiDung);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); 
    }
    return null;
}

// 2. Kiểm tra một sản phẩm cụ thể đã có trong giỏ hàng chưa
function checkCartAndProduct($con, $idGioHang, $idSanPham)
{
    $sql = "SELECT * FROM ct_gio_hang WHERE id_gio_hang = ? AND id_san_pham = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $idGioHang, $idSanPham);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * NHÓM 2: CHI TIẾT GIỎ HÀNG (CART DETAIL)
 */

// 3. Cập nhật số lượng cho một dòng sản phẩm đã có sẵn
function updateCartDetail($con, $idCTGioHang, $soLuong)
{
    $sql = "UPDATE ct_gio_hang SET so_luong = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $soLuong, $idCTGioHang);
        if ($stmt->execute()) {
            return "Đã cập nhật số lượng trong chi tiết giỏ hàng";
        }
        return "Lỗi: Không thể cập nhật chi tiết giỏ hàng";
    }
    return "Lỗi câu lệnh SQL";
}

// 4. Thêm một sản phẩm mới vào bảng chi tiết giỏ hàng
function insertCartDetail($con, $idGioHang, $idSanPham, $soLuong, $gia)
{
    $sql = "INSERT INTO ct_gio_hang (id_gio_hang, id_san_pham, so_luong, gia) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iiii", $idGioHang, $idSanPham, $soLuong, $gia);
        if ($stmt->execute()) {
            return "Đã thêm sản phẩm vào chi tiết giỏ hàng";
        }
        return "Lỗi: Không thể thêm sản phẩm vào chi tiết giỏ hàng";
    }
    return "Lỗi câu lệnh SQL";
}

// 5. Lấy danh sách tất cả sản phẩm trong một giỏ hàng
function getCartDetailByCart($con, $idGioHang)
{
    $sql = "SELECT * FROM ct_gio_hang WHERE id_gio_hang = ?";
    $cartDetails = [];
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idGioHang);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $cartDetails[] = $row;
        }
        return $cartDetails;
    }
    return [];
}

// 6. Xóa tất cả sản phẩm trong chi tiết giỏ hàng theo ID giỏ hàng
function deleteProductInCartDetail($con, $idGioHang)
{
    $sql = "DELETE FROM ct_gio_hang WHERE id_gio_hang = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idGioHang);
        if ($stmt->execute()) {
            return "Đã xóa thành công các sản phẩm trong chi tiết giỏ hàng";
        }
        return "Lỗi: Không thể xóa các sản phẩm";
    }
    return "Lỗi câu lệnh SQL";
}

/**
 * NHÓM 3: GIỎ HÀNG TỔNG (CART)
 */

// 7. Cập nhật tổng số lượng loại sản phẩm trong giỏ hàng chính
function updateCart($con, $idGioHang, $soLuong)
{
    $sql = "UPDATE gio_hang SET so_luong_sp = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $soLuong, $idGioHang);
        if ($stmt->execute()) {
            return "Đã cập nhật giỏ hàng chính";
        }
        return "Lỗi: Không thể cập nhật giỏ hàng chính";
    }
    return "Lỗi câu lệnh SQL";
}

// 8. Tạo mới một giỏ hàng cho người dùng
function insertCart($con, $idNguoiDung, $soLuong)
{
    $sql = "INSERT INTO gio_hang (id_nguoi_dung, so_luong_sp) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $idNguoiDung, $soLuong);
        if ($stmt->execute()) {
            return $con->insert_id; // Trả về ID của giỏ hàng vừa tạo
        }
        return "Lỗi: Không thể tạo giỏ hàng mới";
    }
    return "Lỗi câu lệnh SQL";
}

// 9. Reset số lượng sản phẩm trong giỏ hàng về 0 theo ID người dùng
function updateProductInCart($con, $idNguoiDung)
{
    $sql = "UPDATE gio_hang SET so_luong_sp = 0 WHERE id_nguoi_dung = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $idNguoiDung);
        if ($stmt->execute()) {
            return "Đã reset số lượng sản phẩm trong giỏ hàng về 0";
        }
        return "Lỗi: Không thể cập nhật giỏ hàng";
    }
    return "Lỗi câu lệnh SQL";
}

// 10. Lấy thông tin giỏ hàng tổng dựa trên ID giỏ hàng
function getCartById($con, $id)
{
    $sql = "SELECT * FROM gio_hang WHERE id = ?";
    $stmt = $con->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    return [];
}