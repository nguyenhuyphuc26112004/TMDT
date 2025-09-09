<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Document</title>
</head>
<body>
    <div class="header">
        <div class="info-header">
            <div class="left-info-header">
                Chào mừng bạn ghé thăm shop
            </div>
            <div class="right-info-header">
                <div class="right-info">
                    <i class="fa-solid fa-circle-user"></i>
                    <p>Tài khoản</p>
                    <div class="log">
                        <?php
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start(); // Chỉ gọi session_start nếu chưa có session nào được khởi động
                            }
                            if (!isset($_SESSION['tenDangNhap'])){
                        ?>
                                <div class="login"><a href="dangNhap.php">Đăng nhập</a></div>
                                <div class="login"><a href="dangKy.php">Đăng ký</a></div>
                        <?php
                            } 
                            else 
                            { 
                        ?>
                            <div class="login"><a href="doiMatKhau.php">Đổi mật khẩu </a></div>
                            <div class="login"><a href="gioHang.php">Giỏ hàng</a></div>
                            <div class="login"><a href="donHang.php">Đơn hàng</a></div>
                            <div class="login"><a href="php/client/logoutUser.php">Đăng xuất</a></div>
                        <?php
                        }
                        ?>                      
                    </div>
                </div>
                <div class="account">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <p>Sản phẩm</p>
                </div>
                <div class="account">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>Hệ thống của hàng</p>
                </div>
            </div>
        </div>
        <div class="mid-header">
            <div>
                <img src="./anh/logo.jpg" alt="">
            </div>
            <form action="search.php" method="GET" class="search">
                <div class="search">
                    <input type="text" name ="query" placeholder="Tìm kiếm sản phẩm ...">
                    <div class="grass">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>
            </form>
            <div class="phone">
                <div>
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div class="info-phone">
                    <p>Gọi đặt hàng</p>
                    <p class="sdt">0368334112</p>
                </div>
            </div>
            <div class="phone">
                <div>
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div class="info-phone">
                    <p>Gọi tư vấn</p>
                    <p class="sdt">0368334112</p>
                </div>
            </div>
        </div>
        <div class="nav">
            <div class="left-nav">
                <div class="list">
                    <i class="fa-solid fa-list"></i> <p>DANH MỤC SẢN PHẨM</p>
                </div>
                <a href="trangChu.php">Trang chủ</a>
                <a href="">Giới thiệu</a>
                <a href="">Khuyến mãi</a>
                <a href="">Tin tức</a>
                <a href="">Tuyển dụng</a>
                <a href="">Liên hệ</a>
            </div> 
            <div class="right-nav">
                <a href="gioHang.php">Giỏ hàng</a>
                <div class="gioHang">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>  
        </div>         
    </div>
</body>
</html>