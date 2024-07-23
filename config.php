<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "base_datos_fin_vac";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
