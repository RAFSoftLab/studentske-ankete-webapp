<?php
$server_name = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "c8_rafanketa";


$conn = new mysqli($server_name, $db_username, $db_password, $db_name, "3307");
if ($conn->connect_error) {    
    die("connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8")

?>
