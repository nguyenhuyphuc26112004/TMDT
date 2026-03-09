<?php
    require('layout/header.php');
    require('php/client/cart.php');
    require('php/client/getObjectById.php');
    
    if(!isset($_SESSION['idNguoiDung'])) {
        echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='login.php';</script>";
        exit;
    }

    $idNguoiDung = $_SESSION['idNguoiDung'];
    $gioHang = checkCart($con, $idNguoiDung);
    
    if ($gioHang == null) {
        echo "<div style='text-align:center; padding:50px;'><h3>Giỏ hàng của bạn đang trống!</h3></div>";
    } else {
        $cTGioHang = getCartDetailByCart($con, $gioHang['id']);
        $maChuyenKhoan = "DH" . time(); // Tạo mã nội dung chuyển khoản duy nhất
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/trangDatHang.css">
    <title>Thanh toán đơn hàng</title>
    <style>
        .error { color: red; display: none; font-size: 13px; margin-top: 5px; }
        .form-info { display: flex; gap: 30px; margin: 30px 10%; }
        .info-left, .info-right { flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .info { margin-bottom: 15px; }
        .info input, .info textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .cart-info { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; align-items: center; }
        .cart-info img { width: 50px; height: 50px; object-fit: cover; }
        .tongCong { font-size: 20px; font-weight: bold; margin-top: 20px; color: red; text-align: right; }
        .payment-box { margin-top: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px; }
        #qr_code_area { margin-top: 15px; background: #fdfdfd; padding: 15px; border: 1px dashed #007bff; text-align: center; display: none; }
        button[type="submit"] { background: #28a745; color: #fff; border: none; padding: 15px; width: 100%; font-size: 18px; border-radius: 5px; cursor: pointer; margin-top: 20px; }
    </style>
</head>
<body>
    <form id="formXacNhan" action="thanhToan.php" method="post">
        <div class="form-info">
            <div class="info-left">
                <h3>THÔNG TIN GIAO HÀNG</h3>
                <div class="info">
                    <label>Họ và tên *</label>
                    <input type="text" id="name" name="name" placeholder="Nhập họ tên">
                    <span class="error" id="nameError">Vui lòng nhập họ tên</span>
                </div>
                <div class="info">
                    <label>Số điện thoại *</label>
                    <input type="text" id="sdt" name="sdt" placeholder="Nhập số điện thoại">
                    <span class="error" id="sdtError">Vui lòng nhập số điện thoại</span>
                </div>
                <div class="info">
                    <label>Email *</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email">
                    <span class="error" id="emailError">Email không hợp lệ</span>
                </div>
                <div class="info">
                    <label>Địa chỉ nhận hàng *</label>
                    <textarea id="address" name="address" rows="3" placeholder="Địa chỉ chi tiết"></textarea>
                    <span class="error" id="addressError">Vui lòng nhập địa chỉ</span>
                </div>
            </div>

            <div class="info-right">
                <h3>ĐƠN HÀNG CỦA BẠN</h3>
                <?php
                    $tongTien = 0;
                    foreach ($cTGioHang as $cTGH) :
                        $tongTien += ($cTGH['so_luong'] * $cTGH['gia']);
                        $sp = getObjectById($con, 'san_pham', $cTGH['id_san_pham']);
                ?>
                <div class="cart-info">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="admin/img/<?php echo $sp['anh'] ?>">
                        <b><?php echo $sp['ten'] ?> x<?php echo $cTGH['so_luong'] ?></b>
                    </div>
                    <span><?php echo number_format($cTGH['so_luong'] * $cTGH['gia'], 0, ',', '.') ?>đ</span>
                </div>
                <?php ; ?>

                <div class="tongCong">Tổng: <?php echo number_format($tongTien, 0, ',', '.') ?> VNĐ</div>

                <div class="payment-box">
                    <b>Phương thức thanh toán *</b><br><br>
                    <input type="radio" name="payment_method" value="COD" checked onclick="toggleQR(false)"> Tiền mặt khi nhận hàng (COD)<br><br>
                    <input type="radio" name="payment_method" value="ONLINE" onclick="toggleQR(true)"> Chuyển khoản VietQR (Ngân hàng)<br>
                    
                    <div id="qr_code_area">
                        <p style="font-size: 14px;">Mở App ngân hàng quét mã để thanh toán nhanh:</p>
                        <?php
                            $BANK_ID = "MB"; // Ngân hàng (MB, VCB, TCB...)
                            $ACC_NO = "0368334112"; // SỐ TÀI KHOẢN CỦA BẠN
                            $ACC_NAME = "NGUYEN HUY PHUC"; // TÊN CỦA BẠN
                            $qr_url = "https://img.vietqr.io/image/{$BANK_ID}-{$ACC_NO}-compact2.png?amount={$tongTien}&addInfo={$maChuyenKhoan}&accountName={$ACC_NAME}";
                        ?>
                        <img src="<?php echo $qr_url ?>" style="width: 200px; border: 5px solid #fff;">
                        <p>Nội dung: <b style="color:blue;"><?php echo $maChuyenKhoan ?></b></p>
                        <input type="hidden" name="ma_chuyen_khoan" value="<?php echo $maChuyenKhoan ?>">
                    </div>
                </div>

                <div style="margin-top:15px;">
                    <label><input type="checkbox" id="terms"> Tôi đồng ý với điều khoản dịch vụ</label>
                    <span class="error" id="termsError">Bạn cần đồng ý điều khoản</span>
                </div>

                <input type="hidden" name="tongTien" value="<?php echo $tongTien ?>">
                <button type="submit">XÁC NHẬN ĐẶT HÀNG</button>
            </div>
        </div>
    </form>

    <script>
        function toggleQR(show) {
            document.getElementById('qr_code_area').style.display = show ? "block" : "none";
        }

        document.getElementById('formXacNhan').addEventListener('submit', function(e) {
            let isValid = true;
            ['name', 'sdt', 'email', 'address'].forEach(id => {
                let el = document.getElementById(id);
                let err = document.getElementById(id + 'Error');
                if(el.value.trim() === "") { 
                    err.style.display = "block"; isValid = false; 
                } else { err.style.display = "none"; }
            });

            if(!document.getElementById('terms').checked) {
                document.getElementById('termsError').style.display = "block"; isValid = false;
            } else { document.getElementById('termsError').style.display = "none"; }

            if(!isValid) e.preventDefault();
        });
    </script>
</body>
</html>
<?php } ?>