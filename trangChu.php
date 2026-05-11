<?php
// 1. Kết nối cơ sở dữ liệu
require('php/connectMysql.php'); 

// Khởi tạo session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- XỬ LÝ PHÂN TRANG ---
$limit = 12; // Số sản phẩm tối đa trên 1 trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Tính tổng số sản phẩm để tính tổng số trang
$total_sql = "SELECT COUNT(*) FROM san_pham WHERE trang_thai = 1";
$total_result = mysqli_query($con, $total_sql);
$total_rows = mysqli_fetch_array($total_result)[0];
$total_pages = ceil($total_rows / $limit);

// 2. Lấy danh sách sản phẩm theo trang
$sql = "SELECT * FROM san_pham WHERE trang_thai = 1 LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $sql);

$products = [];
if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <title>Trang chủ - Hệ thống Trái Cây</title>
    <style>
        .product-section { 
            max-width: 1250px; 
            margin: 30px auto; 
            padding: 0 10px;
            clear: both; 
        }
        
        .product-title { 
            text-align: left; 
            margin-bottom: 25px; 
            font-size: 22px; 
            color: #333;
            text-transform: uppercase;
            font-weight: bold;
            border-left: 5px solid #27ae60; 
            padding-left: 15px;
        }

        .product-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 20px; 
        }

        .product-item {
            border: 1px solid #eee;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            transition: transform 0.3s, box-shadow 0.3s;
            background: #fff;
        }

        .product-item:hover { 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }

        .product-item img { 
            width: 100%; 
            height: 200px; 
            object-fit: cover; 
            border-radius: 5px; 
            margin-bottom: 10px;
        }

        .product-name { 
            font-weight: bold; 
            margin: 10px 0 5px 0; 
            height: 40px; 
            overflow: hidden; 
            color: #444;
            line-height: 20px;
        }

        .product-price { 
            color: #e74c3c; 
            font-weight: bold; 
            font-size: 18px;
            margin-bottom: 5px;
        }

        .product-unit {
            font-size: 13px;
            color: #888;
            margin-bottom: 15px;
        }

        .btn-view { 
            display: inline-block; 
            padding: 8px 15px;
            background: #27ae60; 
            color: white; 
            text-decoration: none; 
            border-radius: 4px;
            font-size: 14px;
            width: 80%;
        }

        /* CSS Phân trang */
        .pagination {
            margin-top: 30px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .pagination a {
            color: #333;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #27ae60;
            color: white;
            border: 1px solid #27ae60;
        }
        .pagination a:hover:not(.active) {
            background-color: #f1f1f1;
        }

        @media (max-width: 1024px) {
            .product-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .product-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include('layout/header.php'); ?>

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
                <img class="active" style="width: 1000px; height: 400px;" src="./anh/banner1.png" alt="Banner 1">
                <img style="width: 1000px; height: 400px;" src="./anh/banner2.png" alt="Banner 2">
                <img style="width: 1000px; height: 400px;" src="./anh/banner3.png" alt="Banner 3">
            </div>
            
        </div>
    </div>

    <div class="product-section">
        <h2 class="product-title">Danh Sách Sản Phẩm</h2>
        
        <div class="product-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $item): ?>
                    <div class="product-item">
                        <img src="admin/img/<?php echo htmlspecialchars($item['anh']); ?>" 
                             alt="<?php echo htmlspecialchars($item['ten']); ?>">
                        
                        <div class="product-name">
                            <?php echo htmlspecialchars($item['ten']); ?>
                        </div>
                        
                        <div class="product-price">
                            <?php echo number_format($item['gia'], 0, ',', '.'); ?>đ
                        </div>
                        
                        <div class="product-unit">
                            Đơn vị: <?php echo htmlspecialchars($item['don_vi']); ?>
                        </div>
                        
                        <a href="xemChiTietSP.php?id=<?php echo $item['id']; ?>" class="btn-view">
                            Xem chi tiết
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: left; padding-left: 15px;">Hiện tại chưa có sản phẩm nào.</p>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=<?php echo ($page - 1); ?>">&laquo; Trước</a>
            <?php endif; ?>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if($page < $total_pages): ?>
                <a href="?page=<?php echo ($page + 1); ?>">Sau &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <?php include('layout/footer.php'); ?>
                <script>
    let currentIndex = 0;
    const images = document.querySelectorAll('.main-picture img');
    const totalImages = images.length;

    function showImage(index) {
        // Gỡ bỏ class active của tất cả ảnh
        images.forEach(img => img.classList.remove('active'));
        
        // Tính toán chỉ số thực tế (vòng lặp)
        currentIndex = (index + totalImages) % totalImages;
        
        // Thêm class active cho ảnh hiện tại
        images[currentIndex].classList.add('active');
    }

    function showNext() {
        showImage(currentIndex + 1);
    }

    function showPrev() {
        showImage(currentIndex - 1);
    }

    // TỰ ĐỘNG CHUYỂN ẢNH SAU 3 GIÂY
    let autoSlide = setInterval(showNext, 3000);

    // Dừng tự động khi người dùng di chuột vào banner và chạy lại khi bỏ ra
    const bannerContainer = document.querySelector('.right-mid');
    bannerContainer.addEventListener('mouseenter', () => clearInterval(autoSlide));
    bannerContainer.addEventListener('mouseleave', () => {
        autoSlide = setInterval(showNext, 3000);
    });
</script>
</body>
</html>