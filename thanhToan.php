<?php
// Kiểm tra người dùng đăng nhập hay chưa
require('php/checkSession.php');
require('php/client/order.php'); // Chứa insertOrder, insertOrderDetail, updateProductStock
require('php/client/cart.php');
require('php/client/getObjectById.php');
checkSessionClient();

// 1. Lấy thông tin từ Form gửi lên
$idNguoiDung = $_SESSION['idNguoiDung'];
$name        = mysqli_real_escape_string($con, $_POST['name']);
$sdt         = mysqli_real_escape_string($con, $_POST['sdt']);
$email       = mysqli_real_escape_string($con, $_POST['email']);
$address     = mysqli_real_escape_string($con, $_POST['address']);
$tongTien    = (float)$_POST['tongTien'];
$trangThai   = 'Đang chờ duyệt';

$method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'COD';
$ma_ck  = isset($_POST['ma_chuyen_khoan']) ? mysqli_real_escape_string($con, $_POST['ma_chuyen_khoan']) : null;

if ($method == 'ONLINE') {
    $pt_thanh_toan = 'ONLINE';
    $trang_thai_tt = 'Chờ xác nhận tiền'; // Khách đã quét mã nhưng Admin cần check Bank
} else {
    $pt_thanh_toan = 'COD';
    $trang_thai_tt = 'Chưa thanh toán';
}

// 2. Lấy thông tin giỏ hàng
$gioHang = checkCart($con, $idNguoiDung);
if (!$gioHang) {
    header('Location: gioHang.php');
    exit;
}
$cTGioHang = getCartDetailByCart($con, $gioHang['id']);

// --- BƯỚC 1: KIỂM TRA TỒN KHO TRƯỚC KHI LÀM BẤT CỨ GÌ ---
foreach ($cTGioHang as $cTGH) {
    $sp = getObjectById($con, 'san_pham', $cTGH['id_san_pham']);
    if ($sp['so_luong'] < $cTGH['so_luong']) {
        echo "<script>
                alert('Sản phẩm " . $sp['ten'] . " không đủ tồn kho (Còn: " . $sp['so_luong'] . ").');
                window.location.href = 'gioHang.php';
              </script>";
        exit;
    }
}

// --- BƯỚC 2: TIẾN HÀNH LƯU DỮ LIỆU (Sử dụng Transaction) ---
mysqli_begin_transaction($con);

try {
    // A. Tạo đơn hàng tổng quát
    $sql_dh = "INSERT INTO don_hang (id_nguoi_dung, tong_tien, dia_chi, email, sdt, ten, trang_thai, pt_thanh_toan, trang_thai_thanh_toan, ma_chuyen_khoan, ngay_dat) 
               VALUES ('$idNguoiDung', '$tongTien', '$address', '$email', '$sdt', '$name', '$trangThai', '$pt_thanh_toan', '$trang_thai_tt', '$ma_ck', NOW())";
    
    if (!mysqli_query($con, $sql_dh)) {
        throw new Exception("Lỗi tạo đơn hàng: " . mysqli_error($con));
    }
    
    $idDonHang = mysqli_insert_id($con);

    // B. Duyệt qua từng sản phẩm trong giỏ hàng
    foreach ($cTGioHang as $cTGH) {
        $idSanPham  = $cTGH['id_san_pham'];
        $soLuongMua = $cTGH['so_luong'];
        $giaHienTai = $cTGH['gia'];

        // 1. Thêm vào chi tiết đơn hàng
        $sql_ct = "INSERT INTO ct_don_hang (id_don_hang, id_san_pham, so_luong, gia) 
                   VALUES ('$idDonHang', '$idSanPham', '$soLuongMua', '$giaHienTai')";
        if (!mysqli_query($con, $sql_ct)) {
            throw new Exception("Lỗi lưu chi tiết đơn hàng.");
        }

        // 2. Thực hiện trừ số lượng trong bảng san_pham
        $sql_update_stock = "UPDATE san_pham SET so_luong = so_luong - $soLuongMua WHERE id = '$idSanPham'";
        if (!mysqli_query($con, $sql_update_stock)) {
            throw new Exception("Lỗi cập nhật kho hàng.");
        }
        
        // 3. Cập nhật trạng thái hết hàng nếu số lượng về 0
        $sql_check_stock = "SELECT so_luong FROM san_pham WHERE id = '$idSanPham'";
        $res_stock = mysqli_query($con, $sql_check_stock);
        $row_stock = mysqli_fetch_assoc($res_stock);
        if ($row_stock['so_luong'] <= 0) {
            mysqli_query($con, "UPDATE san_pham SET trang_thai = 0 WHERE id = '$idSanPham'");
        }
    }

    // C. Xóa giỏ hàng sau khi đặt thành công
    $idGioHang = $gioHang['id'];
    mysqli_query($con, "DELETE FROM ct_gio_hang WHERE id_gio_hang = '$idGioHang'");
    mysqli_query($con, "UPDATE gio_hang SET so_luong_sp = 0 WHERE id = '$idGioHang'");

    // Hoàn tất giao dịch
    mysqli_commit($con);

    // 6. Chuyển hướng thành công
    header('Location: trangChu.php?status=success&order_id=' . $idDonHang);

} catch (Exception $e) {
    // Nếu có bất kỳ lỗi nào, hủy bỏ toàn bộ thay đổi
    mysqli_rollback($con);
    echo "Lỗi hệ thống: " . $e->getMessage();
}
?>