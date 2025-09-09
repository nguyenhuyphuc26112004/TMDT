<?php
// echo "hello form checkLogin.php";
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

// ktra login của trang admin  -> chỉ có admin mới vào dcdc
function checkLoginAdmin($con, $username, $password)
{
    // Câu lệnh SQL
    // admin có id = 2 ; user có id = 1
    $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ? and mat_khau = ? and id_vai_tro = 2 ";

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // gán các tham số vào câu lệnh
        $stmt->bind_param("ss", $username, $password);

        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION["vaiTro"] = 2; // Lưu vai trò Admin vào session
            return true;
        } else {
            return false;
        }
    }

    // Trả về false nếu có lỗi
    return false;
}


// admin và user đều có thể vào
function checkLogin($con, $username, $password)
{
    $sql = "SELECT * FROM nguoi_dung WHERE ten_dang_nhap = ? AND mat_khau = ?";

    // Chuẩn bị câu lệnh SQL
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Lấy thông tin người dùng
            $row = $result->fetch_assoc();
            // Lưu vai trò vào session
            $_SESSION["vaiTro"] = $row['id_vai_tro']; // Lưu vai trò của người dùng
            return true;
        } else {
            return false; // Nếu không có tài khoản
        }
    }
    return false;
}
