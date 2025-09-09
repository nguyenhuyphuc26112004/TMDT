<?php
// echo "hello from getObjectById.php <br>";
// require('../connectMysql.php');
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');

// $con thuộc file connectMysql.php
// hàm

// input :  con ( để kết nối với sql ) ,id 
// output : array
function getRoleById($con, $id)
{
    // Câu lệnh SQL : lấy ra tên role thông qua id role
    $sql = "SELECT vai_tro.ten FROM vai_tro WHERE id = ? ";

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
