<?php

require "database.php";

$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $vaue4 = $value5 = $value1 = $value2 = $value3 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {

        $value1 = test_input($_POST["value1"]);
        $value2 = test_input($_POST["value2"]);
        $value3 = test_input($_POST["value3"]);
		$value4 = test_input($_POST["value4"]);
        $value5 = test_input($_POST["value5"]);
        if ($value1 > 0 && $value1 < 60 && $value3 < 999999) {
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $time =  date('Y-m-d H:i:s');
            echo '<br>'. "here time: " . $time . '<br>';
            
            $sql = "INSERT INTO SensorData (value1, value2, value3, value4, value5, reading_time)
            VALUES ('" . $value1 . "', '" . $value2 . "', '" . $value3 . "', '" . $value4 . "', '" . $value5 . "', '" . $time . "')";

            
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            
                    
            $temptime = "SELECT `reading_time` FROM `FilterData` WHERE id = ( SELECT MAX(id) FROM `FilterData`)";
            $result = $conn->query($temptime);
            $row = $result->fetch_assoc();
            echo "time : ".  $temptime = $row["reading_time"];
            $time =  date('Y-m-d H:i:s');
            echo '<br>'. "here time: " . $time . '<br>';
            
            echo $da = (strtotime(date('Y-m-d H:i:s')) - strtotime($temptime));
            if (( strtotime(date('Y-m-d H:i:s')) - strtotime($temptime) ) >= 3600) {
                
	    $sqll = "INSERT INTO FilterData (value1, value2, value3, value4, value5, reading_time)
            VALUES ('" . $value1 . "', '" . $value2 . "', '" . $value3 . "', '" . $value4 . "', '" . $value5 . "', '" . $time . "')";
            
            if ($conn->query($sqll) === TRUE) {
                echo "New record created successfully";
            } 
            else {
                echo "Error: " . $sqll . "<br>" . $conn->error;
            }
}
            
            $conn->close();
        }
        else {
            echo "Wrong API Key provided.";
        }
    } else {
        echo "Communication Error";
    } 
}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
