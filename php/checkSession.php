<?php

// function này check xem người dùng có quyền vào trang admin không 
function checkSession($requiredRole)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Kiểm tra session tồn tại và vai trò phù hợp
    if (!isset($_SESSION["tenDangNhap"]) || $_SESSION["vaiTro"] !== $requiredRole) {
        // Nếu không đúng, chuyển hướng về trang đăng nhập
        header('Location: dangNhap.php');
        exit;
    }
}

// function này check xem người dùng đã đang nhập hay chưa
function checkSessionClient()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Kiểm tra session tồn tại và vai trò phù hợp
    if (!isset($_SESSION["tenDangNhap"])) {
        // Nếu không đúng, chuyển hướng về trang đăng nhập
        header('Location: dangNhap.php');
        exit;
    }
}
