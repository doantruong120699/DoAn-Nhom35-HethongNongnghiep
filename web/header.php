<?php
 session_start();
if (isset($_SESSION['name'])==false) 
    header("location:index.php");

require "database.php";
           								
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Dự báo thời tiết</title>
	<link rel="stylesheet" href="css/index.css">
</head>
<body id="home">
    <!-- Bắt đầu thẻ wrap: Thẻ này chứa tất cả các thẻ con có kích thước cố định-->
    <div id="wrap">
        <div id="masterhead">
            <img src="images/goodday.png" alt="Logo Thời Tiết">
            <div id="slogan">
                <h1>Du Bao Thoi Tiet</h1>
            </div>
        </div>
        <!-- BẮT ĐẦU: thẻ mainav, thẻ này chứa menu của trang web -->
        <div id="mainnav">
            <ul>
                <li><a href="trangchu.php">Trang chủ</a></li>
                <li><a href="seendata.php">Xem Dữ Liệu</a></li>
                <li><a href="datawithhour.php">Dữ liệu theo giờ</a></li>
                <li><a href="esp_control/esp-outputs.php">Thiết Bị</a></li>
                <li><a href="#">Giúp Đỡ</a></li>
                
            </ul>

            <ul id="login">
                <li>
                    	<div style ="border: solid 1px gray; margin-top: 0px; color: white;">
						Admin:  <?php echo $_SESSION['name'] . " "; ?>
						<a href="logout.php" 
						style = "	
						text-decoration: none;
						color: white;
						border-left: solid 1px gray;
						background: black;
						width: 100px;float: right;">
						Đăng Xuất</a>
					</div>
                </li>
            </ul>
        </div> <!-- Kết Thúc: thẻ mainav, thẻ này chứa menu của trang web -->