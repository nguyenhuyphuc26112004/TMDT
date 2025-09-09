
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <title>Đơn hàng </title>
    <style>
        .quanLyDH {
            margin: 50px 30px;
            height: 100vh;

        }
        .quanLyDH table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 13px;
        }
        td a {
            text-decoration: none;
            padding: 8px 10px;
            border: 1px solid white;
            border-radius: 5px;
            width: 6px;
            color: blue;
        }
        .title-cart h2 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }
        td .pay {
            padding: 10px;
            background-color: green;
            color: #fff;
        }
        td .pay:hover {
            cursor: pointer;
            opacity: 0.6;
        }

        .submit {
            margin: 25px 45%;
        }

        .submit>a {
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #acacac;
            padding: 8px 23%;
            height: 45px;
            width: 100%;
            font-size: 17px;
        }
    </style>
</head>

<body>
    <?php
        require('layout/header.php');
        require('php/client/getObjectByCondition.php');
        require('php/client/getObjectById.php');
        $idDonHang = $_GET['id'];
        $ctDonHang = getOrderDetailByOrder($con, $idDonHang);
    ?>
    <div class="quanLyDH">
        <div class="title-cart">
            <h1>Đơn hàng</h1>
        </div>
        <table>
            <tr>
                <th>Tên sản phẩm </th>
                <th>Số lượng </th>
                <th>Giá sản phẩm </th>
                <th>Ảnh sản phẩm </th>
            </tr>
            <?php
            foreach ($ctDonHang as $cTDH) {
                $sp = getObjectById($con, 'san_pham', $cTDH['id_san_pham']);

            ?>
                <tr>
                    <td><?php echo $sp['ten'] ?></td>
                    <td><?php echo $cTDH['so_luong'] ?></td>
                    <td><?php echo number_format($sp['gia'], 0, ',', '.') ?> VND</td>
                    <td>
                        <img src="admin/img/<?php echo $sp['anh'] ?>" alt="" width="200px" height="200px">
                    </td>
                </tr>

            <?php
            } ?>

        </table>
        <div class="submit">
            <a href="donHang.php" style="background-color: #1C8552; color : white;">Trở lại</a>
        </div>
    </div>

    <!-- footer -->
    <?php
    require('layout/footer.php');
    ?>
    <!-- end footer -->
</body>

</html>