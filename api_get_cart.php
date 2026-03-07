<?php
header('Content-Type: application/json');
session_start();

require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/connectMysql.php'); 
require($_SERVER['DOCUMENT_ROOT'] . '/TMDT/php/client/cart.php'); 

if (!isset($_SESSION['idNguoiDung'])) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit();
}

$idNguoiDung = $_SESSION['idNguoiDung'];

try {
    $gioHang = checkCart($con, $idNguoiDung);

    if (!$gioHang) {
        echo json_encode(['status' => 'success', 'data' => [], 'total' => 0]);
        exit();
    }

    $idGioHang = $gioHang['id'];
    $sql = "SELECT ct.*, sp.ten, sp.anh 
            FROM ct_gio_hang ct
            JOIN san_pham sp ON ct.id_san_pham = sp.id
            WHERE ct.id_gio_hang = ?";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $idGioHang);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    $tongTien = 0;

    while ($row = $result->fetch_assoc()) {
        $row['thanh_tien'] = $row['so_luong'] * $row['gia'];
        $tongTien += $row['thanh_tien'];
        $items[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'idGioHang' => $idGioHang,
        'data' => $items,
        'tongCong' => $tongTien
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}