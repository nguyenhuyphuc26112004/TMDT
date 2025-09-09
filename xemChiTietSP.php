<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" 
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Document</title>
    <style>
        body{
            background-color: #eef0f4;
        }
        .product{
            display: flex;
            margin: 50px 390px;
            justify-content: center;
            padding: 20px;
            border: 2px solid rgb(210, 202, 202);
            border-radius: 10px;
        }
        .image-product{
            margin-right: 20px;
        }
        .product img{
            width: 280px;
            height: 350px;
            border-radius: 10px;
        }
        .info-product{
            padding-top: 10px;
            margin-left: 20px;
            font-size: 30px;
        }
        .info-product p{
            margin: 10px 0;
        }
        .price{
            display: flex;
            align-items: center;
            margin: 10px 0;
            color: red;
        }
        .soLuong{
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .buy-amount button{
            width: 35px;
            height: 35px;
            outline: none;
            border: 1px solid #ececec;
            cursor: pointer;
        }
        .buy-amount button:hover{
            background-color: #ececec;
        }
        .buy-amount button i{
            color: #909090;
        }
        button:hover i{
            color: #4f4f4f;
        }
        .buy-amount{
            display: flex;
            margin-left: 30px;
        }
        input{
            width: 40px;
            text-align: center;
            border: 1px solid #ececec;
        }
        .btn{
            display: flex;
            align-items: center;
        }
        .btn button{
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .themGioHang button{
            color: #151534;
            background: none;
            padding: 10px;
            font-size: 18px;
            border: 1px solid #d9d9d9;
        }
        .themGioHang button:hover{
            color: #28c361;
            border-color: #28c361;
        }
        .datHang button{
            background-color: #28c361;
            padding: 10px;
            border: none;
            font-size: 18px;
            color: white;    
        }
    </style>
</head>
<body>
    <?php
        require('layout/header.php');
        require('php/client/getObjectById.php');
        $idSP = $_GET['id'];
        $sanPham = getObjectById($con, 'san_pham', $idSP);
    ?>
    <div class="product">
        <div class="image-product">
            <img src="admin/img/<?php echo $sanPham['anh'] ?>" alt="lỗi <?php echo $sanPham['anh'] ?>">
        </div>
        <div class="info-product">
            <div class="name-product">
                <p><?php echo $sanPham['ten'] ?></p>
            </div>
            <div class="price">
                <p>Giá: <?php echo number_format($sanPham['gia'], 0, ',', '.') ?> VNĐ</p>
            </div>
            <form action="themSanPham.php" method="post">
                <input type="hidden" name="idSanPham" value="<?php echo $sanPham['id'] ?>">
                <input type="hidden" name="giaSanPham" value="<?php echo $sanPham['gia'] ?>">
                <div class="soLuong">
                    <p>Số lượng: </p>
                    <div class="buy-amount">
                        <button type="button" class="minus-btn" onclick="giam()"><i class="fa-solid fa-minus"></i></button>
                        <input type="text" id="amount" name="soLuong" value="1" min="1">
                        <button type="button" class="plus-btn" onclick="tang()"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
                <div class="btn">
                    <div class="themGioHang">
                        <button type="submit">Thêm vào giỏ hàng</button>
                    </div>
                    <div class="datHang">
                        <button type ="submit">Đặt hàng</button>
                    </div>
                </div> 
            </form>
        
        </div>
    </div>
    <script src="./js/xemChiTietSP.js"></script>
</body>
</html>