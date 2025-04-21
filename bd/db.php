<?php
session_start();

$servername = "127.0.0.1"; 
$username = "annie";   
$password = "qwerty123";   
$dbname = "annie"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>