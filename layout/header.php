<?php
// BẮT BUỘC: session_start phải nằm ở dòng đầu tiên của file
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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
                    <a href="hoSo.php" style="color: inherit; text-decoration: none;">
                        <i class="fa-solid fa-circle-user"></i>
                    </a>
                    <p>
                        <?php echo isset($_SESSION['tenDangNhap']) ? "Chào, " . htmlspecialchars($_SESSION['tenDangNhap']) : "Tài khoản"; ?>
                    </p>
                    <div class="log">
                        <?php
                            if (!isset($_SESSION['tenDangNhap'])){
                        ?>
                                <div class="login"><a href="dangNhap.php">Đăng nhập</a></div>
                                <div class="login"><a href="dangKy.php">Đăng ký</a></div>
                        <?php
                            } else { 
                        ?>
                            <div class="login"><a href="hoSo.php">Hồ sơ cá nhân</a></div>
                            <div class="login"><a href="doiMatKhau.php">Đổi mật khẩu</a></div>
                            <div class="login"><a href="gioHang.php">Giỏ hàng</a></div>
                            <div class="login"><a href="donHang.php">Đơn hàng</a></div>
                            <div class="login"><a href="php/client/logoutUser.php">Đăng xuất</a></div>
                        <?php
                            }
                        ?>                      
                    </div>
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

            <form action="search.php" method="GET" class="search-box">
                <input type="text" name="query" placeholder="Bạn muốn tìm hoa quả gì hôm nay?">
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
            <div class="header-contact">
                <i class="fa-solid fa-headset"></i>
                <div class="contact-txt">
                    <p>Hỗ trợ tận tâm</p>
                    <span>0368.334.112</span>
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