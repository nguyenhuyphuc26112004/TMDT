<!-- <?php
session_start();
// ktra session có biến tên là tenDangNhap đã tồn tại hay chưa 
if (isset($_SESSION['tenDangNhap'])) {
    // đã tồn tại
    // xóa nó khỏi session

    session_unset(); // Xóa tất cả biến trong session
    session_destroy(); // Hủy phiên làm việc
}
header("Location: ../../admin/dangNhap.php?dang-xuat");

// khi đăng nhập -> đã gán $_SESSION['tenDangNhap'] = $tenDangNhap -> đăng nhập thành công -> bắt đầu 1 phiên mới
//->  echo $_SESSION['tenDangNhap'] sẽ in ra tenDangNhap của bạn
?> -->
<?php
session_start();

// 1. Chỉ xóa các biến định danh của Admin
if (isset($_SESSION['tenDangNhap'])) {
    unset($_SESSION['tenDangNhap']);
}

// Nếu bạn có biến phân quyền riêng, hãy xóa nó luôn
if (isset($_SESSION['id_vai_tro'])) {
    unset($_SESSION['id_vai_tro']);
}

// 2. KHÔNG DÙNG session_destroy() vì nó sẽ đá cả Client ra ngoài
// Chỉ chuyển hướng về trang đăng nhập của admin
header("Location: ../../admin/dangNhap.php?dang-xuat");
exit();
?>