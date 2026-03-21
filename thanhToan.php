<?php
session_start();
require('php/connectMysql.php'); // Đảm bảo đã có file kết nối DB
require('php/checkSession.php');
require('php/client/order.php'); 
require('php/client/cart.php');
require('php/client/getObjectById.php');

// Nạp thư viện PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

date_default_timezone_set('Asia/Ho_Chi_Minh');
checkSessionClient();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_dat_hang'])) {
    // 1. Lấy thông tin từ Form
    $idNguoiDung = $_SESSION['idNguoiDung'];
    $name        = mysqli_real_escape_string($con, $_POST['name']);
    $sdt         = mysqli_real_escape_string($con, $_POST['sdt']);
    $email       = mysqli_real_escape_string($con, $_POST['email']);
    $address     = mysqli_real_escape_string($con, $_POST['address']);
    $tongTien    = (float)$_POST['tongTien'];
    $method      = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'COD';
    $ma_ck       = isset($_POST['ma_chuyen_khoan']) ? mysqli_real_escape_string($con, $_POST['ma_chuyen_khoan']) : null;

    $pt_thanh_toan = ($method == 'ONLINE') ? 'ONLINE' : 'COD';
    $trang_thai_tt = ($method == 'ONLINE') ? 'Chờ xác nhận tiền' : 'Chưa thanh toán';

    // 2. Kiểm tra giỏ hàng
    $gioHang = checkCart($con, $idNguoiDung);
    if (!$gioHang) {
        header('Location: gioHang.php');
        exit;
    }
    $cTGioHang = getCartDetailByCart($con, $gioHang['id']);

    // --- BƯỚC 1: KIỂM TRA TỒN KHO ---
    foreach ($cTGioHang as $cTGH) {
        $sp = getObjectById($con, 'san_pham', $cTGH['id_san_pham']);
        if ($sp['so_luong'] < $cTGH['so_luong']) {
            echo "<script>alert('Sản phẩm " . $sp['ten'] . " không đủ tồn kho!'); window.location.href = 'gioHang.php';</script>";
            exit;
        }
    }

    // --- BƯỚC 2: TRANSACTION LƯU DB ---
    mysqli_begin_transaction($con);
    try {
        $sql_dh = "INSERT INTO don_hang (id_nguoi_dung, tong_tien, dia_chi, email, sdt, ten, trang_thai, pt_thanh_toan, trang_thai_thanh_toan, ma_chuyen_khoan, ngay_dat) 
                   VALUES ('$idNguoiDung', '$tongTien', '$address', '$email', '$sdt', '$name', 'Đang chờ duyệt', '$pt_thanh_toan', '$trang_thai_tt', '$ma_ck', NOW())";
        
        if (!mysqli_query($con, $sql_dh)) throw new Exception("Lỗi tạo đơn hàng");
        $idDonHang = mysqli_insert_id($con);

        $listSP_Email = ""; // Chuỗi HTML để hiện danh sách SP trong email
        foreach ($cTGioHang as $cTGH) {
            $idSanPham  = $cTGH['id_san_pham'];
            $soLuongMua = $cTGH['so_luong'];
            $giaHienTai = $cTGH['gia'];
            $spInfo = getObjectById($con, 'san_pham', $idSanPham);

            // Lưu chi tiết
            mysqli_query($con, "INSERT INTO ct_don_hang (id_don_hang, id_san_pham, so_luong, gia) VALUES ('$idDonHang', '$idSanPham', '$soLuongMua', '$giaHienTai')");
            // Cập nhật kho
            mysqli_query($con, "UPDATE san_pham SET so_luong = so_luong - $soLuongMua WHERE id = '$idSanPham'");
            
            // Nối chuỗi cho email
            $listSP_Email .= "<li>{$spInfo['ten']} - SL: $soLuongMua - Giá: ".number_format($giaHienTai)."đ</li>";
        }

        // Xóa giỏ
        mysqli_query($con, "DELETE FROM ct_gio_hang WHERE id_gio_hang = '{$gioHang['id']}'");
        mysqli_query($con, "UPDATE gio_hang SET so_luong_sp = 0 WHERE id = '{$gioHang['id']}'");

        mysqli_commit($con);

        // --- BƯỚC 3: GỬI EMAIL ---
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'phuc40965@gmail.com'; // Email gửi
            $mail->Password   = 'snyoshuogsuhmhwv';    // Mật khẩu ứng dụng
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('phuc40965@gmail.com', 'Hệ thống TMDT');
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "Đặt hàng thành công #$idDonHang";
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee;'>
                    <h2 style='color: #28a745;'>Thông báo đặt hàng thành công!</h2>
                    <p>Chào <b>$name</b>,</p>
                    <p>Cảm ơn bạn đã ủng hộ. Đơn hàng <b>#$idDonHang</b> của bạn đang được xử lý.</p>
                    <hr>
                    <p><b>Danh sách sản phẩm:</b></p>
                    <ul>$listSP_Email</ul>
                    <p><b>Tổng thanh toán:</b> <span style='color:red;'>".number_format($tongTien)."đ</span></p>
                    <p><b>Phương thức:</b> $pt_thanh_toan</p>
                    <p><b>Địa chỉ nhận hàng:</b> $address</p>
                    <hr>
                    <p style='font-size: 12px; color: #888;'>Chúng tôi sẽ sớm liên hệ qua SĐT $sdt để giao hàng.</p>
                </div>";
            $mail->send();
        } catch (Exception $e) {
            error_log("Email error: " . $mail->ErrorInfo);
        }

        header('Location: donHang.php?msg=success&id=' . $idDonHang);

    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "Lỗi hệ thống: " . $e->getMessage();
    }
}
?>