<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Trang chủ
    </title>
</head>
<body>
    <?php
        include('layout/header.php');
    ?>
    <div class="mid">
        <div class="left-mid">
            <div class="list-product">
                <div class="item"><a href="traiCayVietNam.php">Trái cây Việt Nam</a></div>
                <div class="item"><a href="traiCayNhapKhau.php">Trái cây nhập khẩu</a></div>
                <div class="item"><a href="quaSaykho.php">Quả sấy khô</a></div>
                <div class="item"><a href="gioTraiCay.php">Giỏ trái cây</a></div>                    
                <div class="item"><a href="doUongTraiCay.php">Đồ uống trái cây</a></div>
            </div>
        </div>
        <div class="right-mid">
            <div class="main-picture">
                <img class="active" style="width: 1000px; height: 400px;" src="./anh/banner1.png" alt="">
                <img  style="width: 1000px; height: 400px;" src="./anh/banner2.png" alt="">
                <img style="width: 1000px; height: 400px;" src="./anh/banner3.png" alt="">
            </div>
            <div class="next">
                <i class="fa-solid fa-circle-chevron-right" onclick="showNext()"></i>
            </div>
            <div class="prev">
                <i class="fa-solid fa-circle-chevron-left" onclick="showPrev()"></i>
            </div>
        </div>
    </div>
    <?php
        include('layout/footer.php');
    ?>
    <script src="./js/main.js"></script>
</body>
</html>