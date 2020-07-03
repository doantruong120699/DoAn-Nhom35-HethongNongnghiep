
<?php
     session_start();
     if (isset($_SESSION['name'])==false) 
         header("location:index.php");
    include_once('esp-database.php');

    $result = getAllOutputs();
    $html_buttons = null;
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ($row["state"] == "1"){
                $button_checked = "checked";
            }
            else {
                $button_checked = "";
            }
            $html_buttons .=  '<div class="col-xs-9 col-sm-9 col-md-4 col-lg-4">' . 
            '<div class="thumbnail">' .
            
            '<h3>' . $row["name"] .  ' </h3>
            
            <div><form action="esp-outputs-action.php" method="get">
            <input type="hidden" name="id" min="0" id="outputValue" value = '. $row["id"] .'>
            <input type="number" name="value" min="0" id="outputValue" value = '. $row["value"] .'> 
            <input type="submit" name="submit" value="submit">
            </form>
            </div><br>

            <label class="switch"><input type="checkbox" onchange="updateOutput(this)" id="' . $row["id"] . '" ' . $button_checked . '><span class="slider"></span></label>'
            . '</div> '
            . '</div> '
            ;
        }
    }

    
?>

<!DOCTYPE html>
<html lang="en"><head>
	<title> Example </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">  
	<script type="text/javascript" src="../vendor/bootstrap.js"></script>
	<link rel="stylesheet" href="../vendor/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="esp-style.css">
    <title>ESP Output Control</title>
</head>
<body >
	

	<div class="container">
        
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<ul class = "list-group">
			    	<li class="list-group-item"><a href="../trangchu.php">Trang chủ</a></li>
			    	<li class="list-group-item"><a href="../seendata.php">Xem dữ liệu</a></li>
			    	<li class="list-group-item"><a href="../datawithhour.php">Dữ liệu theo giờ</a></li>
			    	<li class="list-group-item"><a href="../esp_control/esp-outputs.php">Điều khiển thiết bị</a></li>
			    </ul>
			    
			</div> <!-- end list group -->

            
            
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <div class="row">
                    <div class="col-xs-9 col-sm-9 col-md-6 col-lg-6">
                        <h2 class="tilte" style =" font-weight: BOLD; left: 500px;">Điều Khiển Thiết Bị</h2> 
                    </div>
                </div>
            
                <?php echo $html_buttons; ?>
			</div> 


		</div>
	</div>

    
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<hr>
				Website 2020
				<br><br>
			</div>
		</div>
	</div>


    <script>
        function updateOutput(element) {
            var xhr = new XMLHttpRequest();
            if(element.checked){
                xhr.open("GET", "esp-outputs-action.php?action=output_update&id="+element.id+"&state=1", true);
            }
            else {
                xhr.open("GET", "esp-outputs-action.php?action=output_update&id="+element.id+"&state=0", true);
            }
            xhr.send();
        }

        function updateValue(element) {
            var xhr = new XMLHttpRequest();
            //khoi tao yeu cau
            xhr.open("POST", "esp-outputs-action.php", true);
            //Đặt giá trị của tiêu đề yêu cầu HTTP.
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    alert("Output created");
                    setTimeout(function(){ window.location.reload(); });
                }
            }
            var outputValue = document.getElementById("outputValue").value;
            
            var httpRequestData = "action=value_update&value="+outputValue+"&id="+element.id;
            //Gửi yêu cầu. 
            xhr.send(httpRequestData);
        }

        
    </script> 
</body>
</html>