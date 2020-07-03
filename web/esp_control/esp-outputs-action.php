<?php
    include_once('esp-database.php');

    $action = $id = $name = $gpio = $state = $value = "";

    if(isset($_GET["submit"])) {
        echo $id = test_input($_GET["id"]);
        echo $value = test_input($_GET["value"]);
        $result = updateValue($id,$value);
        echo $result;
        header("location: esp-outputs.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = test_input($_POST["action"]);
        if ($action == "output_create") {
            $name = test_input($_POST["name"]);    
            $gpio = test_input($_POST["gpio"]);
            $state = test_input($_POST["state"]);
            $board = test_input($_POST["value"]);
            $result = createOutput($name, $board, $gpio, $state);

            
            echo $result;
        }
        
        else {
            echo "No data posted with HTTP POST.";
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $action = test_input($_GET["action"]);
        if ($action == "outputs_state") {
            
            $result = getAllOutputStates();
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $rows[$row["gpio"]] = $row["state"];
                    $rows[$row["gpio"] . "-on"] = $row["value"];
                }
            }
            //echo var_dump($rows); 
            echo json_encode($rows);
            
        }
        else if ($action == "output_update") {
            $id = test_input($_GET["id"]);
            $state = test_input($_GET["state"]);
            $value = test_input($_GET["value"]);
            $result = updateOutput($id, $state, $value);
            echo $result;
        }

        else {
            echo "Invalid HTTP request.";
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
