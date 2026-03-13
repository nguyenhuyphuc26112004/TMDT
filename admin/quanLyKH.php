<?php
// 1. XỬ LÝ LOGIC & DỮ LIỆU
require_once('../php/checkSession.php');
checkSession(2);

require_once('../php/admin/getAllObject.php');
require_once('../php/admin/cart.php'); // $con nằm trong file này hoặc connectMysql

// Lấy từ khóa tìm kiếm người dùng
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($con, trim($_GET['search'])) : '';

if (!empty($searchTerm)) {
    // Truy vấn lọc theo ID, Họ tên, Tên đăng nhập hoặc Email (đã cập nhật)
    $sql = "SELECT * FROM nguoi_dung WHERE id LIKE '%$searchTerm%' OR ho_ten LIKE '%$searchTerm%' OR ten_dang_nhap LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
    $result = mysqli_query($con, $sql);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Lấy toàn bộ người dùng
    $users = getAll_object($con, 'nguoi_dung');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <style>
        /* GIỮ NGUYÊN CSS GỐC CỦA BẠN */
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
        .search-container { margin-bottom: 20px; display: flex; }
        .search-form { display: flex; gap: 10px; background: #fff; padding: 10px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .search-form input { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; width: 300px; outline: none; }
        .btn-search { background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-reset { background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; font-size: 14px; display: flex; align-items: center; }
        table { border-collapse: collapse; width: 100%; background: #fff; }
        td, th { font-size: 14px; border: 1px solid #a19898; text-align: center; padding: 12px 8px; }
        th { background-color: #f4f4f4; font-size: 16px; }
        .action-group { display: flex; justify-content: center; gap: 5px; flex-wrap: wrap; }
        .action-group a { text-decoration: none; padding: 6px 10px; border-radius: 4px; font-size: 13px; color: white !important; display: inline-flex; align-items: center; gap: 4px; transition: 0.2s; }
        .action-group a:hover { opacity: 0.8; transform: scale(1.05); }
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
                    <a href="quanLyKH.php" style="background: #ccc; color: #000;"><i class="fa-solid fa-users"></i> Quản lý người dùng</a>
                    <a href="quanLySP.php"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</a>
                    <a href="quanLyDH.php"><i class="fa-solid fa-cart-shopping"></i> Quản lý đơn hàng</a>
                    <a href="thongKe.php"><i class="fa-solid fa-chart-line"></i> Thống kê</a>
                </div>
                <p><i class="fa-solid fa-user-gear"></i> Tài khoản</p>
                <div class="danhmuc">
                    <a href="../php/admin/logoutAdmin.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
                </div>
            </div>
        </div>

        <div class="quanLyDH">
            <div class="title">
                <h2><i class="fa-solid fa-user-group"></i> Danh sách người dùng</h2>
                <a href="dangKy.php" style="text-decoration: none; background: #007bff; color: white; padding: 8px 15px; border-radius: 4px; font-weight: bold;">
                    <i class="fa-solid fa-user-plus"></i> Tạo mới KH
                </a>
            </div>

            <div class="search-container">
                <form action="" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Nhập tên, ID, username hoặc email..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
                    <?php if(!empty($searchTerm)): ?><a href="quanLyKH.php" class="btn-reset">Làm mới</a><?php endif; ?>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Họ và tên</th>
                        <th>Tên đăng nhập</th>
                        <th>Email liên hệ</th>
                        <th width="280px">Hoạt động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><b>#<?php echo $user['id'] ?></b></td>
                                <td style="text-align: left; padding-left: 15px;"><?php echo htmlspecialchars($user['ho_ten']) ?></td>
                                <td><?php echo htmlspecialchars($user['ten_dang_nhap']) ?></td>
                                <td><?php echo htmlspecialchars($user['email']) ?></td>
                                <td class="action-group">
                                    <a class="btn-info" href="xemCT_KH.php?id=<?php echo $user['id'] ?>"><i class="fa-solid fa-eye"></i> Xem</a>
                                    <a class="btn-edit" href="capNhat_KH.php?id=<?php echo $user['id'] ?>"><i class="fa-solid fa-user-pen"></i> Sửa</a>
                                    <a class="btn-delete" href="xoa_KH.php?id=<?php echo $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')"><i class="fa-solid fa-trash-can"></i> Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Không tìm thấy người dùng nào phù hợp.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>