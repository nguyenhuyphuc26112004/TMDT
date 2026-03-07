<?php
require_once('../php/checkSession.php');
checkSession(2);
require_once('../php/admin/getAllObject.php');

// --- 1. XỬ LÝ BỘ LỌC THEO NGÀY ---
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate   = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Điều kiện mặc định: Chỉ tính những đơn đã thanh toán thành công
$whereSql = " WHERE trang_thai_thanh_toan = 'Đã thanh toán'";

if (!empty($startDate) && !empty($endDate)) {
    $whereSql .= " AND ngay_dat BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
}

// --- 2. XỬ LÝ PHÂN TRANG ---
$limit = 15; // Số lượng đơn hàng tối đa mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

// --- 3. TRUY VẤN DỮ LIỆU THỐNG KÊ ---

// A. Tổng doanh thu toàn bộ hệ thống (Mọi thời đại)
$sqlTotal = "SELECT SUM(tong_tien) as total_all FROM don_hang WHERE trang_thai_thanh_toan = 'Đã thanh toán'";
$resTotal = mysqli_query($con, $sqlTotal);
$totalAllTime = mysqli_fetch_assoc($resTotal)['total_all'] ?? 0;

// B. Tổng doanh thu và Tổng số đơn hàng theo bộ lọc (Để hiển thị và tính phân trang)
$sqlFilter = "SELECT SUM(tong_tien) as total_filter, COUNT(*) as count_orders 
              FROM don_hang" . $whereSql;
$resFilter = mysqli_query($con, $sqlFilter);
$dataFilter = mysqli_fetch_assoc($resFilter);

$totalFiltered = $dataFilter['total_filter'] ?? 0;
$countOrders   = $dataFilter['count_orders'] ?? 0; // Đã sửa tên biến đồng nhất
$totalPages    = ceil($countOrders / $limit);

// C. Lấy danh sách đơn hàng thực tế (Có LIMIT phân trang)
$sqlList = "SELECT * FROM don_hang" . $whereSql . " ORDER BY ngay_dat DESC LIMIT $start, $limit";
$resList = mysqli_query($con, $sqlList);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        
        /* SIDEBAR STYLES */
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 20px; margin: 15px 0; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; margin-right: 10px; }

        /* MAIN CONTENT STYLES */
        .main { margin-top: 50px; margin-left: 20px; width: 75%; flex: 1; padding-right: 20px; }
        .title { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; }

        /* BOX THỐNG KÊ */
        .stats-box { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat { background: #fff; padding: 15px; border-radius: 6px; flex: 1; border: 1px solid #ddd; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .stat small { color: #666; font-weight: bold; text-transform: uppercase; font-size: 12px; }
        .stat h3 { margin: 10px 0 5px 0; color: #333; font-size: 22px; }

        /* BỘ LỌC */
        .filter-box { background: #fff; padding: 15px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 6px; display: flex; align-items: center; }
        .filter-box form { display: flex; align-items: center; gap: 10px; }
        .filter-box input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .filter-box button { background: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
        .filter-box .reset-btn { text-decoration: none; color: #666; font-size: 14px; margin-left: 10px; }

        /* TABLE */
        table { border-collapse: collapse; width: 100%; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        td, th { font-size: 14px; border: 1px solid #ddd; text-align: center; padding: 12px 8px; }
        th { background-color: #f8f9fa; font-size: 15px; color: #333; }
        tr:hover { background-color: #fcfcfc; }

        /* BADGES */
        .badge { padding: 4px 8px; border-radius: 10px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-info { background-color: #cce5ff; color: #004085; }

        /* PHÂN TRANG */
        .pagination { display: flex; justify-content: center; list-style: none; padding: 25px 0; gap: 8px; }
        .pagination a { text-decoration: none; color: #333; padding: 8px 14px; border: 1px solid #ddd; border-radius: 4px; background: #fff; transition: 0.3s; }
        .pagination a.active { background-color: #007bff; color: white; border-color: #007bff; }
        .pagination a:hover:not(.active) { background-color: #eee; }
    </style>
</head>

<body>
<div class="container">
    
    <div class="trangchu">
        <img src="./logo/logo.jpg" alt="Logo">
        <div class="tieude">
            <p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p>
        </div>

        <div class="list-tieude">
            <p><i class="fa-solid fa-layer-group"></i> Danh mục quản lý</p>
            <div class="danhmuc">
                <a href="quanLyKH.php"><i class="fa-solid fa-users"></i> Quản lý người dùng</a>
                <a href="quanLySP.php"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</a>
                <a href="quanLyDH.php"><i class="fa-solid fa-cart-shopping"></i> Quản lý đơn hàng</a>
                <a href="thongKe.php" style="font-weight:bold; color: #000;"><i class="fa-solid fa-chart-line"></i> Thống kê</a>
            </div>

            <p><i class="fa-solid fa-user-gear"></i> Tài khoản</p>
            <div class="danhmuc">
                <a href="../php/admin/logoutAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="title">
            <h2><i class="fa-solid fa-chart-pie"></i> Bảng thống kê doanh thu</h2>
        </div>

        <div class="stats-box">
            <div class="stat">
                <small>Tổng doanh thu hệ thống</small>
                <h3><?php echo number_format($totalAllTime, 0, ',', '.'); ?> đ</h3>
            </div>
            <div class="stat" style="border-top: 4px solid #28a745;">
                <small>Doanh thu theo bộ lọc</small>
                <h3 style="color: #28a745;"><?php echo number_format($totalFiltered, 0, ',', '.'); ?> đ</h3>
            </div>
            <div class="stat" style="border-top: 4px solid #007bff;">
                <small>Số đơn thành công</small>
                <h3><?php echo $countOrders; ?> đơn</h3>
            </div>
        </div>

        <div class="filter-box">
            <form method="GET" action="thongKe.php">
                <strong>Lọc ngày:</strong> 
                <input type="date" name="start_date" value="<?php echo $startDate; ?>">
                <span>đến</span>
                <input type="date" name="end_date" value="<?php echo $endDate; ?>">
                <button type="submit"><i class="fa-solid fa-filter"></i> Lọc dữ liệu</button>
                <?php if($startDate != ''): ?>
                    <a href="thongKe.php" class="reset-btn">Làm mới</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ngày đặt</th>
                    <th>Khách hàng</th>
                    <th>PT Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($resList) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($resList)): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['ngay_dat'])); ?></td>
                        <td style="text-align: left;"><?php echo htmlspecialchars($row['ten']); ?></td>
                        <td><span class="badge badge-info"><?php echo $row['pt_thanh_toan']; ?></span></td>
                        <td><span class="badge badge-success"><?php echo $row['trang_thai_thanh_toan']; ?></span></td>
                        <td><strong style="color: #d9534f;"><?php echo number_format($row['tong_tien'], 0, ',', '.'); ?> đ</strong></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">Không có dữ liệu đơn hàng nào được tìm thấy.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="?page=<?php echo $page-1 ?>&start_date=<?php echo $startDate ?>&end_date=<?php echo $endDate ?>">&laquo; Trang trước</a>
            <?php endif; ?>

            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i ?>&start_date=<?php echo $startDate ?>&end_date=<?php echo $endDate ?>" 
                   class="<?php echo ($i == $page) ? 'active' : '' ?>">
                    <?php echo $i ?>
                </a>
            <?php endfor; ?>

            <?php if($page < $totalPages): ?>
                <a href="?page=<?php echo $page+1 ?>&start_date=<?php echo $startDate ?>&end_date=<?php echo $endDate ?>">Trang sau &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
    </div>
</div>
</body>
</html>