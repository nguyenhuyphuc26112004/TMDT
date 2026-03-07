<?php
// 1. XỬ LÝ LOGIC & DỮ LIỆU
require_once('../php/checkSession.php');
checkSession(2);

require_once('../php/admin/getAllObject.php');

// Lấy từ khóa tìm kiếm và trạng thái lọc từ URL
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($con, trim($_GET['search'])) : '';
$statusFilter = isset($_GET['status']) ? mysqli_real_escape_string($con, $_GET['status']) : '';

// Xây dựng câu lệnh SQL điều kiện động
$whereClauses = [];

if (!empty($searchTerm)) {
    $whereClauses[] = "(id LIKE '%$searchTerm%' OR ten LIKE '%$searchTerm%' OR sdt LIKE '%$searchTerm%' OR ma_chuyen_khoan LIKE '%$searchTerm%')";
}

if (!empty($statusFilter)) {
    $whereClauses[] = "trang_thai = '$statusFilter'";
}

$whereSql = "";
if (count($whereClauses) > 0) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Truy vấn lấy danh sách đơn hàng
$sql = "SELECT * FROM don_hang $whereSql ORDER BY id DESC";
$result = mysqli_query($con, $sql);
$donHang = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        /* GIỮ NGUYÊN STYLE LAYOUT CŨ CỦA BẠN */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .tieude i { margin-right: 5px; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 20px; margin: 15px 0; color: #333; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; text-align: center; margin-right: 10px; }
        
        .quanLyDH { margin-top: 50px; margin-left: 20px; width: 75%; flex: 1; padding-right: 20px; }
        .title { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; }
        .title h2 { margin: 0; }
        
        /* SEARCH BOX */
        .search-container { margin-bottom: 20px; display: flex; flex-direction: column; gap: 15px; }
        .search-form { display: flex; gap: 10px; background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .search-form input { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; width: 300px; outline: none; }
        .btn-search { background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-reset { background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-size: 14px; display: flex; align-items: center; }
        
        /* TABS LỌC */
        .filter-tabs { display: flex; gap: 5px; margin-top: 10px; }
        .tab-item { text-decoration: none; padding: 6px 15px; background: #eee; border: 1px solid #ccc; color: #333; border-radius: 4px; font-size: 14px; }
        .tab-item.active { background: #007bff; color: white; border-color: #0056b3; font-weight: bold; }

        /* TABLE */
        table { border-collapse: collapse; width: 100%; background: #fff; }
        td, th { font-size: 14px; border: 1px solid #a19898; text-align: center; padding: 12px 8px; }
        th { background-color: #f4f4f4; font-size: 16px; }
        
        .action-group { display: flex; justify-content: center; gap: 5px; flex-wrap: wrap; padding: 14px 0;}

        .action-group a { text-decoration: none; padding: 6px 10px; border-radius: 4px; font-size: 14px; color: white !important; display: inline-flex; align-items: center; gap: 4px; transition: 0.2s; }

        .action-group a:hover { opacity: 0.8; transform: scale(1.05); }

        .btn-sp { background-color: #17a2b8; }

        .btn-info { background-color: #28a745; }

        .btn-edit { background-color: #ffc107; color: #212529 !important; }

        .btn-delete { background-color: #dc3545; }
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
                    <a href="quanLyDH.php" style="background: #ccc; color: #000; border-radius: 4px;"><i class="fa-solid fa-cart-shopping"></i> Quản lý đơn hàng</a>
                    <a href="thongKe.php" style="font-weight:bold;"><i class="fa-solid fa-chart-line"></i> Thống kê</a>
                </div>
                <p><i class="fa-solid fa-user-gear"></i> Tài khoản</p>
                <div class="danhmuc">
                    <a href="../php/admin/logoutAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="quanLyDH">
            <div class="title"><h2>Danh sách đơn hàng hệ thống</h2></div>

            <div class="search-container">
                <form action="" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Mã đơn, tên khách hoặc SĐT..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($statusFilter); ?>">
                    <button type="submit" class="btn-search">Tìm kiếm</button>
                    <?php if(!empty($searchTerm) || !empty($statusFilter)): ?>
                        <a href="quanLyDH.php" class="btn-reset">Làm mới</a>
                    <?php endif; ?>
                </form>

                <div class="filter-tabs">
                    <a href="quanLyDH.php?search=<?php echo urlencode($searchTerm); ?>" class="tab-item <?php echo $statusFilter == '' ? 'active' : ''; ?>">Tất cả</a>
                    <a href="quanLyDH.php?status=Đang chờ duyệt&search=<?php echo urlencode($searchTerm); ?>" class="tab-item <?php echo $statusFilter == 'Đang chờ duyệt' ? 'active' : ''; ?>">Chờ duyệt</a>
                    <a href="quanLyDH.php?status=Đang vận chuyển&search=<?php echo urlencode($searchTerm); ?>" class="tab-item <?php echo $statusFilter == 'Đang vận chuyển' ? 'active' : ''; ?>">Đang vận chuyển</a>
                    <a href="quanLyDH.php?status=Đã nhận hàng&search=<?php echo urlencode($searchTerm); ?>" class="tab-item <?php echo $statusFilter == 'Đã nhận hàng' ? 'active' : ''; ?>">Hoàn tất</a>
                    <a href="quanLyDH.php?status=Đã hủy&search=<?php echo urlencode($searchTerm); ?>" class="tab-item <?php echo $statusFilter == 'Đã hủy' ? 'active' : ''; ?>">Đã hủy</a>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức</th>
                        <th>Trạng thái vận chuyển</th>
                        <th>Trạng thái thanh toán</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($donHang) > 0): ?>
                        <?php foreach ($donHang as $dH): ?>
                            <tr>
                                <td><b>#<?php echo $dH['id'] ?></b></td>
                                <td style="text-align: left;">
                                    <b><?php echo $dH['ten'] ?></b><br>
                                    <small><?php echo $dH['sdt'] ?></small>
                                </td>
                                <td style="color: red; font-weight: bold;"><?php echo number_format($dH['tong_tien'], 0, ',', '.'), " đ" ?></td>
                                <td><?php echo $dH['pt_thanh_toan'] ?></td>
                                <td>
                                    <span style="font-weight: bold; color: <?php echo ($dH['trang_thai'] == 'Đã hủy') ? 'red' : 'blue'; ?>">
                                        <?php echo $dH['trang_thai'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: bold; color: <?php echo ($dH['trang_thai_thanh_toan'] == 'Đã thanh toán') ? 'green' : '#ff9800'; ?>">
                                        <?php echo $dH['trang_thai_thanh_toan'] ?> <?php echo ($dH['trang_thai_thanh_toan'] == 'Đã thanh toán') ? '✅' : ''; ?>
                                    </span>
                                </td>
                                <td class="action-group">
                                    <a class="btn-sp" href="xemSP_DH.php?id=<?php echo $dH['id'] ?>"><i class="fa-solid fa-box-open"></i> SP</a>
                                    <a class="btn-info" href="xemCT_DH.php?id=<?php echo $dH['id'] ?>"><i class="fa-solid fa-circle-info"></i> CT</a>
                                    <a class="btn-edit" href="capNhat_DH.php?id=<?php echo $dH['id'] ?>"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                                    <?php if ($dH['trang_thai'] == 'Đã hủy'): ?>
                                        <a class="btn-delete" onclick="return confirm('Xóa đơn hàng này?')" href="xoa_DH.php?id=<?php echo $dH['id'] ?>"><i class="fa-solid fa-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">Không tìm thấy đơn hàng nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>