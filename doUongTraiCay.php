<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/sanPham.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Đồ uống trái cây</title>
    <style>
        .product{
            font-size: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);    
            justify-content: space-around;
        }
        .product img{
            cursor: pointer;
            width: 180px;
            height: 220px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.1) 2px 2px 2px 2px;
        }
        .picture{
            margin: 20px 20px 10px;
            padding: 20px;
            border: 2px solid  #cecece;
            border-radius: 10px;
        }
        .name{
            margin: 10px 0;
        }
        .name a{
            font-size: 20px;
            text-decoration: none;
            color: black;    
        }
        .price p{
            color: red;
            margin:10px 0;
        }
        .btn{
            margin-top: 10px;
        }
        .btn{
            margin-top: 10px;
        }
        .btn {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .product button{
            background-color: #28c361;
            padding: 8px 35px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .btn a{
            font-size: 18px;
            text-decoration: none;
            color: white;    
        }
        .pagination {
            display: flex;
            justify-content: center;
        }

        .pagination a {
            font-size: 18px;
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php
        require('layout/header.php');
        require('php/client/getObjectByCondition.php');


        $itemsPerPage = 8; // Define number of items per page
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page from URL
        $currentPage = max(1, $currentPage); // Ensure current page is at least 1

        $totalProducts = getTotalProducts($con, 'san_pham', 'Đồ uống trái cây'); // Get total products
        $totalPages = ceil($totalProducts / $itemsPerPage); // Calculate total pages

        $offset = ($currentPage - 1) * $itemsPerPage;
        $sanPham = getObjectByCondition($con, 'san_pham', 'Đồ uống trái cây', $offset, $itemsPerPage); // Adjust this function to accept offset and limit
    ?>
    
    <div class="container">
        <div class="left-container">
            <div class="list-product">
                <div class="item"><a href="traiCayVietNam.php">Trái cây Việt Nam</a></div>
                <div class="item"><a href="traiCayNhapKhau.php">Trái cây nhập khẩu</a></div>
                <div class="item"><a href="quaSaykho.php">Quả sấy khô</a></div>
                <div class="item"><a href="gioTraiCay.php">Giỏ trái cây</a></div>                    
                <div class="item"><a href="doUongTraiCay.php">Đồ uống trái cây</a></div>
            </div>
            <div class="bannerDoc">
                <img src="./anh/banner.png" alt="">
            </div>
        </div>
        <div class="right-container">
            <div class="tieude">
                <b>Nước uống trái cây</b>
            </div>
            <div class="products">
                <div class="product">
                    <?php
                    foreach ($sanPham as $sp) {
                    ?>
                        <div class="picture">
                            <a href=""><img src="admin/img/<?php echo $sp['anh'] ?>" alt=" Lỗi <?php echo $sp['anh'] ?>"></a>
                            <div class="name">
                                <a href="xemChiTietSP.php?id=<?php echo $sp['id'] ?>"><?php echo $sp['ten'] ?></a>
                            </div>
                            <div class="donVi">
                                Đơn vị: <?php echo $sp['don_vi'] ?>
                            </div>
                            <div class="price">
                                <p>Giá: <?php echo number_format($sp['gia'], 0, ',', '.') ?> VNĐ</p>
                            </div>
                            <div class="btn">
                                <button><a href="xemChiTietSP.php?id=<?php echo $sp['id'] ?>">Chọn</a></button>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="pagination">
                    <a href="?page=<?php echo max(1, $currentPage - 1); ?>">&laquo;</a>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" <?php if ($i == $currentPage) echo 'style="font-weight:bold;"'; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>">&raquo;</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>