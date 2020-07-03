<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$servername = "localhost";
//Database name
$dbname = "id13163525_esp_data";
//Database user
$username = "root";
//Database user password
$password = "";
$table_name = "FilterData";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$query = "SELECT * FROM " . $table_name . " ORDER BY id DESC LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) 
{
    // products array
    $products_arr=array();
    $products_arr["weather"]=array();
    // Sử dụng vòng lặp while để lặp kết quả
    while($row = $result->fetch_assoc()) {
        // extract row
        extract($row);
        
        $product_item=array(
            "id" => $id,
            "reading_time" => $reading_time,
            "nhietdo" => $value1,
            "doam" => $value2,
            "apsuat" => $value3,
            "gio" => $value4
        );
        array_push($products_arr["weather"], $product_item);
    }

    // set response code - 200 OK
    http_response_code(200);
  
    // show products data in json format
    $intput = json_encode($products_arr);
    echo $intput;
} 
else {
       // set response code - 404 Not found
       http_response_code(404);
  
       // tell the user no products found
       echo json_encode(
           array("message" => "No products found.")
       );
}

$conn->close();