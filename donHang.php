<?php
// Kiểm tra người dùng đã đăng nhập hay chưa
require('php/checkSession.php');
checkSessionClient();
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
            --dark-green: #1e7e34;
            --bg-gray: #f8f9fa;
            --text-main: #333;
            --text-muted: #777;
        }

        body {
            background-color: #f4f7f4;
            margin: 0;
            color: var(--text-main);
        }

        .container-order {
            max-width: 1100px;
            margin: 40px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            min-height: 550px;
        }

        .header-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 22px;
            color: var(--dark-green);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Table Design tinh giản */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        th {
            text-align: left;
            padding: 10px 15px;
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            background-color: #fff;
            transition: 0.3s;
        }

        td {
            padding: 20px 15px;
            border-top: 1px solid #f1f1f1;
            border-bottom: 1px solid #f1f1f1;
        }

        /* Bo góc dòng */
        td:first-child { border-left: 1px solid #f1f1f1; border-radius: 10px 0 0 10px; }
        td:last-child { border-right: 1px solid #f1f1f1; border-radius: 0 10px 10px 0; }

        tbody tr:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        /* Badge trạng thái nhẹ nhàng */
        .badge {
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }

        .status-pending { background: #fff4e5; color: #ff9800; }
        .status-shipping { background: #e3f2fd; color: #2196f3; }
        .status-received { background: #ebfbee; color: #2ecc71; }
        .status-cancel { background: #fdf2f2; color: #e74c3c; }

        /* Nút bấm hiện đại */
        .btn {
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
        }

        .btn-view { background: #f8f9fa; color: #444 !important; border: 1px solid #ddd; }
        .btn-view:hover { background: #eee; }

        .btn-cancel { background: #fff; color: #dc3545 !important; border: 1px solid #f5c6cb; }
        .btn-cancel:hover { background: #dc3545; color: white !important; }

        /* Phân trang */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 35px;
        }

        .pagination a {
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid #eee;
            color: var(--text-main);
            text-decoration: none;
        }

        .pagination a.active {
            background: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }
    </style>
</head>

<body>
    <?php
    require('layout/header.php');
    require('php/client/getObjectByCondition.php');

    $idNguoiDung = $_SESSION['idNguoiDung'];

    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $total_orders = countTotalOrdersByUser($con, $idNguoiDung);
    $total_pages = ceil($total_orders / $limit);
    $donHang = getOrderByUserPaged($con, $idNguoiDung, $offset, $limit);

    if ($total_orders == 0) {
        echo '<div class="container-order" style="display:flex; align-items:center; justify-content:center; text-align:center;">
                <div>
                    <i class="fa-solid fa-box-open" style="font-size: 60px; color: #eee; margin-bottom: 15px;"></i>
                    <h2 style="color: #bbb;">Bạn chưa có đơn hàng nào</h2>
                </div>
              </div>';
    } else {
    ?>
        <div class="container-order">
            <div class="header-title">
                <h1><i class="fa-solid fa-receipt"></i> Lịch sử mua hàng</h1>
                <span style="font-size: 13px; color: var(--text-muted);">Hiển thị <?php echo count($donHang); ?> đơn hàng gần nhất</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th>Thao tác</th>
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
                            <td><strong style="color: #2c3e50;">#<?php echo $dH['id'] ?></strong></td>
                            <td>
                                <div style="font-weight: 500;"><?php echo date('d/m/Y', strtotime($dH['ngay_dat'])) ?></div>
                                <small style="color: #999;"><?php echo date('H:i', strtotime($dH['ngay_dat'])) ?></small>
                            </td>
                            <td>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo $dH['trang_thai'] ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: var(--dark-green); font-size: 15px;">
                                    <?php echo number_format($dH['tong_tien'], 0, ',', '.') ?> đ
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="xemChiTietDH.php?id=<?php echo $dH['id'] ?>" class="btn btn-view">
                                        <i class="fa-solid fa-eye"></i> Chi tiết
                                    </a>
                                    
                                    <?php if ($dH['trang_thai'] == 'Đang chờ duyệt') { ?>
                                        <a href="huyDonHang.php?id=<?php echo $dH['id'] ?>" 
                                           class="btn btn-cancel" 
                                           onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
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
                        <a href="?page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php } ?>

    <?php require('layout/footer.php'); ?>
</body>

</html>