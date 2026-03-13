<?php
// echo "hello from save_object.php <br>";
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php');


function saveUser($con, $id_vai_tro, $ho_ten, $gioi_tinh, $email, $ten_dang_nhap, $mat_khau) {
    // Câu lệnh SQL với 8 giá trị (id_vai_tro, ho_ten, gioi_tinh, email, ten_dang_nhap, mat_khau, so_lan_sai, thoi_gian_khoa)
    $sql = "INSERT INTO nguoi_dung (id_vai_tro, ho_ten, gioi_tinh, email, ten_dang_nhap, mat_khau, so_lan_sai, thoi_gian_khoa) 
            VALUES (?, ?, ?, ?, ?, ?, 0, NULL)";
    
    $stmt = $con->prepare($sql);
    
    // 1 kiểu INT (i) và 5 kiểu STRING (s) cho 6 tham số truyền vào
    $stmt->bind_param("isssss", $id_vai_tro, $ho_ten, $gioi_tinh, $email, $ten_dang_nhap, $mat_khau);
    
    $ketQua = $stmt->execute();
    $stmt->close();
    
    return $ketQua;
}
?>