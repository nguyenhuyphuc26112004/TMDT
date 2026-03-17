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
            --light-green: #ebfbee;
            --bg-gray: #f8f9fa;
            --text-main: #2d3436; /* Màu chữ chính đậm hơn để không bị mờ */
            --text-sub: #636e72;  /* Màu chữ phụ */
            --border-color: #dfe6e9;
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            min-height: 550px;
        }

        .header-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid var(--bg-gray);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 24px;
            color: var(--text-main); /* Chữ tiêu đề đậm rõ ràng */
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-title h1 i {
            color: var(--primary-green);
        }

        /* Table Design */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
        }

        th {
            text-align: left;
            padding: 10px 20px;
            font-size: 14px;
            color: var(--text-main); /* Chữ th đậm hơn */
            font-weight: 600;
            text-transform: uppercase;
        }

        tbody tr {
            background-color: #fff;
            transition: all 0.3s ease;
        }

        td {
            padding: 20px;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        /* Bo góc dòng */
        td:first-child { border-left: 1px solid var(--border-color); border-radius: 12px 0 0 12px; }
        td:last-child { border-right: 1px solid var(--border-color); border-radius: 0 12px 12px 0; }

        tbody tr:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transform: translateY(-3px);
        }

        /* Mã đơn hàng nổi bật */
        .order-id {
            font-weight: 700;
            font-size: 15px;
        }

        /* Badge trạng thái */
        .badge {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending { background: #fff4e5; color: #d48806; }
        .status-shipping { background: #e6f7ff; color: #1890ff; }
        .status-received { background: #f6ffed; color: #52c41a; }
        .status-cancel { background: #fff1f0; color: #f5222d; }

        /* Nút bấm */
        .btn {
            text-decoration: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        /* NÚT CHI TIẾT MÀU XANH */
        .btn-view { 
            background: var(--light-green); 
            color: var(--primary-green) !important; 
            border: 1px solid var(--primary-green); 
        }

        .btn-view:hover { 
            background: var(--primary-green); 
            color: white !important; 
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        .btn-cancel { 
            background: #fff; 
            color: #e74c3c !important; 
            border: 1px solid #fab1a0; 
        }

        .btn-cancel:hover { 
            background: #ff7675; 
            color: white !important; 
            border-color: #ff7675;
        }

        /* Phân trang */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .pagination a {
            padding: 10px 18px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .pagination a.active {
            background: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }

        .pagination a:hover:not(.active) {
            background: var(--light-green);
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
                    <i class="fa-solid fa-box-open" style="font-size: 80px; color: #dcdde1; margin-bottom: 20px;"></i>
                    <h2 style="color: #636e72;">Bạn chưa có đơn hàng nào</h2>
                    <a href="trangChu.php" class="btn btn-view" style="margin-top: 15px;">Mua sắm ngay nhé</a>
                </div>
              </div>';
    } else {
    ?>
        <div class="container-order">
            <div class="header-title">
                <h1><i class="fa-solid fa-receipt"></i> Lịch sử mua hàng</h1>
                <span style="font-size: 14px; font-weight: 500;">
                    Tổng số: <b><?php echo $total_orders; ?></b> đơn hàng
                </span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Thời gian đặt</th>
                        <th>Trạng thái</th>
                        <th>Tổng thanh toán</th>
                        <th style="text-align: center;">Thao tác</th>
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
                            <td><span class="order-id">#<?php echo $dH['id'] ?></span></td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main);"><?php echo date('d/m/Y', strtotime($dH['ngay_dat'])) ?></div>
                                <div style="font-size: 12px; "><?php echo date('H:i', strtotime($dH['ngay_dat'])) ?></div>
                            </td>
                            <td>
                                <span class="badge <?php echo $statusClass; ?>">
                                    <?php echo $dH['trang_thai'] ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 800; color: #d63031; font-size: 16px;">
                                    <?php echo number_format($dH['tong_tien'], 0, ',', '.') ?> đ
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 10px; justify-content: center;">
                                    <a href="xemChiTietDH.php?id=<?php echo $dH['id'] ?>" class="btn btn-view">
                                        <i class="fa-solid fa-circle-info"></i> Chi tiết
                                    </a>
                                    
                                    <?php if ($dH['trang_thai'] == 'Đang chờ duyệt') { ?>
                                        <a href="huyDonHang.php?id=<?php echo $dH['id'] ?>" 
                                           class="btn btn-cancel" 
                                           onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng #<?php echo $dH['id'] ?>?')">
                                            <i class="fa-solid fa-trash-can"></i> Hủy
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