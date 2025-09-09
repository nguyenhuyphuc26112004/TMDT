<?php
// echo "hello from cart.php <br>";

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');
// require('../connectMysql.php');
function checkCart($con, $idNguoiDung)
{
    // Câu lệnh SQL
    $sql = "SELECT * FROM gio_hang WHERE id_nguoi_dung = ?";
    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("i", $idNguoiDung);

        // Thực thi
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Trả về bản ghi đầu tiên nếu có
        return $result->fetch_assoc(); // Trả về mảng kết hợp
    }

    // Trả về null nếu có lỗi
    return null;
}

function checkCartAndProduct($con, $idGioHang, $idSanPham)
{
    // Câu lệnh SQL
    $sql = "SELECT * FROM ct_gio_hang WHERE id_gio_hang = ? And id_san_pham = ?";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("ii", $idGioHang, $idSanPham);

        // Thực thi
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Trả về bản ghi đầu tiên nếu có
        return $result->fetch_assoc(); // Trả về mảng kết hợp
    }

    // Trả về null nếu có lỗi
    return null;
}

// CART DETAIL
function updateCartDetail($con, $idCTGioHang, $soLuong)
{
    // Câu lệnh SQL
    $sql = "UPDATE ct_gio_hang SET so_luong = ? WHERE id = ? ";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("ii", $soLuong, $idCTGioHang);

        if ($stmt->execute()) {
            return "đã cập nhật CT_gio_hang vào database";
        } else {
            return "Lỗi không cập nhật được CT_gio_hang vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}

function insertCartDetail($con, $idGioHang, $idSanPham, $soLuong, $gia)
{
    // Câu lệnh SQL
    $sql = "INSERT INTO ct_gio_hang (id_gio_hang, id_san_pham, so_luong, gia) VALUES (?, ?, ?, ?);";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("iiii", $idGioHang, $idSanPham, $soLuong, $gia);

        if ($stmt->execute()) {
            return "đã thêmthêm CT_gio_hang vào database";
        } else {
            return "Lỗi không thêmthêm được CT_gio_hang vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}


function getCartDetailByCart($con, $idGioHang)
{
    $sql = "SELECT * FROM ct_gio_hang WHERE id_gio_hang = ?";
    // Mảng lưu kết quả
    $cartDetails = [];

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // gán các tham số
        $stmt->bind_param("i", $idGioHang);
        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Hứng dữ liệu từ câu truy vấn SQL
        while ($cartDetail = $result->fetch_assoc()) {
            // Thêm mỗi người dùng vào mảng
            $cartDetails[] = $cartDetail;
        }

        // Trả về mảng chứa tất cả người dùng
        return $cartDetails;
    }

    // Trả về mảng rỗng nếu có lỗi
    return [];
}

function deleteProductInCartDetail($con, $idGioHang)
{
    $sql = "DELETE FROM ct_gio_hang WHERE id_gio_hang = ?;";
    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {
        // gán các tham số
        $stmt->bind_param("i", $idGioHang);

        if ($stmt->execute()) {
            return "đã xóa thành con các sp trong ct gio hang";
        } else {
            return "Lỗi không thể xóa các sp trong ct gio hang ";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}

// CART
function updateCart($con, $idGioHang, $soLuong)
{
    // Câu lệnh SQL 
    $sql = "UPDATE gio_hang SET so_luong_sp = ? WHERE id = ? ";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("ii", $soLuong, $idGioHang);

        if ($stmt->execute()) {
            return "đã cập nhật gio_hang vào database";
        } else {
            return "Lỗi không cập nhật được gio_hang vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}

function insertCart($con, $idNguoiDung, $soLuong)
{
    // Câu lệnh SQL
    $sql = "INSERT INTO gio_hang (id_nguoi_dung, so_luong_sp) VALUES (?, ?);";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Gán tham số
        $stmt->bind_param("ii", $idNguoiDung, $soLuong);

        if ($stmt->execute()) {
            //đã thêm gio_hang vào database
            return $con->insert_id; // tra ve id cua gio hang vua tao moi
        } else {
            return "Lỗi không thêm được gio_hang vào database";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}

function updateProductInCart($con, $idNguoiDung)
{
    $sql = "UPDATE gio_hang SET so_luong_sp = 0 WHERE id_nguoi_dung = ?;";
    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {
        // Gán tham số
        $stmt->bind_param("i", $idNguoiDung);

        if ($stmt->execute()) {
            return "đã update thành con các sp trong gio hang";
        } else {
            return "Lỗi không thể update các sp trong gio hang ";
        }
    } else {
        return "lỗi câu lệnh sql";
    }
}

function getCartById($con, $id)
{
    $sql = "SELECT * FROM gio_hang WHERE id = ?";
    // Mảng lưu kết quả
    $cart = [];

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // gán các tham số
        $stmt->bind_param("i", $id);
        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Trả về mảng rỗng nếu có lỗi
    return [];
}
