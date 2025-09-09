<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/trangDatHang.css">
    <title>Trang đặt hàng</title>
    <style>
        .error {
            color: red;
            display: none;
        }
        .form-info{
            margin: 50px 300px;
        }
        .tongCong{
            font-size:20px;
            margin-top: 15px;
        }
        .tinh_tien{
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #dddddd;
            align-items: center;
            padding:0 15px;
        }
        .tinh_tien p{
            font-size:18px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <?php
        require('layout/header.php');
        require('php/client/cart.php');
        require('php/client/getObjectById.php');
        $idNguoiDung = $_SESSION['idNguoiDung'];
        $gioHang = checkCart($con, $idNguoiDung);
    if ($gioHang == null) {
        echo ">>>>>>>>>>>> không lấy được giỏ hàng";
    } else {
        // mảng lưu tất cả sp của người dùng 
        $cTGioHang = getCartDetailByCart($con, $gioHang['id']);
    ?>
    <form id="formXacNhan" action="thanhToan.php" method="post">
        <div class="form-info">
            <div class="info-left">
                <h3>THÔNG TIN THANH TOÁN & VẬN CHUYỂN</h3>
                <div class="info">
                    <label for="name"><b>Họ và tên *</b></label>
                    <input type="text" id="name" name="name" placeholder="Họ và tên của bạn">
                    <span class="error" id="nameError">Họ và tên không được để trống</span>
                </div>
                <div class="info">
                    <label for="sdt"><b>Phone *</b></label>
                    <input type="text" id="sdt" name="sdt" placeholder="Số điện thoại của bạn">
                    <span class="error" id="sdtError">Số điện thoại không được để trống</span>
                </div>
                <div class="info">
                    <label for="email"><b>Email address *</b></label>
                    <input type="text" id="email" name="email" placeholder="Email của bạn">
                    <span class="error" id="emailError">Email không được để trống</span>
                </div>
                <div class="info">
                    <label for="address"><b>Địa chỉ nhận hàng *</b></label>
                    <input type="text" id="address" name="address" placeholder="Địa chỉ nhận hàng của bạn">
                    <span class="error" id="addressError">Địa chỉ nhận hàng không được để trống</span>
                </div>
                <div class="info">
                    <label for=""><b>Thông tin bổ sung</b></label>
                    <textarea id="" placeholder="Thêm thông tin bổ sung"></textarea>
                </div>
            </div>
            <div class="info-right">
                <h3>ĐƠN HÀNG CỦA BẠN</h3>
                <div class="cart-info">
                    <div class="product-name">
                        <b>SẢN PHẨM</b>
                    </div>
                    <div class="product-total">
                        <b>THÀNH TIỀN</b>
                    </div>
                </div>
                <?php
                    $tongTien = 0;
                    foreach ($cTGioHang as $cTGH) {
                        $tongTien += ($cTGH['so_luong'] * $cTGH['gia']);
                            // lấy sp thông qua id sản phẩm
                        $sp = getObjectById($con, 'san_pham', $cTGH['id_san_pham']);
                ?>
                <div class="cart-info">
                    <div class="product-name">
                        <img src="admin/img/<?php echo $sp['anh'] ?>" alt=""> 
                        <b><?php echo $sp['ten'] ?></b>
                    </div>
                    <div class="product-total">
                        <b><?php echo number_format ($cTGH['gia'],0,',' , '.') . ' VNĐ'; ?></b>
                    </div>
                </div>
                <div class="cart-info">
                    <div class="product-name">
                        <b>Số lượng</b>
                    </div>
                    <div class="product-total">
                        <b>x <?php echo $cTGH['so_luong'] ?></b>
                    </div>
                </div>
                <div class="cart-info">
                    <div class="product-name">
                        <b>Thành tiền</b>
                    </div>
                    <div class="product-total">
                        <b><?php echo number_format($cTGH['so_luong'] * $cTGH['gia'], 0, ',', '.') . ' VNĐ'; ?></b>
                    </div>
                </div>
                <?php
                    } 
                ?>
                <div class="tongCong">
                    <b>Tổng cộng</b>
                </div>
                <div class="tinh_tien">
                    <p style = "color: red" >Thành tiền</p>
                    <p style = "color: red"><?php echo number_format($tongTien, 0, ',', '.') ?> VNĐ</p>
                </div>
                <div class="payment">
                    <b>Phương thức thanh toán</b> <br>
                    <input type="radio" name="" id="">Trả tiền mặt khi nhận hàng
                </div>
                <div class="agree">
                    <label>
                        <input type="checkbox" name="terms" value="accepted">
                        Tôi đã đọc và đồng ý với các điều khoản và điều kiện của trang web
                    </label>
                </div>
                <div class="btn">
                    <input type="hidden" name="tongTien" value="<?php echo $tongTien ?>">
                    <button type="submit">Đặt hàng</button>
                </div>
            </div>
        </div>
    </form>
    <?php
    }
    ?>
    <script>
        var formDatHang = document.getElementById('formDatHang');
        var sdt = document.getElementById('sdt');
        var email = document.getElementById('email');
        var address = document.getElementById('address');
        var name = document.getElementById('name');
    
        var nameError = document.getElementById('nameError');
        var sdtError = document.getElementById('sdtError');
        var emailError = document.getElementById('emailError'); // Ensure this ID exists in the HTML
        var addressError = document.getElementById('addressError');
    
        formDatHang.addEventListener("submit", function(e){
            var check = true;
            if (name.value.trim() === "") { 
                check = false; 
                nameError.style.display = "block";
            } else { 
                nameError.style.display = "none";
            }
            
            if (sdt.value.trim() === "") { 
                check = false; 
                sdtError.style.display = "block";
            } else { 
                sdtError.style.display = "none";
            }
            if (email.value.trim() === "") { 
                check = false; 
                emailError.style.display = "block";
            } else { 
                emailError.style.display = "none";
            }
            if (address.value.trim() === "") { 
                check = false; 
                addressError.style.display = "block";
            } else { 
                addressError.style.display = "none";
            }
            if (!check) {
                e.preventDefault(); // Prevent form submission if there are errors
            }
        });
    </script>
</body>
</html>