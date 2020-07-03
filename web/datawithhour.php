
    <?php
    require "header.php";
    require "database.php";
    
    $sql = "SELECT id, value1, value2, value3, value4, value5, reading_time FROM FilterData ORDER BY id DESC";
    
    echo "<table border = '1' width = '100%'>";
    echo "<caption>DATA</caption>";
    echo "<TR>
    <TH>Id</TH>
    <TH>Nhiệt độ</TH>
    <TH>Độ ẩm</TH>
    <TH>Áp suất</TH>
    <TH>Tốc độ gió</TH>
    <TH>Độ ẩm đất</TH>
     <TH>Time</TH>
    </TR>";
     
    if ($result = $conn->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            $row_id = $row["id"];
           
            $row_value1 = $row["value1"];
            $row_value2 = $row["value2"]; 
            $row_value3 = $row["value3"]; 
            $row_value4 = $row["value4"];
            $row_value5 = $row["value5"];
            $row_reading_time = $row["reading_time"];
           
            echo '<tr> 
                    <td>' . $row_id . '</td> 
                    <td>' . $row_value1 . '°C' . '</td> 
                    <td>' . $row_value2 . '%' . '</td> 
                    <td>' . $row_value3 . 'hpa' . '</td> 
                    <td>' . $row_value4 . 'm/s ' . '</td>
                    <td>' . $row_value5 . '%' . '</td> 
                    <td>' . $row_reading_time . '</td> 
                  </tr>';
        }
        $result->free();
    }
    
    $conn->close();
   


?>

   
