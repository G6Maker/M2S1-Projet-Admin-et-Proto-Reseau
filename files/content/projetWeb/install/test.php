<?php
    $conn = new mysqli($_POST["hostname"], $_POST["username"], $_POST["password"], $_POST["db"], $_POST["port"]);
    if($conn->connect_error){
        echo "fail";
        $conn->close();
    } else {
        echo "success";
        $conn->close();
    }
?>