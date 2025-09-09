<?php
// echo "hello from getAllObjectByCondition.php <br>";

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');
// require('../connectMysql.php');





function getCartByUser($con, $idNguoiDung)
{
    // Câu lệnh SQL
    $sql = "SELECT * FROM gio_hang WHERE id_nguoi_dung = ? ";


    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // gán các tham số vào câu lệnh
        $stmt->bind_param("i", $idNguoiDung);

        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    // Trả về mảng rỗng nếu có lỗi
    return null;
}
