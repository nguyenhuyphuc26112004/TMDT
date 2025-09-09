 <?php
    // ktra người dùng đăng nhập hay chưa
    require('php/checkSession.php');
    checkSessionClient();
    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Xóa khách hàng</title>
     <style>
         .delete {
             margin: 130px 30px;
             height: 100vh;
             font-family: Arial, sans-serif;

         }

         .delete .title {
             border-bottom: 1px solid #979494;
             margin: 25px 0px 15px 0px;
         }

         .warning {
             background-color: #f925164e;
             color: rgb(76, 12, 12);
             justify-content: space-between;
             align-items: center;
             width: 98.7%;
             padding: 3px 10px;
             font-size: 16px;
             border-radius: 2px;

         }

         .del {
             margin-top: 25px;
         }
         .del a{
            text-decoration: none;
            padding: 10px;
            border: 1px solid #acacac;

            border-radius: 5px;
         }
         button {
             border-radius: 5px;
             border: 1px solid #acacac;
             padding: 8px 25px;
             height: 36.8px;
             width: 99.9px;
             font-size: 17px;
             box-sizing: border-box;
         }

         .back {
             text-align: center;
             text-decoration: none;
             border-radius: 5px;
             border: 1px solid #acacac;
             padding: 8px 25px;
             height: 49px;
             width: 100px;
             font-size: 17px;
             box-sizing: border-box;
         }
     </style>
 </head>

 <body>
     <!-- header -->
     <?php
        // require('layout/header.php');
        require('php/client/getObjectByCondition.php'); // đẻ sử dụng biên con : kết nối tới database 
        $idDonHang = $_GET['id'];
        // Kiểm tra nếu người dùng nhấn nút "Hủy"
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idCheck'])) {
            $idDonHang = $_POST['idCheck'];
            // sql
            $sql = "UPDATE don_hang SET trang_thai = 'Đã hủy' WHERE id = ? ";

            // chuan bị cau lenh sql
            $stmt = $con->prepare($sql); // true nếu sẵn sàng

            // gắn các tham số cho câu lệnh
            $stmt->bind_param("i", $idDonHang);

            // thuc thi câu lệnh
            $stmt->execute();


            header('Location: donHang.php');
            exit; // không thực hiện các câu lệnh phía sau 
        }
        ?>
     <!-- code -->
     <div class="delete">
         <div class="title">
             <h2>Hủy đơn hàng id = <?php echo $_GET['id'] ?></h2>
         </div>
         <div class="main">
             <div class="warning">
                 <p>Bạn chắc chắn muốn hủy đơn hàng này chứ ?</p>
             </div>
             <div class="del">
                 <form action="huyDonHang.php" method="POST">
                     <input type="hidden" name="idCheck" , value="<?php echo $_GET['id'] ?>">
                     <a href="donHang.php" style="background-color: #1C8552; color : white; padding: 8px 19px;">Trở lại</a>
                     <button type="submit" style="background-color: #c5303a; color : white;">
                         Hủy
                     </button>
                 </form>

             </div>
         </div>
     </div>
 </body>

 </html>