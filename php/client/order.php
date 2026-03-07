<?php
// echo "hello from cart.php <br>";

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');
// require('../connectMysql.php');


function insertOrder($con, $idNguoiDung, $tongTien, $diaChi, $sdt, $email, $ten, $trangThai, $ptThanhToan) {
    $sql = "INSERT INTO don_hang (id_nguoi_dung, tong_tien, dia_chi, sdt, email, ten, trang_thai, pt_thanh_toan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("idssssss", $idNguoiDung, $tongTien, $diaChi, $sdt, $email, $ten, $trangThai, $ptThanhToan);
    if ($stmt->execute()) {
        return $con->insert_id;
    }
    return false;
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
// Thêm hàm này vào cuối file order.php
function updateProductStock($con, $idSanPham, $soLuongMua) {
    // Câu lệnh SQL trừ số lượng sản phẩm dựa trên ID
    $sql = "UPDATE `san_pham` 
            SET `so_luong` = `so_luong` - $soLuongMua 
            WHERE `id` = $idSanPham";
    
    return mysqli_query($con, $sql);
}

// Hàm hoàn trả số lượng vào kho khi hủy đơn
function refundStock($con, $idDonHang) {
    // 1. Lấy danh sách sản phẩm và số lượng từ chi tiết đơn hàng
    $sql_ct = "SELECT id_san_pham, so_luong FROM ct_don_hang WHERE id_don_hang = '$idDonHang'";
    $result = mysqli_query($con, $sql_ct);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $idSP = $row['id_san_pham'];
            $soLuongHoan = $row['so_luong'];

            // 2. Cộng lại số lượng vào bảng san_pham
            $sql_update = "UPDATE san_pham SET so_luong = so_luong + $soLuongHoan WHERE id = '$idSP'";
            mysqli_query($con, $sql_update);
            
            // 3. Nếu sản phẩm đang ở trạng thái 'Hết hàng' (0), cập nhật lại thành 'Còn hàng' (1)
            $sql_status = "UPDATE san_pham SET trang_thai = 1 WHERE id = '$idSP' AND so_luong > 0";
            mysqli_query($con, $sql_status);
        }
    }
}