<?php
require "database.php";

$response = file_get_contents('https://thoitiet-nhan123.herokuapp.com/'); 
$response = json_decode($response); 

// echo "<pre>";
// print_r($response);
// echo "</pre>";
foreach($response as $key => $value) {
  if ($key == "Time")  {
     $value1 = $value ;
    // echo "<br>";
  }
  if ($key == "luongmua") {
     $value2 = $value ;
    // echo "<br>";
  }
}


date_default_timezone_set('Asia/Ho_Chi_Minh');
$temptime = "SELECT `reading_time` FROM `RainDataw2` WHERE reading_time = ( SELECT MAX(reading_time) FROM `RainDataw2`)";
$result = $conn->query($temptime);
$row = $result->fetch_assoc();
$temptime = $row["reading_time"];
// echo "time : ".  $temptime;
// echo "request time : " . $value1 . "<br>";
// echo $da = (strtotime($value1) - strtotime($temptime)) ;

if (( strtotime($value1) - strtotime($temptime) ) > 0) {
    
$sqll = "INSERT INTO RainDataw2 (id, reading_time, rain)
VALUES (NULL, '" . $value1 . "' , '" . $value2 . "' )";

if ($conn->query($sqll) === TRUE) {
   // echo "New record created successfully";
} 
else {
  //  echo "Error: " . $sqll . "<br>" . $conn->error;
}
}

$conn->close();


