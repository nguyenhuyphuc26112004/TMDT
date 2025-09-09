<?php
// echo "hello from getObjectById.php <br>";
// require('../connectMysql.php');
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

// $con thuộc file connectMysql.php
// hàm

// input : tablename , id 
// output : array


function getObjectById($con, $tableName, $id)
{
    // Câu lệnh SQL
    $sql = "SELECT * FROM $tableName WHERE id = ? ";

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // gán các tham số vào câu lệnh
        $stmt->bind_param("i", $id);

        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // lấy 1 dòng đầu tiên trong mảng kq ( có thể null )
        return $result->fetch_assoc();
    }

    // Trả về mảng rỗng nếu có lỗi
    return null;
}
