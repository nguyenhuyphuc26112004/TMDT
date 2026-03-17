<?php
    // Giữ nguyên phần xử lý PHP đầu trang
    require('layout/header.php'); 
    require('php/client/cart.php');
    require('php/client/getObjectById.php');
    
    if(!isset($_SESSION['idNguoiDung'])) {
        echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='dangNhap.php';</script>";
        exit;
    }

    $idNguoiDung = $_SESSION['idNguoiDung'];
    $gioHang = checkCart($con, $idNguoiDung);
    
    if ($gioHang == null) {
        echo "<div style='text-align:center; padding:100px 0;'><h3>Giỏ hàng của bạn đang trống!</h3><a href='index.php'>Quay lại mua hàng</a></div>";
        exit;
    }

    $cTGioHang = getCartDetailByCart($con, $gioHang['id']);
    $maChuyenKhoan = "DH" . time(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán đơn hàng</title>
    <link rel="stylesheet" href="./css/trangDatHang.css">
    
    <style>
        .checkout-wrapper {
            width: 100%;
            clear: both;
            padding: 40px 0;
            background-color: #f8f9fa;
            min-height: 80vh;
            font-family: Arial, sans-serif;
        }

        .checkout-wrapper .form-info { 
            display: flex; 
            gap: 30px; 
            margin: 0 10%; 
        }

        .checkout-wrapper .info-left, 
        .checkout-wrapper .info-right { 
            flex: 1; 
            background: #fff; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        }

        .checkout-wrapper .error { color: red; display: none; font-size: 13px; margin-top: 5px; }
        .checkout-wrapper .info { margin-bottom: 15px; }
        .checkout-wrapper .info label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .checkout-wrapper .info input, 
        .checkout-wrapper .info textarea { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }

        .checkout-wrapper .cart-info { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px solid #eee; 
            align-items: center; 
        }

        .checkout-wrapper .tongCong { 
            font-size: 22px; 
            font-weight: bold; 
            margin-top: 20px; 
            color: #d9534f; 
            text-align: right; 
        }

        /* Vùng chứa mã QR */
        .checkout-wrapper #qr_code_area { 
            margin-top: 15px; 
            padding: 15px; 
            border: 1px dashed #007bff; 
            text-align: center; 
            display: none; /* Mặc định ẩn, hiện khi chọn ONLINE */
            background: #f0f7ff;
        }

        .checkout-wrapper .btn-dat-hang { 
            background: #28a745 !important;
            color: #fff !important; 
            border: none !important; 
            padding: 12px 30px !important; 
            width: auto !important; 
            min-width: 200px; 
            font-size: 16px !important; 
            font-weight: bold !important;
            border-radius: 25px !important; 
            cursor: pointer !important; 
            margin: 25px auto 0 auto;
            display: block !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(40, 167, 69, 0.2);
        }

        .checkout-wrapper .btn-dat-hang:hover { 
            background: #218838 !important; 
            transform: translateY(-2px); 
            box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
        }
    </style>
</head>
<body>

    <div class="checkout-wrapper">
        <form id="formXacNhan" action="thanhToan.php" method="post">
            <div class="form-info">
                <div class="info-left">
                    <h3>THÔNG TIN GIAO HÀNG</h3>
                    <div class="info">
                        <label>Họ và tên *</label>
                        <input type="text" id="name" name="name" placeholder="Nhập tên người nhận">
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
                        <span class="error" id="emailError">Vui lòng nhập email hợp lệ</span>
                    </div>
                    <div class="info">
                        <label>Địa chỉ nhận hàng *</label>
                        <textarea id="address" name="address" rows="3" placeholder="Địa chỉ chi tiết"></textarea>
                        <span class="error" id="addressError">Vui lòng nhập địa chỉ cụ thể</span>
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
                            <img src="admin/img/<?php echo $sp['anh'] ?>" style="width:50px; height:50px; object-fit:cover;">
                            <div>
                                <b><?php echo $sp['ten'] ?></b><br>
                                <small>x<?php echo $cTGH['so_luong'] ?></small>
                            </div>
                        </div>
                        <span><?php echo number_format($cTGH['so_luong'] * $cTGH['gia'], 0, ',', '.') ?>đ</span>
                    </div>
                    <?php endforeach; ?>

                    <div class="tongCong">Tổng cộng: <?php echo number_format($tongTien, 0, ',', '.') ?>đ</div>

                    <div class="payment-box" style="margin-top:20px; border:1px solid #eee; padding:15px; border-radius:8px;">
                        <b>Phương thức thanh toán *</b><br><br>
                        <label><input type="radio" name="payment_method" value="COD" checked onclick="toggleQR(false)"> Tiền mặt (COD)</label><br><br>
                        <label><input type="radio" name="payment_method" value="ONLINE" onclick="toggleQR(true)"> Chuyển khoản VietQR</label>
                        
                        <div id="qr_code_area">
                            <p style="font-size:13px; color:#555;">Quét mã bằng App Ngân hàng để thanh toán:</p>
                            <?php 
                                // Tạo URL VietQR an toàn hơn
                                $bankID = "MB";
                                $accountNo = "0368334112";
                                $accountName = urlencode("NGUYEN HUY PHUC");
                                $qrUrl = "https://img.vietqr.io/image/{$bankID}-{$accountNo}-compact2.png?amount={$tongTien}&addInfo={$maChuyenKhoan}&accountName={$accountName}";
                            ?>
                            <img src="<?php echo $qrUrl; ?>" style="width:100%; max-width:200px; border: 2px solid #fff;">
                            <p style="margin-top:10px;">Nội dung: <b style="color:blue;"><?php echo $maChuyenKhoan ?></b></p>
                        </div>
                    </div>

                    <div style="margin-top:20px;">
                        <label style="font-size:14px;"><input type="checkbox" id="terms"> Tôi đồng ý với điều khoản</label>
                        <span class="error" id="termsError">Vui lòng đồng ý với điều khoản</span>
                    </div>

                    <input type="hidden" name="tongTien" value="<?php echo $tongTien ?>">
                    <input type="hidden" name="ma_chuyen_khoan" value="<?php echo $maChuyenKhoan ?>">
                    
                    <button type="submit" name="btn_dat_hang" class="btn-dat-hang">XÁC NHẬN ĐẶT HÀNG</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleQR(show) {
            document.getElementById('qr_code_area').style.display = show ? "block" : "none";
        }

        document.getElementById('formXacNhan').addEventListener('submit', function(e) {
            let isValid = true;
            ['name', 'sdt', 'email', 'address'].forEach(id => {
                let el = document.getElementById(id);
                let err = document.getElementById(id + 'Error');
                if(el && el.value.trim() === "") { 
                    err.style.display = "block"; isValid = false; 
                } else if(err) { err.style.display = "none"; }
            });

            if(!document.getElementById('terms').checked) {
                document.getElementById('termsError').style.display = "block"; isValid = false;
            } else { document.getElementById('termsError').style.display = "none"; }

            if(!isValid) e.preventDefault();
        });
    </script>
</body>
</html>