<?php
$servername = "localhost";
//Database name
$dbname = "id13163525_esp_data";
//Database user
$username = "root";
//Database user password
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
} 