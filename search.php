<?php
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

    $query = isset($_GET['query']) ? $_GET['query'] : '';

    // Prepare and execute the search
    $sql = "SELECT * FROM san_pham WHERE ten LIKE ? OR loai LIKE ?";
    $stmt = $con->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store results in an array
    $sanPham = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sanPham[] = $row; // Add each product to the array
        }
    } else {
        $sanPham = []; // No products found
    }
    $stmt->close();
    $con->close();
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
    <title>Trái cây nhập khẩu</title>
    <style>
        .product {
            font-size: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);    
            justify-content: space-around;
        }
        .product img {
            cursor: pointer;
            width: 180px;
            height: 220px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.1) 2px 2px 2px 2px;
        }
        .picture {
            margin: 20px 20px 10px;
            padding: 20px;
            border: 2px solid  #cecece;
            border-radius: 10px;
        }
        .tieude {
            font-size: 28px;
        }
        .name {
            margin: 10px 0;
        }
        .name a {
            font-size: 20px;
            text-decoration: none;
            color: black;    
        }
        .price p {
            color: red;
            margin: 10px 0;
        }
        .btn {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .product button {
            background-color: #28c361;
            padding: 8px 35px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn a {
            font-size: 18px;
            text-decoration: none;
            color: white;    
        }
    </style>
</head>
<body>
    <?php require('layout/header.php'); ?>
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
            <div class="product">
                <?php if (empty($sanPham)): ?>
                    <p>Không tìm thấy sản phẩm nào.</p>
                <?php else: ?>
                    <?php foreach ($sanPham as $sp): ?>
                        <div class="picture">
                            <a href=""><img src="admin/img/<?php echo htmlspecialchars($sp['anh']); ?>" alt="Lỗi <?php echo htmlspecialchars($sp['anh']); ?>"></a>
                            <div class="name">
                                <a href="xemChiTietSP.php?id=<?php echo $sp['id']; ?>"><?php echo htmlspecialchars($sp['ten']); ?></a>
                            </div>
                            <div class="donVi">
                                Đơn vị: <?php echo htmlspecialchars($sp['don_vi']); ?>
                            </div>
                            <div class="price">
                                <p>Giá: <?php echo number_format($sp['gia'], 0, ',', '.') . ' VNĐ'; ?></p>
                            </div>
                            <div class="btn">
                                <button><a href="xemChiTietSP.php?id=<?php echo $sp['id']; ?>">Chọn</a></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>