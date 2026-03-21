<?php
require_once('../php/checkSession.php');
checkSession(2); 
require_once('../php/admin/getAllObject.php');

// --- 1. XỬ LÝ BỘ LỌC NGÀY ---
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate   = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// --- 2. PHÂN TRANG ---
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// --- 3. TRUY VẤN DỮ LIỆU ---
$sqlTotal = "SELECT SUM(tong_tien) as total_all FROM don_hang WHERE trang_thai_thanh_toan = 'Đã thanh toán'";
$totalAllTime = mysqli_fetch_assoc(mysqli_query($con, $sqlTotal))['total_all'] ?? 0;

$sqlFilter = "SELECT COUNT(*) as total_rows, SUM(tong_tien) as total_sum 
              FROM don_hang 
              WHERE trang_thai_thanh_toan = 'Đã thanh toán' 
              AND ngay_dat BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
$resFilter = mysqli_fetch_assoc(mysqli_query($con, $sqlFilter));
$totalFiltered = $resFilter['total_sum'] ?? 0;
$totalRows = $resFilter['total_rows'] ?? 0;
$totalPages = ceil($totalRows / $limit);

$sqlList = "SELECT * FROM don_hang 
            WHERE trang_thai_thanh_toan = 'Đã thanh toán' 
            AND ngay_dat BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'
            ORDER BY ngay_dat DESC LIMIT $start, $limit";
$resList = mysqli_query($con, $sqlList);

// --- 4. DỮ LIỆU BIỂU ĐỒ ---
$months = []; $revenues = [];
$sqlMonthly = "SELECT m.m, COALESCE(SUM(d.tong_tien), 0) as rev FROM (SELECT 1 AS m UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) AS m LEFT JOIN don_hang d ON m.m = MONTH(d.ngay_dat) AND YEAR(d.ngay_dat) = YEAR(CURDATE()) AND d.trang_thai_thanh_toan = 'Đã thanh toán' GROUP BY m.m";
$resMonthly = mysqli_query($con, $sqlMonthly);
while($r = mysqli_fetch_assoc($resMonthly)) { $months[] = "T".$r['m']; $revenues[] = $r['rev']; }

$spNames = []; $spQtys = [];
$sqlTop = "SELECT sp.ten, SUM(ct.so_luong) as qty FROM ct_don_hang ct JOIN san_pham sp ON ct.id_san_pham = sp.id JOIN don_hang dh ON ct.id_don_hang = dh.id WHERE dh.trang_thai_thanh_toan = 'Đã thanh toán' GROUP BY sp.id ORDER BY qty DESC LIMIT 5";
$resTop = mysqli_query($con, $sqlTop);
while($r = mysqli_fetch_assoc($resTop)) { $spNames[] = $r['ten']; $spQtys[] = $r['qty']; }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* GIỮ NGUYÊN LAYOUT SIDEBAR GỐC CỦA BẠN */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
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

        /* PHẦN NỘI DUNG CHÍNH BÊN PHẢI */
        .main { flex: 1; padding: 20px 40px; overflow-y: auto; }
        
        /* Bộ lọc ngày */
        .filter-section { 
            background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 20px; 
            display: flex; align-items: flex-end; gap: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { font-size: 13px; font-weight: bold; }
        .filter-group input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }

        /* Stats Cards */
        .stats-box { display: flex; gap: 20px; margin-bottom: 25px; }
        .stat { background: #fff; padding: 20px; border-radius: 8px; flex: 1; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 4px solid #333; }
        
        .charts-container { display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; margin-bottom: 30px; }
        .chart-box { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }

        table { width: 100%; border-collapse: collapse; background: #fff; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; font-size: 14px; }
        th { background: #f8f9fa; }
        .pagination { display: flex; justify-content: center; gap: 5px; margin: 20px 0; }
        .pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #333; background: #fff; border-radius: 4px; }
        .pagination a.active { background: #007bff; color: #fff; }
    </style>
</head>
<body>
<div class="container">
    <div class="trangchu">
        <img src="./logo/logo.jpg" alt="Logo">
        <div class="tieude"><p><i class="fa-solid fa-bars"></i> Quản lý hệ thống</p></div>
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
        <h2><i class="fa-solid fa-chart-line"></i> Báo cáo doanh thu</h2>

        <form class="filter-section" method="GET">
            <div class="filter-group">
                <label>Từ ngày</label>
                <input type="date" name="start_date" value="<?php echo $startDate; ?>">
            </div>
            <div class="filter-group">
                <label>Đến ngày</label>
                <input type="date" name="end_date" value="<?php echo $endDate; ?>">
            </div>
            <button type="submit" class="btn-submit"><i class="fa-solid fa-filter"></i> Lọc dữ liệu</button>
        </form>

        <div class="stats-box">
            <div class="stat">
                <small>Tổng doanh thu</small>
                <h3><?php echo number_format($totalAllTime, 0, ',', '.'); ?>đ</h3>
            </div>
            <div class="stat" style="border-top-color: #28a745;">
                <small>Doanh thu kỳ lọc</small>
                <h3 style="color: #28a745;"><?php echo number_format($totalFiltered, 0, ',', '.'); ?>đ</h3>
            </div>
            <div class="stat" style="border-top-color: #007bff;">
                <small>Đơn hàng</small>
                <h3><?php echo $totalRows; ?></h3>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-box"><canvas id="revChart"></canvas></div>
            <div class="chart-box"><canvas id="pieChart"></canvas></div>
        </div>

        <div class="chart-box">
            <h4>Danh sách đơn thành công trong kỳ</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ngày đặt</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($resList)): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['ngay_dat'])); ?></td>
                        <td><?php echo htmlspecialchars($row['ten']); ?></td>
                        <td style="color:red; font-weight:bold;"><?php echo number_format($row['tong_tien'], 0, ',', '.'); ?>đ</td>
                        <td><span style="color:green">Đã thanh toán</span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&start_date=<?php echo $startDate; ?>&end_date=<?php echo $endDate; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Doanh thu
new Chart(document.getElementById('revChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{ label: 'Doanh thu tháng (đ)', data: <?php echo json_encode($revenues); ?>, borderColor: '#007bff', backgroundColor: 'rgba(0,123,255,0.1)', fill: true }]
    }
});
// Sản phẩm
new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($spNames); ?>,
        datasets: [{ data: <?php echo json_encode($spQtys); ?>, backgroundColor: ['#ff6384', '#36a2eb', '#ffce56', '#4bc0c0', '#9966ff'] }]
    }
});
</script>
</body>
</html>