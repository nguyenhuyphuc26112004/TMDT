<?php
// 1. Kiểm tra session
require_once('../php/checkSession.php');
checkSession(2);

// 2. Nạp file xử lý dữ liệu
require_once('../php/admin/getAllObject.php');

// --- LOGIC PHÂN TRANG (MAX 20 SP) ---
$limit = 15; // Số lượng tối đa
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

// Câu lệnh điều kiện tìm kiếm
$whereSql = "";
if (!empty($searchTerm)) {
    $whereSql = " WHERE id LIKE '%$searchTerm%' OR ten LIKE '%$searchTerm%'";
}

// Lấy tổng số sản phẩm để tính số trang
$sqlCount = "SELECT COUNT(*) as total FROM san_pham" . $whereSql;
$resCount = mysqli_query($con, $sqlCount);
$rowCount = mysqli_fetch_assoc($resCount);
$totalProducts = $rowCount['total'];
$totalPages = ceil($totalProducts / $limit);

// Truy vấn lấy dữ liệu theo LIMIT
$sql = "SELECT * FROM san_pham" . $whereSql . " LIMIT $start, $limit";
$result = mysqli_query($con, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        /* GIỮ NGUYÊN LAYOUT CŨ CỦA BẠN */
        body { background-color: #f4f5f7; font-family: Arial, sans-serif; margin: 0; }
        .container { display: flex; }
        .trangchu { padding: 0 20px; display: flex; flex-direction: column; width: 330px; background-color: #dbdbdb; min-height: 100vh; }
        .tieude { border-bottom: 1px solid #b3b3b3; font-weight: bold; }
        .tieude p { font-size: 26px; margin: 15px 0; }
        .tieude i { margin-right: 5px; }
        .trangchu img { margin: 20px auto; border-radius: 5px; width: 120px; height: 100px; display: block; }
        .list-tieude { padding: 0 20px 20px 30px; }
        .list-tieude p { font-size: 22px; margin: 15px 0; color: #333; }
        .danhmuc { padding-bottom: 10px; border-bottom: 1px solid #b3b3b3; }
        .danhmuc a { display: flex; align-items: center; padding: 10px; font-size: 18px; text-decoration: none; color: #6c6c6c; transition: 0.3s; }
        .danhmuc a:hover { color: #000; background: #ccc; border-radius: 4px; }
        .danhmuc a i { width: 25px; text-align: center; margin-right: 10px; }
        .quanLyDH { margin-top: 50px; margin-left: 20px; width: 75%; flex: 1; padding-right: 20px; }
        .title { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; }
        .title h2 { margin: 0; }
        .search-container { margin-bottom: 20px; display: flex; }
        .search-form { display: flex; gap: 10px; background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .search-form input { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; width: 300px; outline: none; }
        .btn-search { background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-reset { background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-size: 14px; display: flex; align-items: center; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        td, th { font-size: 14px; border: 1px solid #a19898; text-align: center; padding: 12px 8px; }
        th { background-color: #f4f4f4; font-size: 16px; }
        .badge { padding: 4px 8px; border-radius: 10px; font-size: 14px; font-weight: bold; }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .action-group { display: flex; justify-content: center; gap: 5px; flex-wrap: wrap; }
        .action-group a { text-decoration: none; padding: 6px 10px; border-radius: 4px; font-size: 13px; color: white !important; display: inline-flex; align-items: center; gap: 4px; transition: 0.2s; }
        .action-group a:hover { opacity: 0.8; transform: scale(1.05); }
        .btn-info { background-color: #28a745; }
        .btn-edit { background-color: #ffc107; color: #212529 !important; }
        .btn-delete { background-color: #dc3545; }

        /* THÊM CSS PHÂN TRANG (GỌN GÀNG) */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 20px 0;
            gap: 10px;
        }
        .pagination a {
            text-decoration: none;
            color: #333;
            padding: 8px 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fff;
            transition: 0.3s;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
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
                    <a href="thongKe.php" style="font-weight:bold;"><i class="fa-solid fa-chart-line"></i> Thống kê</a>
                </div>
                <p><i class="fa-solid fa-user-gear"></i> Tài khoản</p>
                <div class="danhmuc">
                    <a href="../php/admin/logoutAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="quanLyDH">
            <div class="title">
                <h2>Danh sách sản phẩm</h2>
                <a href="taoMoi_SP.php" style="text-decoration: none; background: #007bff; color: white; padding: 8px 15px; border-radius: 4px;">
                    <i class="fa-solid fa-plus"></i> Tạo mới SP
                </a>
            </div>

            <div class="search-container">
                <form action="" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Nhập tên hoặc ID sản phẩm..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
                    <?php if(!empty($searchTerm)): ?><a href="quanLySP.php" class="btn-reset">Làm mới</a><?php endif; ?>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Loại</th>
                        <th>Hoạt động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>#<?php echo $product['id'] ?></td>
                                <td style="text-align: left;"><?php echo $product['ten'] ?></td>
                                <td><?php echo $product['so_luong'] ?></td>
                                <td>
                                    <?php if($product['trang_thai'] == 1): ?>
                                        <span class="badge badge-success">Còn hàng</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Hết hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $product['loai'] ?></td>
                                <td class="action-group">
                                    <a class="btn-info" href="xemCT_SP.php?id=<?php echo $product['id'] ?>" title = "Xem chi tiết" ><i class="fa-solid fa-eye"></i> Xem</a>
                                    <a class="btn-edit" href="capNhat_SP.php?id=<?php echo $product['id'] ?>" title = "Cập nhật"><i class="fa-solid fa-pen-to-square"></i> Sửa</a>
                                    <a class="btn-delete" href="xoa_SP.php?id=<?php echo $product['id'] ?>" title = "Xóa" onclick="return confirm('Xóa sản phẩm này?')"><i class="fa-solid fa-trash"></i> Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Không tìm thấy sản phẩm nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if($page > 1): ?>
                    <a href="?page=<?php echo $page-1 ?>&search=<?php echo $searchTerm ?>">&laquo; Trước</a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i ?>&search=<?php echo $searchTerm ?>" 
                       class="<?php echo ($i == $page) ? 'active' : '' ?>">
                        <?php echo $i ?>
                    </a>
                <?php endfor; ?>

                <?php if($page < $totalPages): ?>
                    <a href="?page=<?php echo $page+1 ?>&search=<?php echo $searchTerm ?>">Sau &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>