<?php
// 1. KIỂM TRA SESSION & KẾT NỐI
require('php/checkSession.php');
checkSessionClient();
require('php/client/getObjectByCondition.php'); 

$idNguoiDung = $_SESSION['idNguoiDung'];

// 2. XỬ LÝ LOGIC LỌC (Chỉ lọc theo Tab trạng thái)
$statusFilter = isset($_GET['status']) ? mysqli_real_escape_string($con, $_GET['status']) : '';

// Xây dựng điều kiện WHERE
$whereClauses = ["id_nguoi_dung = '$idNguoiDung'"];
if (!empty($statusFilter)) {
    $whereClauses[] = "trang_thai = '$statusFilter'";
}
$whereSql = "WHERE " . implode(" AND ", $whereClauses);

// 3. PHÂN TRANG
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sqlCount = "SELECT COUNT(*) as total FROM don_hang $whereSql";
$countRes = mysqli_query($con, $sqlCount);
$total_orders = mysqli_fetch_assoc($countRes)['total'];
$total_pages = ceil($total_orders / $limit);

$sqlData = "SELECT * FROM don_hang $whereSql ORDER BY ngay_dat DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $sqlData);
$donHang = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của tôi - Shop Hoa Quả Sạch</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #28a745;
            --danger-red: #d63031;
            --text-main: #2d3436;
            --text-sub: #636e72;
            --border-color: #dfe6e9;
        }

        body { background-color: #f4f7f4; margin: 0; color: var(--text-main); }
        .container-order { max-width: 1300px; margin: 40px auto; padding: 30px; background: #ffffff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); min-height: 550px; }
        
        .header-title { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #f8f9fa; padding-bottom: 20px; margin-bottom: 25px; }
        .header-title h1 { font-size: 26px; margin: 0; font-weight: 500; }

        /* FILTER TABS */
        .filter-tabs { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
        .tab-item { text-decoration: none; padding: 12px 22px; background: #f1f2f6; color: var(--text-sub); border-radius: 8px; font-size: 16px; transition: 0.3s; }
        .tab-item.active { background: var(--primary-green); color: white; }

        /* TABLE STYLES */
        table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        th { text-align: left; padding: 10px 15px; font-size: 15px; color: #000; font-weight: 600; text-transform: uppercase; border-bottom: 2px solid #eee; }
        
        td { 
            padding: 20px 15px; 
            background-color: #fff;
            border-top: 1px solid var(--border-color); 
            border-bottom: 1px solid var(--border-color); 
            vertical-align: middle; 
            font-size: 17px;
            font-weight: 400;
            color: var(--text-main);
        }
        td:first-child { border-left: 1px solid var(--border-color); border-radius: 10px 0 0 10px; }
        td:last-child { border-right: 1px solid var(--border-color); border-radius: 0 10px 10px 0; }

        /* STATUS BADGE */
        .badge { padding: 6px 14px; border-radius: 6px; font-size: 14px; font-weight: 500; display: inline-block; }
        .status-pending { background: #fff4e5; color: #a67100; }
        .status-shipping { background: #e6f7ff; color: #0050b3; }
        .status-received { background: #f6ffed; color: #237804; }
        .status-cancel { background: #fff1f0; color: #a8071a; }

        .price-text { color: var(--danger-red); font-size: 18px; font-weight: 500; }
        
        /* ACTION BUTTONS */
        .btn-action-group { display: flex; align-items: center; justify-content: center; gap: 8px; }
        
        .btn-view, .btn-cancel { 
            text-decoration: none; 
            padding: 10px 16px; 
            border-radius: 8px; 
            font-size: 14px; 
            transition: 0.3s; 
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        /* Nút Xem chi tiết */
        .btn-view { 
            background: #ebfbee; 
            color: var(--primary-green); 
            border-color: var(--primary-green); 
        }
        .btn-view:hover { background: var(--primary-green); color: white; }

        /* Nút Hủy */
        .btn-cancel { 
            background: #fff5f5; 
            color: var(--danger-red); 
            border-color: var(--danger-red); 
        }
        .btn-cancel:hover { background: var(--danger-red); color: white; }
        
        /* PAGINATION */
        .pagination { display: flex; justify-content: center; gap: 10px; margin-top: 30px; }
        .pagination a { padding: 10px 18px; border-radius: 8px; border: 1px solid var(--border-color); text-decoration: none; color: var(--text-main); font-size: 16px; }
        .pagination a.active { background: var(--primary-green); color: white; }
    </style>
</head>

<body>
    <?php require('layout/header.php'); ?>

    <div class="container-order">
        <div class="header-title">
            <h1><i class="fa-solid fa-receipt" style="color: var(--primary-green);"></i> Lịch sử mua hàng</h1>
            <span style="font-size: 16px;">Tổng số: <strong><?php echo $total_orders; ?></strong> đơn hàng</span>
        </div>

        <div class="filter-tabs">
            <a href="?" class="tab-item <?php echo $statusFilter == '' ? 'active' : ''; ?>">Tất cả đơn</a>
            <a href="?status=Đang chờ duyệt" class="tab-item <?php echo $statusFilter == 'Đang chờ duyệt' ? 'active' : ''; ?>">Chờ duyệt</a>
            <a href="?status=Đang vận chuyển" class="tab-item <?php echo $statusFilter == 'Đang vận chuyển' ? 'active' : ''; ?>">Đang giao</a>
            <a href="?status=Đã nhận hàng" class="tab-item <?php echo $statusFilter == 'Đã nhận hàng' ? 'active' : ''; ?>">Đã nhận</a>
            <a href="?status=Đã hủy" class="tab-item <?php echo $statusFilter == 'Đã hủy' ? 'active' : ''; ?>">Đã hủy</a>
        </div>

        <?php if ($total_orders == 0) { ?>
            <div style="text-align: center; padding: 80px 0;">
                <i class="fa-solid fa-calendar-xmark" style="font-size: 60px; color: #eee; margin-bottom: 20px;"></i>
                <h3 style="color: var(--text-sub); font-weight: 400;">Bạn chưa có đơn hàng nào trong mục này</h3>
                <a href="trangChu.php" class="btn-view" style="display: inline-block; margin-top: 15px;">Quay lại mua sắm</a>
            </div>
        <?php } else { ?>
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Thời gian</th>
                        <th style="width: 20%;">Địa chỉ</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th style="text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($donHang as $dH) { 
                        $statusClass = '';
                        switch ($dH['trang_thai']) {
                            case 'Đang chờ duyệt': $statusClass = 'status-pending'; break;
                            case 'Đang vận chuyển': $statusClass = 'status-shipping'; break;
                            case 'Đã nhận hàng': $statusClass = 'status-received'; break;
                            case 'Đã hủy': $statusClass = 'status-cancel'; break;
                        }
                    ?>
                        <tr>
                            <td style="color: #000; font-weight: 600;">#<?php echo $dH['id'] ?></td>
                            <td>
                                <div><?php echo date('d/m/Y', strtotime($dH['ngay_dat'])) ?></div>
                                <div style="font-size: 14px; color: var(--text-sub);"><?php echo date('H:i', strtotime($dH['ngay_dat'])) ?></div>
                            </td>
                            <td>
                                <div style="font-size: 15px; line-height: 1.4; color: #444;">
                                    <?php echo htmlspecialchars($dH['dia_chi']) ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 15px; color: #444;">
                                    <?php echo !empty($dH['pt_thanh_toan']) ? $dH['pt_thanh_toan'] : 'COD'; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo $dH['trang_thai'] ?>
                                </span>
                            </td>
                            <td>
                                <span class="price-text">
                                    <?php echo number_format($dH['tong_tien'], 0, ',', '.') ?>đ
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-action-group">
                                    <a href="xemChiTietDH.php?id=<?php echo $dH['id'] ?>" class="btn-view" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye"></i> Xem
                                    </a>
                                    
                                    <?php if ($dH['trang_thai'] == 'Đang chờ duyệt') { ?>
                                        <a href="huyDonHang.php?id=<?php echo $dH['id']; ?>" 
                                           class="btn-cancel" 
                                           onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')"
                                           title="Hủy đơn hàng">
                                            <i class="fa-solid fa-xmark"></i> Hủy
                                        </a>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&status=<?php echo urlencode($statusFilter); ?>" 
                           class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php } ?>
    </div>

    <?php require('layout/footer.php'); ?>
</body>
</html>