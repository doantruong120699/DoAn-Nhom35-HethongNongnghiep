<?php
    session_start();
    if (isset($_SESSION['name'])==false) 
        header("location:index.php");


    require "callapi.php";
    require "callapiw2.php";
    require "database.php";
        
    $sql = "SELECT id, value1, value2, value3, value4, value5, reading_time FROM FilterData ORDER BY id DESC";
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);
    //Doc du lieu mua tu bang RainData
    $sql2 = "SELECT id, reading_time, rain FROM RainData ORDER BY id DESC";
    if ($result2 = $conn->query($sql2)) {
        $row_value_rain = "";
        while ($row2 = $result2->fetch_assoc()) {
            $row_id = $row2["id"];
            $row_reading_time = $row2["reading_time"];
            
            if ($row_reading_time == $row['reading_time']) {
                $row_value_rain = $row2["rain"];
            }
          
        }
       
    }  
    //Doc du lieu mua tu bang RainDataw2
    $sql3 = "SELECT id, reading_time, rain FROM RainDataw2 ORDER BY id DESC";
    if ($result3 = $conn->query($sql3)) {
        $row_value_rain_2 = "";
        while ($row3 = $result3->fetch_assoc()) {
            $row_id_2 = $row3["id"];
            $row_reading_time_2 = $row3["reading_time"];
            
            if ($row_reading_time_2 == $row['reading_time']) {
               $row_value_rain_2 = $row3["rain"];
            }
          
        }
       
    } 
    function sw_get_current_weekday($x) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $weekday = date("l",$x);
        $weekday = strtolower($weekday);
        switch($weekday) {
            case 'monday':
                $weekday = 'Thứ hai';
                break;
            case 'tuesday':
                $weekday = 'Thứ ba';
                break;
            case 'wednesday':
                $weekday = 'Thứ tư';
                break;
            case 'thursday':
                $weekday = 'Thứ năm';
                break;
            case 'friday':
                $weekday = 'Thứ sáu';
                break;
            case 'saturday':
                $weekday = 'Thứ bảy';
                break;
            default:
                $weekday = 'Chủ nhật';
                break;
        }
        return $weekday;
        // .', ' . date('H:00',$x);
    // Tạo xong thì ngắt kết nối
    $conn->close();
    }
        
									
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
    <!-- BẮT ĐẦU: thẻ innerwrap, thẻ này chứa nội dung chính của trang web -->
    <div id="innerwrap">
        <!-- BẮT ĐẦU: thẻ sidebar, thẻ này chứa các câu cadao tục ngữ-->
        <div id="sidebar">
			
            <p style="margin-left: 50px;
                    margin-bottom: 3px;
                    font-size: 30px;
                    line-height: 30px;
                    font-weight: bold;
                    padding: 0px;">Đà Nẵng</p>
            <?php
                $date = strtotime($row['reading_time']);
                $hours = date('H:00',$date);
                echo '<span style="margin-left: 50px;">' . sw_get_current_weekday($date) .', ' . $hours . '<span>';               
                echo '<ul>';   		
				echo '<span style= "font-size: 70px; left: 60px;">' . '' . $row['value1'] . '<span>°C</span>'	. '</span>';
                echo '<li>' . 'Cập nhật lần cuối: '	. '</li>';
                echo '<li>' . $row['reading_time']	. '</li>';
                echo '</ul>';
				?>

        </div><!-- Kết thúc: thẻ sidebar, thẻ này chứa các câu cadao tục ngữ-->
        <!-- BẮT ĐẦU: thẻ sidebar, thẻ này chứa thông tin và mô tả về loài trâu-->
        <div id="main">
            <h1> </h1> 
            <br> <br> <br> <br> <br>
                <?php 

                    echo '<ul>';

                    echo '<li>' . 'Độ ẩm			:' . $row['value2']	. '%'	. '</li>';
					echo '<li>' . 'Áp suất	    	:' . $row['value3'] . 'hpa'  . '</li>';
					echo '<li>' . 'Tốc độ gió 		:' . $row['value4']	. 'm/s'. '</li>';
                    echo '<li>' . 'Độ ẩm đất		:' . $row['value5'] . '%'  . '</li>';
                    echo '<li style="color:red;">' . 'Lượng mưa 	    :' . $row_value_rain . 'mm'. '  (Artificial neural network)' . '</li>';
                    echo '<li style="color:green;">' . 'Lượng mưa 	    :' . $row_value_rain_2 . 'mm'. '  (Linear Regression)' .'</li>';
                    
                    echo '</ul>';
				?>
        </div><!-- Kết Thúc: thẻ Main, thẻ này chứa thông tin va mô tả về loài trâu -->
       
    </div> <!-- Kết Thúc: thẻ innerwrap, thẻ này chứa nội dung chính của trang web -->
    <div id="footer">
       <p >Trang web được xây dựng bởi nhóm sinh viên Đại học Bách khoa - Đại học Đà Nẵng.</p>
        </div>
    </div>
    
    </div> <!-- Kết thúc thẻ wrap: Thẻ này chứa tất cả các thẻ con có kích thước cố định-->
</body>
<script type="text/javascript">
    init_reload();
    function init_reload(){
        setInterval( function() {
                   window.location.reload();
 
          },300000);
    }
</script>
</html>