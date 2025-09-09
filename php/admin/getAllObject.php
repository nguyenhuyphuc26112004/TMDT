<?php
// echo "hello from getAllObject.php <br>";

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');
// require('../connectMysql.php');



// $con thuộc file connectMysql.php
// hàm
function hello()
{
    return "hello from getAllObject.php";
}

function getAll_object($con, $tableName)
{
    // Câu lệnh SQL
    $sql = "SELECT * FROM $tableName";

    // Mảng lưu kết quả
    $Users = [];

    // Chuẩn bị câu lệnh
    $stmt = $con->prepare($sql);

    // Kiểm tra câu lệnh đã sẵn sàng chưa
    if ($stmt) {

        // Thực thi câu lệnh SQL
        $stmt->execute();

        // Lấy kết quả
        $result = $stmt->get_result();

        // Hứng dữ liệu từ câu truy vấn SQL
        while ($user = $result->fetch_assoc()) {
            // Thêm mỗi người dùng vào mảng
            $Users[] = $user;
        }

        // Trả về mảng chứa tất cả người dùng
        return $Users;
    }

    // Trả về mảng rỗng nếu có lỗi
    return [];
}
