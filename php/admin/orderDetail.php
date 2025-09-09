<?php
// echo "hello from orderDetail.php <br>";
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');
// require('../connectMysql.php');

function getOrderDetailByOrder($con, $idDonHang)
{
    $sql = "SELECT * FROM ct_don_hang WHERE id_don_hang = ?";
    // Mảng lưu kết quả
    $orderDetails = [];

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // gán các tham số
        $stmt->bind_param("i", $idDonHang);
        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Hứng dữ liệu từ câu truy vấn SQL
        while ($orderDetail = $result->fetch_assoc()) {
            // Thêm mỗi người dùng vào mảng
            $orderDetails[] = $orderDetail;
        }

        // Trả về mảng chứa tất cả người dùng
        return $orderDetails;
    }

    // Trả về mảng rỗng nếu có lỗi
    return [];
}
